<!DOCTYPE html>
<html lang="en">
  <head>
  <script>
var base = '<?=Yii::app()->baseUrl?>/';
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    
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
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/normalize.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/asScrollable.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/toastr.min.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/animate.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->baseUrl ?>/css/jquery-ui.css">

    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/ladda-themeless.min.css">

    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/cfm.css">
    
<script src="<?php echo Yii::app()->baseUrl ?>/js/jquery.min.js"></script>
<script src="<?php echo Yii::app()->baseUrl ?>/js/jquery-ui.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/plugins/print/jquery.PrintArea.js"></script>     
    <script src="<?php echo Yii::app()->baseUrl ?>/js/cfm.js"></script>
    <script src="<?php echo Yii::app()->baseUrl ?>/plugins/form/jquery.form.js"></script>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-78503947-3', 'auto');
  ga('send', 'pageview');

</script>

<script>
var base = '<?=Yii::app()->baseUrl?>';
</script>
  </head>
  <body>
    
    <!-- .wrap -->
    <section class="wrap wrap-bg">

      <!-- main -->
      <main>
        
        <?=$content?>

      </main>
      <!-- end / main -->
      
    </section>
    <!-- end / .wrap -->
    

    <!-- Script's -->
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.mousewheel.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.asScrollbar.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/holder.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.asScrollable.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/toastr.min.js"></script>

    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/spin.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/ladda.min.js"></script>

	    <script>

<?php if(Yii::app()->user->hasFlash('success')):?>

	toastrSuccess("<?php echo Yii::app()->user->getFlash('success'); ?>");

<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('info')):?>

toastrInfo("<?php echo Yii::app()->user->getFlash('info'); ?>");

<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('warning')):?>

toastrWarning("<?php echo Yii::app()->user->getFlash('warning'); ?>");

<?php endif; ?>

<?php if(Yii::app()->user->hasFlash('error')):?>

toastrError("<?php echo Yii::app()->user->getFlash('error'); ?>");

<?php endif; ?>

</script>
  </body>
</html>