<?php

namespace common\modules\dashboard\controllers;

use Yii;
use common\modules\dtr\models\Dtr;
use common\modules\dtr\models\EmployeeDtrType;
use common\models\Employee;
use common\models\Holiday;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\db\Transaction;
use yii\db\Expression;
use yii\db\Query;
/**
 * Default controller for the `dashboard` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $am = Dtr::findOne([
                'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                'date' => date("Y-m-d"),
                'time' => 'AM',
                'year' => date('Y'),
                'month' => date('F'),
            ]);
        
        $pm = Dtr::findOne([
            'emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            'date' => date("Y-m-d"),
            'time' => 'PM',
            'year' => date('Y'),
            'month' => date('F'),
        ]);
        
        $celebrants = Employee::find()
                    ->where([
                        'MONTH(birth_date)' => date("m"),
                        'work_status' => 'Active'
                    ])
                    ->orderBy(['DAY(birth_date)' => SORT_ASC])
                    ->all();

        $holidays = Holiday::find()
                    ->where(['>=', new Expression('MONTH(holiday_date)'), date('m')])
                    ->orderBy(['MONTH(holiday_date)' => SORT_ASC, 'DAY(holiday_date)' => SORT_ASC])
                    ->all();

        $connection = Yii::$app->ipms;

        $currentDateSql = "select curdate() as date, dayname(curdate()) as day, date_format(concat(curdate(),' ', curtime()),'%p') as meridian";
        $currentDate = $connection->createCommand($currentDateSql)
            ->queryAll();

        $weeklyDtrSql = "
            select emp_id, dtr_id, date,
            DATE_FORMAT(date, '%W') as days,
            total_with_out_pass_slip,
            total_with_pass_slip,
            total_tardy,
            total_UT,
            total_pass_slip,
            am_in,
            am_out,
            pm_in,
            pm_out,
            total_OT,
            multiplied_total_OT,
            ((TIME_TO_SEC(total_with_pass_slip) / 60)/60) as total_with_pass_slip_dbl
            from tblemp_dtr_type
            where emp_id = :emp_id
            and date >=(
            select distinct(week_lower_limit) from tblweekly_dtr_summary
            where :date between week_lower_limit and DATE_ADD(week_upper_limit,INTERVAL 2 DAY))
            and date <=(
            select distinct(DATE_ADD(week_upper_limit,INTERVAL 2 DAY)) from tblweekly_dtr_summary
            where :date between week_lower_limit and DATE_ADD(week_upper_limit,INTERVAL 2 DAY))
            order by date
        ";

        $weeklyDtrs = $connection->createCommand($weeklyDtrSql)
            ->bindValues([
                ':emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                ':date' => $currentDate[0]['date'],
            ])
            ->queryAll();

        $total = 0;

        $hrsToRendersql = "select distinct(total_hours) as total_hours
                                from tblweekly_dtr_summary
                                where week_lower_limit <= :date and week_upper_limit >= :date";

        $hrsToRender = $connection->createCommand($hrsToRendersql)
            ->bindValues([
                ':emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                ':date' => $currentDate[0]['date'],
            ])
            ->queryAll();

        $hrsToRenderInHrsSql = "select SEC_TO_TIME('".(($hrsToRender[0]['total_hours'] * 60)*60)."') as total";
        $hrsToRenderInHrsQuery = $connection->createCommand($hrsToRenderInHrsSql)
            ->queryAll();

        $hrsToRenderInHrs = $hrsToRenderInHrsQuery[0]['total'];

        $hrsToGo = $hrsToRender[0]['total_hours'];
        $totalInHrs = "00:00:00";
        $hrsToGoInHrs = "00:00:00";

        if(!empty($weeklyDtrs)){
            foreach($weeklyDtrs as $dtr){
                $total += $dtr['total_with_pass_slip_dbl'];
            }
        }

        $totalInHrsSql = "select SEC_TO_TIME('".(($total * 60)*60)."') as total";
        $totalInHrsQuery = $connection->createCommand($totalInHrsSql)
            ->queryAll();
        
        $totalInHrs = $totalInHrsQuery[0]['total'];

        if($total < $hrsToRender[0]['total_hours']){
            $hrsToGo -= $total;
        }

        $hrsToGoSql = "select SEC_TO_TIME('".(($hrsToGo * 60)*60)."') as total";
        $hrsToGoQuery = $connection->createCommand($hrsToGoSql)
            ->queryAll();

        $hrsToGoInHours = $hrsToGoQuery[0]['total'];

        $dayIndex = 0;
        $atLeast = 0;

        if(date("l") == 'Monday')
        {
            $atLeast = 8;
            $dayIndex = 0;
        }
        else if(date("l") == 'Tuesday')
        {
            $atLeast = 16;
            $dayIndex = 1;
        }
        else if(date("l") == 'Wednesday')
        {
            $atLeast = 24;
            $dayIndex = 2;
        }
        else if(date("l") == 'Thursday')
        {
            $atLeast = 32;
            $dayIndex = 3;
        }
        else if(date("l") == 'Friday')
        {
            $atLeast = 40;
            $dayIndex = 4;
        }

        $recommendation = '';

        if(
            ($currentDate[0]['day'] != 'Sunday') && 
            ($currentDate[0]['day'] !='Saturday') && 
            ($currentDate[0]['meridian'] == 'PM') &&
            ($weeklyDtrs[$dayIndex]['pm_in']) &&
            (!$weeklyDtrs[$dayIndex]['pm_out']) && 
            ($weeklyDtrs[$dayIndex]['dtr_id'] != 'Reg 08-05') && 
            ($weeklyDtrs[$dayIndex]['dtr_id'] != 'UT1 6:30-4:30') && 
            ($weeklyDtrs[$dayIndex]['dtr_id'] != 'UT2 6:00-5:00') && 
            ($weeklyDtrs[$dayIndex]['dtr_id'] != '08-05'))
        {
            $ppsNotYetAccounted = '00:00:00';
            if((trim($weeklyDtrs[$dayIndex]['total_with_pass_slip']) == trim($weeklyDtrs[$dayIndex]['total_with_out_pass_slip'])) && ($weeklyDtrs[$dayIndex]['total_pass_slip']))
            {
                $ppsNotYetAccounted = $weeklyDtrs[$dayIndex]['total_pass_slip'];
            }

            $totalUntilCurrentDay = 0;
            for($dd = 0; $dd <= $dayIndex; $dd++){
                $totalUntilCurrentDay += $weeklyDtrs[$dd]['total_with_pass_slip_dbl'];
            }

            $neededHrsForTheDay = $atLeast - $totalUntilCurrentDay;

            $pmTimeIn = '';
            $pmTimeInTodaySql = "select time(time_in) as time_in, time_to_sec(time(time_in)) as time_sec from tblactual_dtr
            where date = curdate() and emp_id= :emp_id and time = 'PM'";
            $pmTimeInTodayQuery = $connection->createCommand($pmTimeInTodaySql)
                ->bindValue(':emp_id', Yii::$app->user->identity->userinfo->EMP_N)
                ->queryAll();

            $pmTimeInToday = $pmTimeInTodayQuery[0];

            $pmTimeIn = $pmTimeInToday['time_sec'] < 46800 ? '13:00:00' : $pmTimeInToday['time_in']; 

            $recommendedTimeOutSql = "select 
                DATE_FORMAT(
                    concat(
                        curdate(),
                        ' ',
                        addtime(
                            '00:00:01',
                            addtime(
                                '".$ppsNotYetAccounted."',
                                addtime(
                                    '".$pmTimeIn."',
                                    (SEC_TO_TIME('".((($neededHrsForTheDay * 60)*60)+0)."'))
                                )
                            )
                        )
                    ),
                '%r') as first, 
                time_to_sec(
                    addtime(
                        '00:00:01',
                        addtime(
                            '".$ppsNotYetAccounted."',
                            addtime(
                                '".$pmTimeIn."',
                                (SEC_TO_TIME('".((($neededHrsForTheDay * 60)*60)+0)."'))
                            )
                        )
                    )
                ) as second";
            $recommendedTimeOutQuery = $connection->createCommand($recommendedTimeOutSql)
                ->queryAll();

            //echo "<pre>"; print_r($recommendedTimeOutQuery); exit;

            $recommendedTimeOut = $recommendedTimeOutQuery[0];

            if(isset($recommendedTimeOut) && $recommendedTimeOut['second'] > 68400){

                $recommendation = "With your accumulated rendered time you'll not be able to achieve this.<br>
                                    To make your total rendered time as close as possible to the expected time you should <br>
                                    time out at not earlier than <b><font color = red>7:00:00 PM</font></b> today.";

            }elseif(isset($recommendedTimeOut) && $recommendedTimeOut['second'] < 57600){

               $recommendation = "To achieve this you should
                      time out at not earlier than <b><font color = red>".$recommendedTimeOut['first']."</font></b> today.
                       <div class=separator>
                      </div>
                      <div class=separator>
                      </div>
                      <B>NOTE</B>: <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      The suggested time out is earlier than <b><font color = red>4:00:00 PM</font></b>
                      please be reminded that if you are to follow it, you will incur an undertime. <br>As per policy, staffs
                      should stay in the office during the core working hours (9:30:00 AM to 4:00:00 PM).
                      ";

            }
            else
            {
             $recommendation = "To achieve this you should time out at not earlier than <b><font color = red>".$recommendedTimeOut['first']."</font></b> today.";
            }
        }

        $currentMonthSql =  "select distinct(month) as month from tblweekly_dtr_summary 
        where week_lower_limit <= curdate() 
        and date_add(week_upper_limit, interval 2 day) >= curdate()";

        $currentMonthQuery = $connection->createCommand($currentMonthSql)
            ->queryAll();

        $currentMonth = $currentMonthQuery[0];

        $currentYearSql =  "select distinct(year) as year from tblweekly_dtr_summary
        where week_lower_limit <= curdate()
        and date_add(week_upper_limit, interval 2 day) >= curdate()";

        $currentYearQuery = $connection->createCommand($currentYearSql)
            ->queryAll();

        $currentYear = $currentYearQuery[0];

        $nextMonthSql = "SELECT monthname(DATE_ADD('".$currentYear['year']."-".date("m", strtotime($currentMonth['month']))."-01', INTERVAL 1 Month)) as month";

        $nextMonthQuery = $connection->createCommand($nextMonthSql)
            ->queryAll();
        
        $nextMonth = $nextMonthQuery[0];

        $nextYearSql = "SELECT year(DATE_ADD('".$currentYear['year']."-".date("m", strtotime($currentMonth['month']))."-01', INTERVAL 1 Month)) as year";

        $nextYearQuery = $connection->createCommand($nextYearSql)
            ->queryAll();
        
        $nextYear = $nextYearQuery[0];

        $getWLCarryOverSql = "select COALESCE(total_days_earned_orig, 0) as CARRIED_OVER_FROM_LAST_MONTH
                            from tblmonthly_credit_transaction
                            where emp_id = :emp_id
                            and month = :month
                            and year = :year
                            and credit_id = 'WL'";
        $getWLCarryOverQuery = $connection->createCommand($getWLCarryOverSql)
                ->bindValues([
                    ':emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                    ':month' => $currentMonth['month'],
                    ':year' => $currentYear['year'],
                ])
                ->queryAll();

        $getWLCarryOver = $getWLCarryOverQuery[0];

        $getWLDeductedHoursNotUtSql = "select COALESCE(sum(deduction), 0) as deducted_days_not_ut
        from tblcredit_deduction
        where (reason <> 'UT'
        and emp_id = :emp_id
        and month = :month
        and year = :year
        and credit_id = 'WL')
        or(reason = 'UT'
            and emp_id = :emp_id
            and month = :month
            and year = :year
            and credit_id = 'WL'
            and DATE_ADD(week_lower_limit,INTERVAL 7 DAY) <= curdate()
        )
        or(reason <> 'UT'
            and emp_id = :emp_id
            and month = :next_month
            and year = :next_year
            and credit_id = 'WL'
        )";

        $getWLDeductedHoursNotUtQuery = $connection->createCommand($getWLDeductedHoursNotUtSql)
                ->bindValues([
                    ':emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                    ':month' => $currentMonth['month'],
                    ':next_month' => $nextMonth['month'],
                    ':year' => $currentYear['year'],
                    ':next_year' => $nextYear['year'],
                ])
                ->queryAll();

        $getWLDeductedHoursNotUt = $getWLDeductedHoursNotUtQuery[0];

        $totalRunningWL = $getWLCarryOver['CARRIED_OVER_FROM_LAST_MONTH'] - $getWLDeductedHoursNotUt['deducted_days_not_ut'];

        $tempMonth = $currentMonth['month'] == 'January' ? 'December' : date("F", strtotime(date("n", strtotime($currentMonth['month'])) - 1));
        $tempYear = $currentMonth['month'] == 'January' ? $currentYear['year'] - 1 : $currentYear['year'];
        $tempYear = $tempMonth == 'December' ? $currentYear['year'] - 1 : $tempYear;

        $getVLCarryOverSql = "select COALESCE(total_days_earned_deducted, 0) as CARRIED_OVER_FROM_LAST_MONTH
        from tblmonthly_credit_transaction
        where emp_id = :emp_id
        and month = :month
        and year = :year
        and credit_id = 'VL'";

        $getVLCarryOverQuery = $connection->createCommand($getVLCarryOverSql)
                ->bindValues([
                    ':emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                    ':month' => $tempMonth,
                    ':year' => $tempYear,
                ])
                ->queryAll();

        $getVLCarryOver = $getVLCarryOverQuery[0];

        $getVLJustEarnedSql = "select COALESCE(days_added, 0) as EARNED_FOR_THE_MONTH
            from tblmonthly_credit_transaction
            where emp_id = :emp_id
            and month = :month
            and year = :year
            and credit_id = 'VL'";

        $getVLJustEarnedQuery = $connection->createCommand($getVLJustEarnedSql)
                ->bindValues([
                    ':emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                    ':month' => $currentMonth['month'],
                    ':year' => $currentYear['year'],
                ])
                ->queryAll();

        $getVLJustEarned = $getVLJustEarnedQuery[0];

        $getVLDeductedHrsNotUt = "select COALESCE(sum(deduction), 0) as deducted_days_not_ut
        from tblcredit_deduction
        where (reason <> 'UT'
        and emp_id = :emp_id
        and month = :month
        and year = :year
        and credit_id = 'VL')
        or(reason = 'UT'
            and emp_id = :emp_id
            and month = :month
            and year = :year
            and credit_id = 'VL'
            and DATE_ADD(week_lower_limit,INTERVAL 7 DAY) <= curdate()
        )
        or(reason <> 'UT'
            and emp_id = :emp_id
            and month = :next_month
            and year = :next_year
            and credit_id = 'VL'
        )";

        $getVLDeductedHrsNotUtQuery = $connection->createCommand($getVLDeductedHrsNotUt)
            ->bindValues([
                ':emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
                ':month' => $currentMonth['month'],
                ':next_month' => $nextMonth['month'],
                ':year' => $currentYear['year'],
                ':next_year' => $nextYear['year'],
            ])
            ->queryAll();

        $getVLDeductedHrsNotUt = $getVLDeductedHrsNotUtQuery[0];

        $totalRunningVL = $getVLCarryOver['CARRIED_OVER_FROM_LAST_MONTH'] + $getVLJustEarned['EARNED_FOR_THE_MONTH'] - $getVLDeductedHrsNotUt['deducted_days_not_ut'];

        if(!empty($getVLJustEarned))
        {
            $getVLAllotedSql = "select COALESCE(alloted_days, 0) as alloted_days from tblcredit where credit_id = 'VL'";
            $getVLAllotedQuery = $connection->createCommand($getVLAllotedSql)
            ->queryAll();

            $getVLAlloted = $getVLAllotedQuery[0];

            $totalRunningVL -= $getVLAlloted['alloted_days'];
        }

        $getAdjustedVLSql = "select COALESCE(vl_diff, 0) as vl_diff from tblemp_credits_audited where emp_id = :emp_id";
        $getAdjustedVLQuery = $connection->createCommand($getAdjustedVLSql)
            ->bindValues([
                ':emp_id' => Yii::$app->user->identity->userinfo->EMP_N,
            ])
            ->queryAll();
        
        $getAdjustedVL = $getAdjustedVLQuery[0];
     
        $totalRunningVL += ($getAdjustedVL['vl_diff']);

        return $this->render('index', [
            'am' => $am,
            'pm' => $pm,
            'celebrants' => $celebrants,
            'holidays' => $holidays,
            'weeklyDtrs' => $weeklyDtrs,
            'hrsToRender' => $hrsToRender,
            'hrsToRenderInHrs' => $hrsToRenderInHrs,
            'total' => $total,
            'totalInHrs' => $totalInHrs,
            'hrsToGo' => $hrsToGo,
            'hrsToGoInHours' => $hrsToGoInHours,
            'atLeast' => $atLeast,
            'recommendation' => $recommendation,
            'currentDate' => $currentDate,
            'totalRunningWL' => $totalRunningWL,
            'totalRunningVL' => $totalRunningVL,
        ]);
    }
}
