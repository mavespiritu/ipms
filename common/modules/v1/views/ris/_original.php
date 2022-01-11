<?php
    use yii\helpers\Html;
    use yii\widgets\DetailView;
    use yii\bootstrap\ButtonDropdown;
    use yii\helpers\Url;

    $total = 0;
?>

<table class="table table-condensed table-hover">
    <thead>
        <tr>
            <th>Item</th>
            <th>Unit Cost</th>
            <th>Quantity</th>
            <td align=center><b>Total</b></td>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
    <?php if(!empty($originalItems)){ ?>
        <?php foreach($originalItems as $idx => $origItems){ ?>
            <tr>
                <th colspan=5><i><?= $idx ?></i></th>
            </tr>
            <?php if(!empty($origItems)){ ?>
                <?php foreach($origItems as $item){ ?>
                    <?= $this->render('_original-item', [
                        'model' => $model,
                        'item' => $item
                    ]) ?>
                    <?php $total += ($item['cost'] * $item['total']); ?>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    <?php }else{ ?>
        <tr>
            <td colspan=7 align=center>No original items included</td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan=2 align=right><b>Grand Total</b></td>
        <td>&nbsp;</td>
        <td align=right><b><?= number_format($total, 2) ?></b></td>
        <td>&nbsp;</td>
    </tr>
    </tbody>
</table>