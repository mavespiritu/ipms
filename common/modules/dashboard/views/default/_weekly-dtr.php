<div style="min-height: calc(100vh - 500px); max-height: 34vh; overflow-y: auto; font-size: 11px;">
    <table id="dtr-table" class="table table-responsive table-bordered table-condensed">
        <thead>
            <tr>
                <th>DATE</th>
                <th>AM IN</th>
                <th>AM OUT</th>
                <th>PM IN</th>
                <th>PM OUT</th>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($weeklyDtrs)){ ?>
            <?php foreach($weeklyDtrs as $dtr){ ?>
                <tr>
                    <td><b><?= date("F j", strtotime($dtr['date'])) ?></b></td>
                    <td align=center><?= $dtr['am_in'] ?></td>
                    <td align=center><?= $dtr['am_out'] ?></td>
                    <td align=center><?= $dtr['pm_in'] ?></td>
                    <td align=center><?= $dtr['pm_out'] ?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    <table id="dtr-table" class="table table-responsive table-bordered table-condensed">
        <tr>
            <th style="width: 50%; background-color: #F4F4F5; font-weight: bolder;">HRS TO RENDER:</th>
            <td align=center><?= $hrsToRender[0]['total_hours'].' ('.$hrsToRenderInHrs.')' ?></td>
        </tr>
        <tr>
            <th style="width: 50%; background-color: #F4F4F5; font-weight: bolder;">HRS RENDERED:</th>
            <td align=center><?= $total.' ('.$totalInHrs.')' ?></td>
        </tr>
        <tr>
            <th style="width: 50%; background-color: #F4F4F5; font-weight: bolder;">HRS TO GO:</th>
            <td align=center><?= $hrsToGo.' ('.$hrsToGoInHours.')' ?></td>
        </tr>
    </table>
    <p style="padding-right: 20px;">Today is <b><font color = red><?= $currentDate[0]['day'] ?></font></b>, it is expected that
        at the end of the day you should have at least rendered <b><font color = red><?= $atLeast ?> hours</font></b>. <?= $recommendation ?></p>
</div>