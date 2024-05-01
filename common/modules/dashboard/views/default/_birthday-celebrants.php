<?php
use frontend\assets\AppAsset;

$asset = AppAsset::register($this);
?>

<div style="min-height: calc(100vh - 500px); max-height: 34vh; overflow-y: auto; padding-right: 20px;">
<?php if($celebrants){ ?>
    <ul class="products-list product-list-in-box">
    <?php foreach($celebrants as $celebrant){ ?>
        <?php $base64Image = base64_encode($celebrant->picture); ?>
        <li class="item">
            <div class="product-img" style="position: relative;">
                <?= '<img src="data:image/jpeg;base64,' . $base64Image . '" alt="Image" class="direct-chat-img "style="border-radius: 50%; width: 40px; height: 40px;">' ?>
                <?php if(date("d") == date("d", strtotime($celebrant->birth_date))){ ?>
                    <img src="<?= $asset->baseUrl.'/images/birthday-hat.png' ?>" alt="Birthday Hat" style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); width: 20px; height: auto;">
                <?php } ?>
            </div>
            <div class="product-info">
                <?= $celebrant->fname.' '.$celebrant->lname ?>
                <?= date("F j", strtotime($celebrant->birth_date)) == date("F j") ? '<span class="label label-success pull-right">Today</span>' : '<span class="pull-right">'.date("F j", strtotime($celebrant->birth_date)).'</span>' ?>
                <span class="product-description">
                    <?= $celebrant->position_id ?>
                </span>
            </div>
        </li>
    <?php } ?>
    </ul>
<?php }else{ ?>
    <p class="text-center">No birthday celebrants found.</p>
<?php } ?>
</div>