<?php
$this->pageTitle = Yii::t('general', 'checkoutTitle');
?>
<script type="text/javascript"
	src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
<script type='text/javascript'
	src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
<!-- .pago-seleccion -->
<div class="pago-seleccion container-fluid">
	<!-- .row -->
	<div class="row">
		<!-- .col (left) -->
		<div class="col-sm-6 col-md-6 pago-participante-col-flex">

			<!-- .text -->
			<div class="text">
				<h2 class="bienvenido">
					<?=Yii::t('general', 'bienvenido')?> <img
						src="<?php echo Yii::app()->request->baseUrl; ?>/images/hardcode/Contest-Logo.png"
						alt="<?=$concurso->txt_name?>">
				</h2>
				<!-- <button type="button" class="btn btn-blue">Consulta las bases del concurso</button> -->
				<a href="<?=$concurso->txt_bases?>" target="_blank"
					class="btn btn-blue"><?=Yii::t('general', 'consulta')?></a>
				<!-- <p class="necesito-ayuda-p" data-toggle="modal" data-target="#modal-necesito-ayuda">Necesito Ayuda</p> -->

				<?php $this->renderPartial ( "necesitoAyuda", array()); ?>
			</div>
			<!-- end / .text -->


			<!-- footer -->
			<footer class="footer-fixed-int">
				<a href="http://2gom.com.mx/" target="_blank">powered by 2 Geeks one
					Monkey</a>
			</footer>
			<!-- end / footer -->

		</div>
		<!-- end / .col (left) -->

		<!-- .col (right) -->
		<div class="col-sm-6 col-md-6 pago-seleccion-datos">

			<!-- .pago-seleccion-user -->
			<div class="pago-seleccion-user">

				<p><?=Yii::t('general', 'saludo')?> <?=CHtml::encode(Yii::app()->user->concursante->txt_nombre)?> </p>
				<!-- .pago-seleccion-user-flip -->
				<div class="pago-seleccion-user-flip">

					<!-- .pago-seleccion-user-flip-front-side -->
					<div class="pago-user-flip pago-seleccion-user-flip-front-side"
						data-flip="front">
										<?php if(empty(Yii::app()->user->concursante->txt_image_url)){?>
										<span style="background-image: url(<?php echo Yii::app()->theme->baseUrl; ?>/images/users.jpg)"></span>
										<?php }else if(Yii::app()->user->concursante->b_login_social_network){?>
										<span style="background-image: url(<?=Yii::app()->user->concursante->txt_image_url?>)"></span>
										<?php }else{?>
										<span style="background-image: url(<?=Yii::app()->request->baseUrl."/images/profiles/".Yii::app()->user->concursante->txt_usuario_number."/".Yii::app()->user->concursante->txt_image_url?>)"></span>
										<?php }?>
									</div>
					<!-- end / .pago-seleccion-user-flip-front-side -->

					<!-- .pago-seleccion-user-flip-back-side  -->
					<div
						class="pago-user-flip pago-seleccion-user-flip-back-side closeSession"
						data-flip="back">
						<?php echo CHtml::link('<i class="icon ion-power"></i>',array('site/logout'), array("class"=>"pago-seleccion-user-logout")); ?>
					</div>
					<!-- end / .pago-seleccion-user-flip-back-side  -->
				</div>
				<!-- end / .pago-seleccion-user-flip -->

			</div>
			<!-- end / .pago-seleccion-user -->


			<!-- .pago-terminos-flip -->
			<div class="pago-terminos-flip">


				<!-- .pago-terminos-front-side -->
				<div class="pago-terminos-front-side pago-terminos-click"
					data-flip="front">



					<!-- .screen-int -->
					<div class="example box screen-pago"
						data-options='{"direction": "vertical", "contentSelector": ">", "containerSelector": ">"}'>
						<!-- SCROLL -->
						<div>
							<!-- SCROLL -->
							<div>

								<!-- .pago-seleccion-producto -->
								<div class="pago-seleccion-producto">

									<h2 class="selecciona-inscripcion"><?=Yii::t('checkout', 'header')?></h2>

									<div class="sec-producto-resumen">

										<div class="row">
											<div class="col-md-9">
												<p class="prodcuto"><?=$oc->txt_description?></p>
											</div>
											<div class="col-md-3 text-right">
												<p class="prodcuto-total">$ <?=$oc->num_sub_total?> CAD</p>
											</div>
										</div>

