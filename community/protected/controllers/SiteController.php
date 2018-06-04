﻿<?php
class SiteController extends Controller {
	/**
	 * Declares class-based actions.
	 */
	public function actions() {
		return array (
				// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha' => array (
						'class' => 'CCaptchaAction',
						'backColor' => 0xFFFFFF 
				),
				// page action renders "static" pages stored under 'protected/views/site/pages'
				// They can be accessed via: index.php?r=site/page&view=FileName
				'page' => array (
						'class' => 'CViewAction' 
				) 
		);
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex() {
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render ( 'index' );
	}
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError() {
		$this->layout = 'mainError';
		if ($error = Yii::app ()->errorHandler->error) {
			if (Yii::app ()->request->isAjaxRequest)
				echo $error ['message'];
			else
				$this->render ( 'error', $error );
		}
	}
	
	/**
	 * Displays the contact page
	 */
	public function actionContact() {
		$model = new ContactForm ();
		if (isset ( $_POST ['ContactForm'] )) {
			$model->attributes = $_POST ['ContactForm'];
			if ($model->validate ()) {
				$name = '=?UTF-8?B?' . base64_encode ( $model->name ) . '?=';
				$subject = '=?UTF-8?B?' . base64_encode ( $model->subject ) . '?=';
				$headers = "From: $name <{$model->email}>\r\n" . "Reply-To: {$model->email}\r\n" . "MIME-Version: 1.0\r\n" . "Content-Type: text/plain; charset=UTF-8";
				
				mail ( Yii::app ()->params ['adminEmail'], $subject, $model->body, $headers );
				Yii::app ()->user->setFlash ( 'contact', 'Thank you for contacting us. We will respond to you as soon as possible.' );
				$this->refresh ();
			}
		}
		$this->render ( 'contact', array (
				'model' => $model 
		) );
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin($t = null) {

		if(false){
 			$this->layout = 'mainLogin';
 			$this->render('//contests/concursoFinalizado');
 			return;
 		}

		$this->layout = 'mainLogin';
		// Verifica que exita el concurso
		$concurso = $this->verificarToken ( $t );
		
		$model = new LoginForm ();
		
		// if it is ajax validation request
		if (isset ( $_POST ['ajax'] ) && $_POST ['ajax'] === 'login-form') {
			echo CActiveForm::validate ( $model );
			Yii::app ()->end ();
		}
		
		// collect user input data
		if (isset ( $_POST ['LoginForm'] )) {
			$model->attributes = $_POST ['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if ($model->validate () && $model->login ()) {
				// Sesión con los datos del concurso
				$this->crearSesionUsuarioConcurso ( Yii::app ()->user->concursante->id_usuario, $concurso );
				$this->redirect ( array (
						"usrUsuarios/concurso" 
				) );
			}
		}
		// display the login form
		$this->render ( 'login', array (
				'model' => $model,
				'concurso' => $concurso 
		) );
	}
	
	// Verifica que exista el concurso
	public function verificarToken($t) {
		// Busqueda de concurso en la base de datos
		$concurso = ConContests::buscarPorToken ( $t );
		
		// Si no existe manda un error al usuario
		if ($concurso == null) {
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		
		return $concurso;
	}
	
	/**
	 * Crea sesion para el usuario
	 *
	 * @param unknown $idCompetidor        	
	 * @param unknown $idConcurso        	
	 */
	public function crearSesionUsuarioConcurso($idCompetidor, $concurso) {
		$identificacorUnico = $this->crearIdentificadorSesion ( $idCompetidor, $concurso->id_contest );
		$isUsuarioInscrito = ConRelUsersContest::isUsuarioInscrito ( $idCompetidor, $concurso->id_contest );
		
		// Sesión con los datos del concurso
		Yii::app ()->user->setState ( $identificacorUnico, $concurso );
		Yii::app ()->user->setState ( "concurso", $concurso->id_contest );
		Yii::app ()->user->setState ( "competidorInscrito", $isUsuarioInscrito );
	}
	
	/**
	 * Crea un identificador sesion
	 *
	 * @param unknown $idCompetidor        	
	 * @param unknown $idConcurso        	
	 * @return string
	 */
	public function crearIdentificadorSesion($idCompetidor, $idConcurso) {
		return $identificador = md5 ( "sesion-" . $idCompetidor . "-" . $idConcurso );
	}
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout() {
		Yii::app ()->user->logout ();
		$this->redirect ( Yii::app ()->homeUrl );
	}
	
	/**
	 * Action Pago
	 */
	public function actionPago() {
		$this->layout = 'mainScroll';
		$this->render ( 'pago' );
	}
	
	/**
	 * Action Toast
	 */
	public function actionToast() {
		// $this->layout='mainScroll';

		$this->render ( 'toast' );
	}
	
	/**
	 * Recuperar contraseña mediante un correo electronico
	 */
	public function actionRequestPassword($t = null) {
		$this->layout = 'mainLogin';
		// Verifica que exita el concurso
		$concurso = $this->verificarToken ( $t );
		
		// Iniciamos el modelo
		$model = new LoginForm ();
		
		if (isset ( $_POST ['LoginForm'] )) {
			$model->attributes = $_POST ['LoginForm'];
			
			// Busca el la base de datos por su email
			$usuario = UsrUsuarios::model ()->find ( array (
					"condition" => "txt_correo=:email",
					"params" => array (
							":email" => $model->username 
					) 
			) );
			
			// Si no encuentra el correo electronico mandamos un error
			if (empty ( $usuario )) {
				$model->addError ( "username", Yii::t('formRecoveryPass', 'messageError') );
				// Si se encuentra el usuario
			} else {
				// Se genera un token para que el usuario pueda ser identificado y cambiar su password
				$recuperarPass = new UsrUsuariosRecuperarPasswords ();
				$isSaved = $recuperarPass->saveRecoveryPass ( $usuario->id_usuario );
				
				if ($isSaved) {
					// Preparamos los datos para enviar el correo
					$view = "_recoveryPassword";
					$data ["hash"] = $recuperarPass;
					$data ["usuario"] = $usuario;
					$data["t"]=$t;
					
					// Envia correo electronico
					
					$this->sendEmail ( Yii::t('recoveryPassword','subject'), $view, $data, $usuario );
					Yii::app ()->user->setFlash ( 'success', Yii::t('formRecoveryPass', 'successMessageSendPass') );
				} else {
					
				}
			}
		}
		$this->render ( "formRecoveryPass", array (
				"model" => $model,
				"concurso" => $concurso 
		) );
	}
	
	
	/**
	 * Action para cambiar password del usuario
	 */
	public function actionResetPassword($hide = null, $t=null) {
		
		// Verifica que exita el concurso
		$concurso = $this->verificarToken ( $t );
		
		$this->layout = "mainLogin";
		if (! empty ( $hide )) {
			$recovery = new UsrUsuariosRecuperarPasswords();
			$recuperar = $recovery->searchMd5 ( $hide );
				
			if (! empty ( $recuperar )) {
	
				$usuario = $recuperar->idUsuario;
				$usuario->scenario = "recovery";
				$usuario->txt_password = NULL;
				
				if (isset ( $_POST ["UsrUsuarios"] )) {
						
					$usuario->attributes = $_POST ["UsrUsuarios"];
					$tx = Yii::app ()->db->beginTransaction ();
					if ($usuario->save ()) {
	
						$recuperar->b_usado = 1;
						if ($recuperar->save ()) {

							$activacion = ActivarUsuario::model()->find(array(
								'condition' => 'id_usuario=:idUsuario',
								'params' => array(
									':idUsuario' => $usuario->id_usuario
								)
							));
							
							$fecha_actual=date("Y-m-d H:m:s");
							$activacion->fch_activacion = $fecha_actual;
							$activacion->save();	

								
							$tx->commit ();
							Yii::app()->user->setState("complete", Yii::t('resetPassword', 'successMessage'));
							if(empty($t)){
								$this->redirect("login", array("t"=>$t));
							}
							$this->redirect ( Yii::app ()->homeUrl );
								
						} else {
							Yii::app ()->user->setFlash ( 'error', Yii::t('resetPassword', 'errorMessage') );
						}
							$tx->rollback ();
						}
					}
				
					$this->render ( "resetPassword", array (
							"model" => $usuario,
							"t"=>$t,
							'concurso'=>$concurso
					) );
				
	
// 				Yii::app ()->user->setState ( "recoveryForm", $usuario );
// 				$this->redirect ( "index" );
			} else {
				Yii::app ()->user->setFlash ( 'error', Yii::t('resetPassword', 'errorMessageRequest') );
				$this->redirect ( "requestPassword/t/".$t );
// 				echo "1";
// 				return;
// 				Yii::app ()->user->setFlash ( $type, "Ha expirado" );
// 				$this->redirect ( "recoveryPassword" );
			}
		} else {
			throw new CHttpException ( 404, 'The requested page does not exist.' );
// 			echo "2";
// 			return;
// 			Yii::app ()->user->setFlash ( $type, $message );
// 			$this->redirect ( "recoveryPassword" );
		}
	}
	
	/**
	 * Envia correo
	 *
	 * @param unknown $view        	
	 * @param unknown $data        	
	 * @param unknown $usuario        	
	 */
	public function sendEmail($asunto, $view, $data, $usuario) {
		$template = $this->generateTemplateRecoveryPass ( $view, $data );
		$sendEmail = new SendEMail ();
		$sendEmail->SendMailPass ( $asunto, $usuario->txt_correo, $usuario->txt_nombre . " " . $usuario->txt_apellido_paterno, $template );
	}
	
	/**
	 * Generamos template con la informacion necesaria
	 */
	public function generateTemplateRecoveryPass($view, $data) {
		
		// Render view and get content
		// Notice the last argument being `true` on render()
		$content = $this->renderPartial ( $view, array (
				'data' => $data 
		), true );
		
		return $content;
	}


	/**
	 * Action
	 */
	public function actionTest() {
		$error = Yii::app()->errorHandler->error;
                switch($error['code'])
                {
                        case 500:

                                $this->render('error', array('error' => $error));
                                break;
                }
	}


	/**
	 * This is the default 'Concurso Finalizado' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionConcursoFinalizado()
	{
		$this->layout = 'mainLogin';
		$this->render('//contests/concursoFinalizado');
	}
	

	public function actionTest2(){
		echo Yii::app()->language();
		exit;
	}

	public function actionActivateAccount($token = null){
		//Buscar token en tabla activar_usuario
		$activacion = ActivarUsuario::model()->find(array(
			'condition' => 'txt_token=:idToken',
			'params' => array(
				':idToken' => $token
			)
		));
		// Buscamos el concurso
		$concurso = ConContests::model()->find(array(
			'condition' => 'id_contest=:idCon',
			'params' => array(
				':idCon' => $activacion->id_contest
			)
		));

		$user = UsrUsuarios::model()->find(array(
			'condition' => 'id_usuario=:idUser',
			'params' => array(
				":idUser" => $activacion->id_usuario,
			)
		));
		$fecha_actual=date("Y-m-d H:m:s");
		$activacion->fch_activacion = $fecha_actual;
		$activacion->save();

		if($user && $concurso){
			$this->loginCompetidor( $user, $concurso );
		}else{
			exit;
		}
	}

	/**
	 * Loguea al usuario despues de registrarse
	 *
	 * @param UsrUsuarios $competidor        	
	 */
	private function loginCompetidor($competidor, $concurso) {
		$model = new LoginForm ();
		$model->username = $competidor->txt_correo;
		$model->password = $competidor->txt_password;
		// validate user input and redirect to the previous page if valid
		if ($model->validate () && $model->login ()) {
			$this->crearSesionUsuarioConcurso ( $competidor->id_usuario, $concurso );
			$this->redirect ( array('usrUsuarios/concurso') );
			return;
		}else{
			
			
		}
		
		exit;
	}

	/**
	 * Valida el token enviado
	 *
	 * @param unknown $token        	
	 * @throws CHttpException
	 */
	public function validarToken($token) {
		
		// Buscamos el concurso mediante el token
		$concurso = ConContests::buscarPorToken ( $token );
		// Si no existe el concurso le mandamos error
		if (empty ( $concurso )) {
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		
		return $concurso;
	}

	public function actionReenviarActivacion($t = null){
		$this->layout = 'mainLogin';
		// Verifica que exita el concurso
		$concurso = $this->verificarToken ( $t );
		
		// Iniciamos el modelo
		$model = new LoginForm ();
		
		if (isset ( $_POST ['LoginForm'] )) {
			$model->attributes = $_POST ['LoginForm'];
			
			// Busca el la base de datos por su email
			$usuario = UsrUsuarios::model ()->find ( array (
					"condition" => "txt_correo=:email",
					"params" => array (
							":email" => $model->username 
					) 
			) );
			// Si no encuentra el correo electronico mandamos un error
			if (empty ( $usuario )) {
				$model->addError ( "username", Yii::t('formRecoveryPass', 'messageError') );
				// Si se encuentra el usuario
			} else {
				// Se genera un token para que el usuario pueda ser identificado y cambiar su password
				/*$recuperarPass = new UsrUsuariosRecuperarPasswords ();
				$isSaved = $recuperarPass->saveRecoveryPass ( $usuario->id_usuario );*/
				$activacion = ActivarUsuario::model()->find(array(
					'condition' => 'id_usuario=:idUsuario',
					'params' => array(
						':idUsuario' => $usuario->id_usuario
					)
				));
			
				if ($activacion) {
					// Preparamos los datos para enviar el correo
					$view = "../usrUsuarios/_activacionEmail";
					$data ["token"] = $activacion->txt_token;
					$data ['nombreCompetidor'] = $usuario->txt_nombre." ".$usuario->txt_apellido_paterno;
					// Envia correo electronico
					$this->sendEmail( Yii::t('general', 'titleSendEmailActivation'), $view, $data, $usuario );
					Yii::app ()->user->setFlash ( 'success', Yii::t('general', 'sendEmailActivationAgain') );
				} else {
					
				}
			}
		}
		$this->render ( "formReenviarAct", array (
				"model" => $model,
				"concurso" => $concurso 
		) );
	}
}