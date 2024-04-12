<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tbldivision".
 *
 * @property string $division_id
 * @property string|null $division_name
 * @property int|null $order
 * @property string|null $item_no
 * @property int|null $hierarchy_num
 * @property string|null $parent
 *
 * @property TblcomAcknowledgement[] $tblcomAcknowledgements
 * @property TblcomDivisionAssignedCom[] $tblcomDivisionAssignedComs
 * @property TblcomCommunication[] $titles
 * @property TblpisHierarchyStructure $hierarchyNum
 * @property Division $parent0
 * @property Division[] $divisions
 * @property TbldivisionUser[] $tbldivisionUsers
 * @property TblempPositionItem[] $tblempPositionItems
 * @property Tblemployee[] $tblemployees
 * @property Tblemployee[] $tblemployees0
 * @property TblmanProject[] $tblmanProjects
 * @property Tbloic[] $tbloics
 * @property TblrdcDivisionCommittee[] $tblrdcDivisionCommittees
 */
class Division extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbldivision';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['division_id'], 'required'],
            [['order', 'hierarchy_num'], 'integer'],
            [['division_id', 'parent'], 'string', 'max' => 20],
            [['division_name'], 'string', 'max' => 255],
            [['item_no'], 'string', 'max' => 50],
            [['division_id'], 'unique'],
            //[['hierarchy_num'], 'exist', 'skipOnError' => true, 'targetClass' => HierarchyStructure::className(), 'targetAttribute' => ['hierarchy_num' => 'hierarchy_num']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['parent' => 'division_id']],
        ];
    }

    public static function getDb()
    {
        return \Yii::$app->ipms; // Return the second database connection
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'division_id' => 'Division ID',
            'division_name' => 'Division Name',
            'order' => 'Order',
            'item_no' => 'Item No',
            'hierarchy_num' => 'Hierarchy Num',
            'parent' => 'Parent',
        ];
    }

    /**
     * Gets query for [[TblcomAcknowledgements]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTblcomAcknowledgements()
    {
        return $this->hasMany(TblcomAcknowledgement::className(), ['division_id' => 'division_id']);
    } */

    /**
     * Gets query for [[TblcomDivisionAssignedComs]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTblcomDivisionAssignedComs()
    {
        return $this->hasMany(TblcomDivisionAssignedCom::className(), ['division_id' => 'division_id']);
    } */

    /**
     * Gets query for [[Titles]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTitles()
    {
        return $this->hasMany(TblcomCommunication::className(), ['title' => 'title', 'datetime_encoded' => 'datetime_encoded'])->viaTable('tblcom_division_assigned_com', ['division_id' => 'division_id']);
    } */

    /**
     * Gets query for [[HierarchyNum]].
     *
     * @return \yii\db\ActiveQuery
     */
   /*  public function getHierarchyNum()
    {
        return $this->hasOne(TblpisHierarchyStructure::className(), ['hierarchy_num' => 'hierarchy_num']);
    } */

    /**
     * Gets query for [[Parent0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Division::className(), ['division_id' => 'parent']);
    }

    /**
     * Gets query for [[Divisions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDivisions()
    {
        return $this->hasMany(Division::className(), ['parent' => 'division_id']);
    }

    /**
     * Gets query for [[TbldivisionUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTbldivisionUsers()
    {
        return $this->hasMany(TbldivisionUser::className(), ['division_id' => 'division_id']);
    } */

    /**
     * Gets query for [[TblempPositionItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTblempPositionItems()
    {
        return $this->hasMany(TblempPositionItem::className(), ['division_id' => 'division_id']);
    } */

    /**
     * Gets query for [[Tblemployees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['division_id' => 'division_id']);
    }

    /**
     * Gets query for [[TblmanProjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTblmanProjects()
    {
        return $this->hasMany(TblmanProject::className(), ['division_id' => 'division_id']);
    } */

    /**
     * Gets query for [[Tbloics]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getTbloics()
    {
        return $this->hasMany(Tbloic::className(), ['division_id' => 'division_id']);
    }
 */
    /**
     * Gets query for [[TblrdcDivisionCommittees]].
     *
     * @return \yii\db\ActiveQuery
     */
   /*  public function getTblrdcDivisionCommittees()
    {
        return $this->hasMany(TblrdcDivisionCommittee::className(), ['division_id' => 'division_id']);
    } */
}