<?php

if (! empty ( $cupon->txt_identificador_unico )) {
	// echo $cupon->txt_identificador_unico . '<br>';
	// echo . '<br>';
	// echo $cupon->num_porcentaje_descuento . '<br>';
	// echo $message;
	?>
	
	<div class="row">
											<div class="col-md-9">
												<p class="prodcuto"><?=$cupon->txt_descripcion?></p>
											</div>
											<div class="col-md-3 text-right">
												<p class="prodcuto-total prodcuto-cupon">$ -<?php
												
												if ($cupon->b_porcentaje == 1) {
													// echo $cupon->num_porcentaje_descuento;
													echo (($cupon->num_porcentaje_descuento*$oc->num_sub_total)/100);
												} else {
													if (! empty ( $oc->id_cupon )) {
														echo  number_format ( ((( $cupon->num_porcentaje_descuento))), 2 ) ;
													} else {
														echo  $oc->num_sub_total;
													}
												}
												
												?> CAD</p>
											</div>
										</div>
	<?php
} else {
	// echo $message;
}
?>

</div>

<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'id' => 'form-pago',
		'enableAjaxValidation' => false 
) );
?>
	<h2 class="selecciona-inscripcion"><?=Yii::t('checkout', 'headerCupon')?></h2>
									<div class="row">
										<div class="col-md-4">
			<?=$form->textField($cupon,'txt_identificador_unico',array("class"=>"form-control",'placeholder'=>Yii::t('checkout', 'placeholderCupon'))); ?>
		</div>
		<div class="col-md-8 padding-0">
			<?php /* echo CHtml::button ( "", array (
					"id" => "addCupon",
					"class" => "btn btn-agregar-cupon",
					'style' => 'font-size: .9em; padding: 5px 15px;',
					'value' => 'Agregar cupón' 
			) ); */
			?>
			<button class="btn btn-agregar-cupon ladda-button" id="addCupon" data-style="zoom-out"><span class="ladda-label"><?=Yii::t('checkout', 'addCupon')?></span></button>
		</div>
		<div class="col-sm-12 col-md-12">
			<p class="check-producto-no-valido"><?=$message?></p>
		</div>
	</div>	
	

	<?php $this->endWidget(); ?>


<div class="row">
										<!-- <div class="col-sm-12 col-md-12 items items-pay"> -->
										<div class="col-sm-12 col-md-12 items">
											<form id='tipo-pago'>
											<?php if($oc->num_total>0){ ?>
												<h2 class="selecciona-inscripcion"><?=Yii::t('checkout', 'headerTipoPago')?></h2>		 
									<?php
												$numProductos = 1;
												$checked = false;
												// Pinta los tipos de forma de pago por concurso
												foreach ( $tiposPagos as $tipoPago ) {
													?>
										<div class="radio-style radio-style-pay">
										<?php
													if ($numProductos == 1) {
														$checked = true;
													}
													// Radio button para el tipo de pago
													echo CHtml::radioButton ( "tipoPago", $checked, array (
															"class" => "formaPay",
															"data-formapago" => $tipoPago->idTipoPago->txt_payment_type_number,
															"value" => $tipoPago->idTipoPago->txt_payment_type_number,
															"data-name" => $tipoPago->idTipoPago->txt_name,
															"id" => "tipoPago" . $tipoPago->idTipoPago->txt_payment_type_number 
													) );
													
													// Imagen del tipo de pago
													echo "<label for='tipoPago" . $tipoPago->idTipoPago->txt_payment_type_number . "'>" . CHtml::image ( Yii::app ()->theme->baseUrl . "/images/" . $tipoPago->idTipoPago->txt_icon_url, '', array (
															'style' => 'width:50%' 
													) ) . "</label>";
													echo "<div class='check'></div>";
													// echo $tipoPago->idTipoPago->txt_icon_url;
													?>
										</div>
										<?php
													$numProductos ++;
												}
											}
											?>
									
								 </form>




										</div>
									</div>

									<div class="row">
										<div class="col-md-12 productos-totales">
											<div class="total-before-tax">
		<?=Yii::t('checkout', 'totalAntesDeImpuesto')?> <span>$ <?php
		if ($cupon->b_porcentaje == 1) {
			// echo $cupon->num_porcentaje_descuento;
			echo $subTotal = number_format ( ($oc->num_sub_total - (($cupon->num_porcentaje_descuento * $oc->num_sub_total) / 100)), 2 ) . " CAD";
		} else {
			if (! empty ( $oc->id_cupon )) {
				echo $subTotal = number_format ( ((($oc->num_sub_total - $cupon->num_porcentaje_descuento))), 2 ) . " CAD";
			} else {
				echo $subTotal = $oc->num_sub_total . ' CAD';
			}
		}
		?></span>
											</div>
											<div class="tax-precio">
 			<?=Yii::t('checkout', 'impuesto')?> <span>$ <?=$tax = number_format ( $subTotal * (0.13), 2 ) . " CAD";?></span>
											</div>
											<div class="total-precio"><?=Yii::t('checkout', 'total')?> <span>$<?=$oc->num_total?> CAD</span>
											</div>
										</div>
									</div>
								</div>
								<!-- end / .pago-seleccion-producto -->

							</div>
							<!-- end / SCROLL -->
						</div>
						<!-- end / SCROLL -->

					</div>
					<!-- end / .screen-int -->


					<div class="pago-seleccion-pago">
