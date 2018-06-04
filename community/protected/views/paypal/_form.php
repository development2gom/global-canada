
<?php #echo CHtml::beginForm("https://www.paypal.com/cgi-bin/webscr", "POST",array("id"=>$idForm)); ?>
<?php echo CHtml::beginForm(Yii::app ()->params ['PayPal']['payPalModeUrl'], "POST",array("id"=>$idForm)); ?>

<?php echo CHtml::hiddenField("cmd", $cmd)?>
<?php echo CHtml::hiddenField("return", Yii::app ()->params ['PayPal']['returnUrl'])?>
<?php echo CHtml::hiddenField("custom", $custom)?>
<?php echo CHtml::hiddenField("notify_url", Yii::app ()->params ['PayPal']['notifyUrl'])?>
<?php echo CHtml::hiddenField("lc", $lc)?>
<?php echo CHtml::hiddenField("business", $business)?>
<?php echo CHtml::hiddenField("item_name", $item_name)?>
<?php echo CHtml::hiddenField("item_number", $item_number)?>
<?php echo CHtml::hiddenField("amount", $amount)?>
<?php echo CHtml::hiddenField("currency_code", $currency_code)?>
 <input TYPE="hidden" name="charset" value="utf-8">

<?php echo CHtml::endForm(); ?>
