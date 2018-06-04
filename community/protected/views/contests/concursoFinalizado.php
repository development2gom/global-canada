<link rel="stylesheet" type="text/css" href="/wwwComiteCanadaConcursante/assets/d7368059/ladda.min.css">
<!-- .login -->
<div class="login container">
	<!-- .row -->
	<div class="row">

		<!-- .col -->
		<div class="login-col-flex col-sm-6 col-md-6">
			<div class="login-text">
				<h2>
					<?=Yii::t('general', 'bienvenido')?> <img
						src="<?php echo Yii::app()->request->baseUrl; ?>/images/hardcode/Contest-Logo.png"
						alt="">
				</h2>
				<!-- <button type="button" class="btn btn-blue">Consulta las bases del concurso</button> -->
				
			</div>
		</div>
		<!-- end / .col -->

		<!-- .col -->
		<div class="login-col-flex col-sm-6 col-md-6">
			<!-- .login-form -->
			<div class="login-form concurso-finalizado">

				<img src="<?= Yii::app()->request->baseUrl ?>/images/hardcode/WPC-Logo.png" alt="CFM">
				<!-- <h1>Title</h1> -->

				<p>
					The call for entries has ended right now we are judging your photos.
					
				</p>
				<p>
					Please visit the PPOC web site for more information about the winners announcement. 
					
				</p>
				<p>
					Thanks for you entries and we wish you a lot of success.
					
				</p>
				

				<span>- Andre Amyot</span>


					

				<div class="all-right">&copy; <?=date('Y')?> <?=Yii::t('general','derechos')?></div>

			</div>
			<!-- end / .login-form -->
		</div>
		<!-- end / .col -->

	</div>
	<!-- end / .row -->
</div>
<!-- end / .login -->