<?php
// echo CHtml::button ( "", array (
// "id" => "pagarCheck",
// "class" => "btn btn-yellow btn-make-payment",
// 'style' => 'font-size: 19px;',
// 'value' => 'Make Payment'
// ) );
?>
<?php if($oc->num_total>0){ ?>
	<button class="btn btn-yellow btn-make-payment ladda-button"
							id="pagarCheck" data-style="zoom-out">
							<span class="ladda-label"><?=Yii::t('checkout', 'submit')?></span>
						</button>
						<script>
$(document).ready(function(){

	$('#addCupon').on('click', function(e){
		e.preventDefault();

		var l = Ladda.create(this);
 		l.start();

		$('#form-pago').submit();
	});

	$('#pagarCheck').on('click', function(e){
		e.preventDefault();

		var l = Ladda.create(this);
	 	l.start();

		var contador = $('.formaPay:checked').length
		if (contador < 1) {

			toastrWarning("Por favor debe seleccionar una forma de pago");
			return false;
		}

		var form = $("#tipo-pago");
		var data = form.serialize();
		var url = '<?=Yii::app()->request->baseUrl?>/payments/updateOrdenCompra/t/<?=$oc->txt_order_number?>';

		$.ajax({
			url:url,
			data:data,
			method:'POST',
			type:'HTML',
			success:function(res){
				$('#container-pay-pal').html(res);
				$('#formPayPal').submit();
			}	
			
			
			});
		
	});
	
});

</script>
<?php }else{?>
	<button class="btn btn-yellow btn-make-payment ladda-button"
							id="pagarCheckFree" data-style="zoom-out">
							<span class="ladda-label">Continue</span>
						</button>

						<script>
$(document).ready(function(){

	$('#addCupon').on('click', function(e){
			e.preventDefault();
			$('#form-pago').submit();
		});

	$('#pagarCheckFree').on('click', function(e){
		e.preventDefault();

		var l = Ladda.create(this);
	 	l.start();

	 	var url = '<?=Yii::app()->request->baseUrl?>/payments/updateOrdenCompra/t/<?=$oc->txt_order_number?>';
		var form = $("#tipo-pago");
		form.attr('action', url);

		form.submit();
		
	});
	
});

</script>
<?php }?>
</div>


				</div>
				<!-- end / .pago-terminos-front-side -->

			</div>
			<!-- end / .pago-terminos-flip -->


		</div>
		<!-- end / .col (right) -->
	</div>
	<!-- end / .row -->
</div>
<!-- end / .pago-seleccion -->

<!-- Open pay pal -->
<div id="container-pay-pal"></div>
<!-- Close pay pal -->
