<?php if($type != 'pdf'){ ?>
    <style>
    table{
        font-family: "Arial";
        border-collapse: collapse;
        width: 100%;
    }
    thead{
        font-size: 12px;
        text-align: center;
    }

    td{
        font-size: 10px;
        border: 1px solid black;
        padding: 5px;
    }

    th{
        text-align: center;
        border: 1px solid black;
        padding: 5px;
    }
</style>
<?php } ?>
<table class="table table-condensed table-bordered table-striped table-condensed table-responsive">
    <thead>
        <tr>
            <td rowspan=3 colspan=4 align=center><b>Project Category</b></td>
            <td colspan=5 align=center><b>Financial Requirements</b></td>
            <td colspan=5 align=center><b>Number of Projects</b></td>
            <td colspan=10 align=center><b>Number of Persons Employed</b></td>
            <td colspan=4 align=center><b>Number of Beneficiaries</b></td>
        </tr>
        <tr>
            <?php if($quarters){ ?>
                <?php foreach($quarters as $q => $quarter){ ?>
                    <td align=center rowspan=2><b><?= $q ?></b></td>
                <?php } ?>
            <?php } ?>
            <td align=center rowspan=2><b>Total</b></td>
            <?php if($quarters){ ?>
                <?php foreach($quarters as $q => $quarter){ ?>
                    <td align=center rowspan=2><b><?= $q ?></b></td>
                <?php } ?>
            <?php } ?>
            <td align=center rowspan=2><b>Total</b></td>
            <?php if($quarters){ ?>
                <?php foreach($quarters as $q => $quarter){ ?>
                    <td align=center colspan=2><b><?= $q ?></b></td>
                <?php } ?>
            <?php } ?>
            <td align=center colspan=2><b>Total</b></td>
            <?php if($quarters){ ?>
                <?php foreach($quarters as $q => $quarter){ ?>
                    <td align=center rowspan=2><b><?= $q ?></b></td>
                <?php } ?>
            <?php } ?>
        </tr>
        <tr>
            <?php if($quarters){ ?>
                <?php foreach($quarters as $quarter){ ?>
                    <?php if($genders){ ?>
                        <?php foreach($genders as $g => $gender){ ?>
                            <td align=center><b><?= $g ?></b></td>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            <?php if($genders){ ?>
                <?php foreach($genders as $g => $gender){ ?>
                    <td align=center><b><?= $g ?></b></td>
                <?php } ?>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php if(!empty($data)){ ?>
        <?php $i = 0; ?>
        <?php foreach($data as $agency => $agencies){ ?>
                <tr style="font-weight: bolder;">
                    <td colspan=4><?= $bigCaps[$i] ?>. <?= $agency ?></td>
                    <td align=right><?= number_format($agencies['content']['q1financial'], 2) ?></td>
                    <td align=right><?= number_format($agencies['content']['q2financial'], 2) ?></td>
                    <td align=right><?= number_format($agencies['content']['q3financial'], 2) ?></td>
                    <td align=right><?= number_format($agencies['content']['q4financial'], 2) ?></td>
                    <td align=right><?= number_format(
                        $agencies['content']['q1financial'] +
                        $agencies['content']['q2financial'] +
                        $agencies['content']['q3financial'] +
                        $agencies['content']['q4financial']
                        , 2) ?>
                    </td>
                    <td align=right><?= number_format($agencies['content']['q1physical'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q2physical'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q3physical'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q4physical'], 0) ?></td>
                    <td align=right><?= number_format(
                        $agencies['content']['q1physical'] +
                        $agencies['content']['q2physical'] +
                        $agencies['content']['q3physical'] +
                        $agencies['content']['q4physical']
                        , 0) ?>
                    </td>
                    <td align=right><?= number_format($agencies['content']['q1maleEmployed'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q1femaleEmployed'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q2maleEmployed'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q2femaleEmployed'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q3maleEmployed'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q3femaleEmployed'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q4maleEmployed'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q4femaleEmployed'], 0) ?></td>
                    <td align=right><?= number_format(
                        $agencies['content']['q1maleEmployed'] +
                        $agencies['content']['q2maleEmployed'] +
                        $agencies['content']['q3maleEmployed'] +
                        $agencies['content']['q4maleEmployed']
                        , 0) ?>
                    </td>
                    <td align=right><?= number_format(
                        $agencies['content']['q1femaleEmployed'] +
                        $agencies['content']['q2femaleEmployed'] +
                        $agencies['content']['q3femaleEmployed'] +
                        $agencies['content']['q4femaleEmployed']
                        , 0) ?>
                    </td>
                    <td align=right><?= number_format($agencies['content']['q1beneficiary'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q2beneficiary'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q3beneficiary'], 0) ?></td>
                    <td align=right><?= number_format($agencies['content']['q4beneficiary'], 0) ?></td>
                </tr>
            <?php if(!empty($agencies['categories'])){ ?>
                <?php $j = 0; ?>
                <?php foreach($agencies['categories'] as $category => $categories){ ?>
                    <tr>
                        <td align=right>&nbsp;</td>
                        <td colspan=3><?= $smallCaps[$j] ?>. <?= $category ?></td>
                        <td align=right><?= number_format($categories['content']['q1financial'], 2) ?></td>
                        <td align=right><?= number_format($categories['content']['q2financial'], 2) ?></td>
                        <td align=right><?= number_format($categories['content']['q3financial'], 2) ?></td>
                        <td align=right><?= number_format($categories['content']['q4financial'], 2) ?></td>
                        <td align=right><?= number_format(
                            $categories['content']['q1financial'] +
                            $categories['content']['q2financial'] +
                            $categories['content']['q3financial'] +
                            $categories['content']['q4financial']
                            , 2) ?>
                        </td>
                        <td align=right><?= number_format($categories['content']['q1physical'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q2physical'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q3physical'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q4physical'], 0) ?></td>
                        <td align=right><?= number_format(
                            $categories['content']['q1physical'] +
                            $categories['content']['q2physical'] +
                            $categories['content']['q3physical'] +
                            $categories['content']['q4physical']
                            , 0) ?>
                        </td>
                        <td align=right><?= number_format($categories['content']['q1maleEmployed'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q1femaleEmployed'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q2maleEmployed'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q2femaleEmployed'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q3maleEmployed'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q3femaleEmployed'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q4maleEmployed'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q4femaleEmployed'], 0) ?></td>
                        <td align=right><?= number_format(
                            $categories['content']['q1maleEmployed'] +
                            $categories['content']['q2maleEmployed'] +
                            $categories['content']['q3maleEmployed'] +
                            $categories['content']['q4maleEmployed']
                            , 0) ?>
                        </td>
                        <td align=right><?= number_format(
                            $categories['content']['q1femaleEmployed'] +
                            $categories['content']['q2femaleEmployed'] +
                            $categories['content']['q3femaleEmployed'] +
                            $categories['content']['q4femaleEmployed']
                            , 0) ?>
                        </td>
                        <td align=right><?= number_format($categories['content']['q1beneficiary'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q2beneficiary'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q3beneficiary'], 0) ?></td>
                        <td align=right><?= number_format($categories['content']['q4beneficiary'], 0) ?></td>
                    </tr>
                    <?php $j++ ?>
                <?php } ?>
            <?php } ?>
            <?php $i++ ?>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>

