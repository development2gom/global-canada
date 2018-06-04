<?php
$debug = true;
$params = array ();

// Parametros para usar en localhost
if ($debug) {
	$params = array (
			// this is used in contact page
			'powered' => array (
					'urlPoweredAuthor' => 'http://2gom.com.mx/' 
			),
			'adminEmail' => 'webmaster@example.com',
			'pathBaseImagenes' => 'images/',
			// lineas necesarias para enviar email
			'contactEmail' => 'development@2gom.com.mx',
			'contactName' => '2Geeks Development Team',
			// Configuracion para enviar correo
			'SwifMailer' => array (
					// "serverSMTP" => 'node01.tmdhosting710.com',
					"serverSMTP" => 's210.tmd.cloud',
					"secure" => 'tls',
					"port" => '465',
					"userName" => 'development@2gom.com.mx',
					"password" => '_fJ.&@yhA&z;' 
			),
			// Configuración para facebook
			'Facebook' => array (
					"data" => array (
							
							'app_id' => '1317189418298509',
							'app_secret' => '6e9fd1cbdfc754e1be4ff5387573efc1',
							'default_graph_version' => 'v2.6' 
					),
					"callBack" => 'http://localhost/wwwComiteCanadaConcursante/usrUsuarios/callbackFacebook/t/3c391e5c9feec1f95282a36bdd5d41ba' 
			),
			'PayPal' => array (
					// 'payPalEmail' => 'beto@2gom.com.mx',
					'payPalModeUrl'=>'https://www.sandbox.paypal.com/cgi-bin/webscr',
					'returnUrl' => 'http://qa.2geeksonemonkey.com/global_judging/canada/community/usrUsuarios/revisarPago',
					'cancelUrl' => 'http://qa.2geeksonemonkey.com/global_judging/canada/community/usrUsuarios/concurso',
					'notifyUrl' => 'http://qa.2geeksonemonkey.com/global_judging/canada/community/usrUsuarios/iPNPayPal' 
			),
			'paginasHabilitadas'=>array(
					'profile'=>true,
			),
	);
} else {
	$params = array (
			
			// this is used in contact page
			'powered' => array (
					'urlPoweredAuthor' => 'http://2gom.com.mx/' 
			),
			'adminEmail' => 'webmaster@example.com',
			'pathBaseImagenes' => 'images/',
			// lineas necesarias para enviar email
			'contactEmail' => 'support@globaljudging.com',
			'contactName' => 'Global Judging',
			// Configuracion para enviar correo
			'SwifMailer' => array (
					// "serverSMTP" => 'node01.tmdhosting710.com',
					"serverSMTP" => 'us3.tmd.cloud',
					"secure" => 'tls',
					"port" => 465,
					"userName" => 'support@globaljudging.com',
					"password" => 'NouKJx3Hf' 
			),
			// Configuración para facebook
			'Facebook' => array (
					"data" => array (
							
// 							'app_id' => '1317189418298509',
// 							'app_secret' => '6e9fd1cbdfc754e1be4ff5387573efc1',
							'app_id' => '301217853545433',
							'app_secret' => '46a431453e596edabee61cdf5267db72',
							'default_graph_version' => 'v2.6' 
					),
					"callBack" => 'https://globaljudging.com/community/usrUsuarios/callbackFacebook/t/con_73cdf1c4f187ef82b94a945feae9d32a5783ddc6232582' 
			),
			'PayPal' => array (
					'payPalModeUrl'=>'https://www.paypal.com/cgi-bin/webscr',
					// 'payPalEmail' => 'beto@2gom.com.mx',
					'returnUrl' => 'https://globaljudging.com/community/usrUsuarios/concurso',
					'cancelUrl' => 'https://globaljudging.com/community/usrUsuarios/concurso',
					'notifyUrl' => 'https://globaljudging.com/community/usrUsuarios/iPNPayPal' 
			) 
	)
	;
}
return $params;


