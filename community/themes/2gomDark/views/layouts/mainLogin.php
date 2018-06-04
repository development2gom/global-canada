<!DOCTYPE html>
<html lang="en">
<head>
<script>
var base = '<?=Yii::app()->baseUrl?>/';
</script>
<meta charset="UTF-8">
<meta name="viewport"
	content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<!-- Favicon -->
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicons/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

<!-- Style -->

<link rel="stylesheet" type="text/css"
	href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
<link rel="stylesheet"
	href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap-extend.min.css">
<link rel="stylesheet"
	href="<?php echo Yii::app()->theme->baseUrl ?>/css/font-awesome.min.css">
<link rel="stylesheet"
	href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ionicons.min.css">
<link rel="stylesheet"
	href="<?php echo Yii::app()->theme->baseUrl; ?>/css/asScrollable.css">
<link rel="stylesheet"
	href="<?php echo Yii::app()->theme->baseUrl; ?>/css/toastr.min.css">
<!-- Plugins -->
<link rel="stylesheet"
	href="<?= Yii::app()->request->baseUrl ?>/plugins/animsition/animsition.min.css">
	
	<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ladda-themeless.min.css">

<link rel="stylesheet"
	href="<?php echo Yii::app()->theme->baseUrl ?>/css/cfm.css">

<script src="<?php echo Yii::app()->baseUrl ?>/js/jquery-1.9.1.min.js"></script>

<script async src="<?php echo Yii::app()->baseUrl ?>/js/cfm.js"></script>
<script async
	src="<?php echo Yii::app()->baseUrl ?>/plugins/form/jquery.form.js"></script>
<script  async src="<?php echo Yii::app()->theme->baseUrl; ?>/js/toastr.min.js"></script>

<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>
<body>
	<div class="animsition-loading"></div>
	<div class="animsition" data-animsition-out-class="fade-out">
		<!-- .wrap -->

		<section class="wrap wrap-bg example box screen"
			data-options='{"direction": "vertical", "contentSelector": ">", "containerSelector": ">"}'>
			<div>
				<div>

					<!-- main -->
					<main>
            <?=$content?>

          </main>
					<!-- end / main -->
          
          <?php
										$this->renderPartial ( '//layouts/footer' );
										?>
        </div>
			</div>
		</section>
		<!-- end / .wrap -->
	</div>
	<!-- Script's -->
	<script
		src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.mousewheel.min.js"></script>
	<script
		src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.asScrollbar.js"></script>
	<script
		src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.asScrollable.js"></script>

	<script
		src="<?= Yii::app()->request->baseUrl ?>/plugins/animsition/animsition.min.js"></script>
	<script src="<?= Yii::app()->request->baseUrl ?>/js/global.js"></script>
	
	   <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/spin.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/ladda.min.js"></script>


	<script>


$(window).load(function() {

<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
		if($key=="success"){
		?>
        toastrSuccess("<?=$message?>");
  <?php  
  }}
?>



<?php if(Yii::app()->user->hasFlash('success')):?>

	toastrSuccess("<?php echo Yii::app()->user->getFlash('success'); ?>");

<?php endif; ?>

<?php
$sessionComplete = Yii::app ()->user->getState ( "complete" );
if (isset ( $sessionComplete )) {
	echo 'toastrSuccess("' . Yii::app ()->user->complete . '");';
	Yii::app ()->user->setState ( "complete", null );
}
?>

<?php if(Yii::app()->user->hasFlash('info')):?>

toastrInfo("<?php echo Yii::app()->user->getFlash('info'); ?>");

<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('warning')):?>

toastrWarning("<?php echo Yii::app()->user->getFlash('warning'); ?>");

<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('error')):?>

toastrError("<?php echo Yii::app()->user->getFlash('error'); ?>");

<?php endif; ?>
});
</script>

</body>
</html>