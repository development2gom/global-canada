<?php
class UsrUsuariosController extends Controller {
	/**
	 *
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 *      using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout = '//layouts/column2';
	
	/**
	 *
	 * @return array action filters
	 */
	public function filters() {
		return array (
				'accessControl', // perform access control for CRUD operations
				'postOnly + delete' 
		); // we only allow deletion via POST request
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 *
	 * @return array access control rules
	 */
	public function accessRules() {
		return array (
				array (
						'allow', // allow all users to perform 'index' and 'view' actions
						'actions' => array (
								'registrar',
								'iPNPayPal',
								'callbackFacebook'
						),
						'users' => array (
								'*' 
						) 
				),
				array (
						'allow', // allow authenticated user to perform 'create' and 'update' actions
						'actions' => array (
								'inscripcion',
								'fotosUsuario',
								'guardarFotosCompetencia',
								'concurso',
								'guardarInformacionPhoto',
								'guardarFotosCompetencia',
								'validateForm',
								'deletePhoto',
								'usurioParticipar',
								'reinscrip',
								'revisarPago',
								'revisarValidarPago',
								'necesitoAyuda',
								'sendReport',
								'checkOut',
								'test'
						),
						'users' => array (
								'@' 
						) 
				),
				array (
						'allow', // allow admin user to perform 'admin' and 'delete' actions
						'actions' => array (
								'admin',
								'delete' 
						),
						'users' => array (
								'admin' 
						) 
				),
				array (
						'deny', // deny all users
						'users' => array (
								'*' 
						) 
				) 
		);
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 *
	 * @param integer $id
	 *        	the ID of the model to be loaded
	 * @return UsrUsuarios the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id) {
		$model = UsrUsuarios::model ()->findByPk ( $id );
		if ($model === null)
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		return $model;
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
	
	/**
	 * Registrar usuario
	 */
	public function actionRegistrar($t = null) {

		$this->layout = 'mainLogin';
		$errorMessage = 'Mensaje de error';
		
		// Buscamos el concurso
		$concurso = $this->validarToken ( $t );
		
		// Inicializacion de modelos
		$competidor = new UsrUsuarios ();
		$competidor->scenario = "register";
		$datosWeb = new UsrUsuariosWebsites ();
		$datosTelefonos = new UsrUsuariosTelefonos ();
		
		// Verifica si se han enviado los datos
		if (isset ( $_POST ['UsrUsuarios'] ) && isset ( $_POST ['UsrUsuariosWebsites'] ) && isset ( $_POST ['UsrUsuariosTelefonos'] )) {
			
			// Asignamos los datos del formulario a sus respectivos modelos
			$competidor->attributes=$_POST ['UsrUsuarios'];
			$competidor->valorAdicional = $_POST['UsrUsuarios']['valorAdicional'];
			$datosWeb->attributes = $_POST ["UsrUsuariosWebsites"];
			$datosTelefonos->attributes = $_POST ["UsrUsuariosTelefonos"];
			
			$competidor->txt_usuario_number = "usr_" . md5 ( uniqid ( "usr_" ) ) . uniqid ();
			/**
			 *
			 * @todo Poner un dropdown con el pais adecuado por el momento todos son canada
			 *      
			 */
			$competidor->id_pais = 2;
			
			// Obtenemos el archivo enviado
			$competidor->nombreImagen = CUploadedFile::getInstance ( $competidor, 'nombreImagen' );
			$size = null;
			
			// Revisa que se haya subido un archivo
			if (! empty ( $competidor->nombreImagen )) {
				$raw_file_name = $competidor->nombreImagen->getTempName ();
				
				// Valida que sea una imagen
				$size = getimagesize ( $raw_file_name );
			}
			
			// Asignamos la validación de los modelos
			$competidorValido = $competidor->validate ();
			$datosWebValido = $datosWeb->validate ();
			$datosTefononosValido = $datosTelefonos->validate ();
			
			// echo $competidor->validaUsuarioExistente2();
			// echo $competidor->validarEmail();
			// exit;
			
			if ($competidor->validaUsuarioExistente2 ()) {
				$errorMessage = Yii::t('registrar', 'errorEmailExist');
			} else if (! $competidorValido || ! $datosWebValido) {
				
				$errorMessage = Yii::t('registrar', 'errorEmptyFields');
			} else if (! $datosTefononosValido) {
				$errorMessage = Yii::t('registrar', 'errorTelefono');
			} else if (! $competidor->validarPasswordLength ()) {
				$competidor->addError ( "txt_password",  Yii::t('registrar', 'errorPass'));
				$errorMessage =  Yii::t('registrar', 'errorPass');
				$competidorValido = false;
			} else if (! $competidor->validarEmail ()) {
				$errorMessage = Yii::t('registrar', 'errorEmail');
			} else if (! $competidor->validarPasswordLength ()) {
				$competidor->addError ( "txt_password", Yii::t('registrar', 'errorPass') );
				$errorMessage = Yii::t('registrar', 'errorPass');
				$competidorValido = false;
			} else if (! $competidor->validarPassword ()) {
				$errorMessage = Yii::t('registrar', 'errorPassEqual');
				$competidorValido = false;
				$competidor->validarRepetirPass ();
			}
			
			
			if (! empty ( $competidor->nombreImagen )) {
				// Nombre unico para la imagen
				$competidor->txt_image_url = $this->getNombreUnico () . "." . $competidor->nombreImagen->extensionName;
			}
			
			// Verifica que todos los modelos sean validos
			if ($competidorValido && $datosWebValido && $datosTefononosValido) {
				
				// Iniciamos transaccion a la base de datos
				$transaction = $competidor->dbConnection->beginTransaction ();
				try {
					
					// Guardar competidor
					if ($competidor->save ()) {
						
						// Guarda el valor adicional
						if (strlen ( $competidor->valorAdicional ) > 0) {
							$adicional = new UsrUsuariosInfos ();
							$adicional->id_usuario = $competidor->id_usuario;
							/**
							 *
							 * @todo crear la respectiva tabla
							 */
							$adicional->id_info_adicional = 1;
							
							$adicional->txt_valor = $competidor->valorAdicional;
							
							$adicional->save ();
						}
						// Asignamos el id del competidor al weby tel
						$datosWeb->id_usuario = $competidor->id_usuario;
						$datosTelefonos->id_usaurio = $competidor->id_usuario;
						
						if (! empty ( $datosWeb->txt_url )) {
							$datosTelefonos->save ();
						}
						
						// Guardar los datos del competidor (Telefono y web)
						if ($datosWeb->save ()) {
							
							// Guarda la imagen de perfil del usuario
							if (! empty ( $size )) {
								$this->guardarImagenCompetidor ( $competidor );
							}
							$transaction->commit ();

						} else {
							$transaction->rollback ();
						}
						//Guardar datos en tabla de activacion
						$activar = new ActivarUsuario();
						$activar->id_usuario = $competidor->id_usuario;
						$activar->id_contest = $concurso->id_contest;
						$activar->txt_token = "act_" . md5 ( uniqid ( "act_" ) ) . uniqid ();;
						
						if($activar->save()){
							// Preparamos los datos para enviar el correo
							$view = "_activacionEmail";
							$data ["token"] = $activar->txt_token;
							$data ['nombreCompetidor'] = $competidor->txt_nombre." ".$competidor->txt_apellido_paterno;
							//$this->loginCompetidor ( $competidor, $concurso );
						
							$this->sendEmail2 ( Yii::t('general', 'titleSendEmailActivation'), $view, $data, $competidor );
							Yii::app ()->user->setFlash ( 'success', Yii::t('general', 'sendEmailActivation')."<b><br>".$competidor->txt_correo."</b>" );

							$this->redirect ( array("site/login", 't'=>$concurso->txt_token) );

						} else {
							$transaction->rollback ();

						}
					}
					// Si existe un error realizamos un rollback
				} catch ( ErrorException $e ) {
					$transaction->rollback ();
				}
			}
		}
		
		
		// Vista a mostrar
		$this->render ( "registrar", array (
				"competidor" => $competidor,
				"datosWeb" => $datosWeb,
				"datosTelefonos" => $datosTelefonos,
				"t" => $t,
				"errorMessage" => $errorMessage 
		) );

		
	}

	public function sendEmail2($asunto, $view, $data, $usuario) {
		$template = $this->generateTemplateRecoveryPass ( $view, $data );
		$sendEmail = new SendEMail ();
		$sendEmail->SendMailPass ( $asunto, $usuario->txt_correo, $usuario->txt_nombre . " " . $usuario->txt_apellido_paterno, $template );
	}

	/*public function actionActivateAccount($token = null, $t = null){
		// Buscamos el concurso
		$concurso = $this->validarToken ( $t );
		$user = UsrUsuarios::model()->find(array(
			'condition' => 'txt_usuario_number=:txtToken',
			'params' => array(
				":txtToken" => $token,
			)
		));
		var_dump($user);
		var_dump($concurso);
		exit();
		if($user && $concurso){
			$this->loginCompetidor( $user, $concurso );
		}else{
			exit;
		}
	}*/
	
	/**
	 */
	public function actionConcurso() {
		$idConcurso = Yii::app ()->user->concurso;
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		
		$this->verificarUsuario($idUsuario, $idC);

		$isUsuarioInscrito = ConRelUsersContest::isUsuarioInscrito ( $idUsuario, $idConcurso );
		
		// if(Yii::app()->user->concursante->txt_correo="humberto@2gom.com.mx"){
		// $this->usuarioNoInscrito ();
		// }else{
		// $this->layout = 'mainRevisarPago';
		// $this->render("mantenimiento");
		
		// }
		
		// Si el usuario esta inscrito lo enviamos a ver sus fotografias
		if ($isUsuarioInscrito) {
			$this->usuarioInscrito ();
			// Si el usuario no esta inscrito
		} else {
			
			$this->usuarioNoInscrito ();
		}
	}

	public function verificarUsuario($idUsuario, $idConcurso){
		$rel = ConRelUsersContest::model()->find(array(
			"condition" => "id_usuario=:idUsuario AND id_contest=:idConcurso",
			"params" => array(
				":idUsuario" => $idUsuario,
				":idConcurso" => $idConcurso
			)
		));
					
		if($rel){
			Yii::app ()->user->concursante->b_participa = $rel->b_participa;
		}else{
			Yii::app ()->user->concursante->b_participa = 0;
		}
	}
	
	/**
	 * Usuario inscrito
	 */
	public function usuarioInscrito() {
		$idConcurso = Yii::app ()->user->concurso;
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		
		$concursoDatos = ConRelUsersContest::model ()->find ( array (
				"condition" => "id_usuario=:idUsuario AND id_contest=:idConcurso",
				"params" => array (
						":idUsuario" => $idUsuario,
						":idConcurso" => $idConcurso 
				) 
		) );
		
		// Revisamos si es la primera vez que el usuario entra al concurso
		if ($concursoDatos->b_primera_vez == 1) {
			$concursoDatos->b_primera_vez = 0;
			$concursoDatos->save ();
			Yii::app ()->user->setFlash ( 'success', Yii::t('general', 'successPayment') );
		}
		
		// Obtenemos el numero de fotos que compro el usuario
		$numberFotosCompradas = $concursoDatos->num_fotos_permitidas;
		
		// Buscamos las fotos del competidor
		$fotosCompetidor = WrkPics::model ()->findAll ( array(
				"condition" => "ID=:idUsuario AND id_contest=:idConcurso",
				"params" => array(
						":idUsuario" => $idUsuario,
						":idConcurso" => $idConcurso 
				) 
		) );
		
		$fotosCompetidor = count ( $fotosCompetidor );
		
		$fotosFaltantes = $numberFotosCompradas - $fotosCompetidor;
		
		// Guardar número de fotos para el usuario
		for($i = 0; $i < $fotosFaltantes; $i ++) {
			$photo = new WrkPics ();
			
			$photo->ID = $idUsuario;
			$photo->id_contest = $idConcurso;
			$photo->txt_pic_number = "pic_" . md5 ( uniqid ( "pic_" ) ) . uniqid ();
			$photo->save ();
		}
		
		// Obtenemos todas las categorias del concurso
		$categorias = ConCategoiries::model ()->findAll ( array (
				"condition" => "id_contest=:idConcurso",
				"params" => array (
						":idConcurso" => $idConcurso 
				),
				"order" => "txt_name_es" 
		) );
		$categorias = CHtml::listData ( $categorias, "id_category", "txt_name" );

		$rel = ConRelUsersContest::model()->find(array(
			'condition' => 'id_usuario=:idUs AND id_contest=:idCon',
			'params' => array(
				':idUs' => $idUsuario,
				':idCon' => $idConcurso
			)
		));
		
		// Muestra las fotos
		$this->render ( "fotosUpload", array (
				"categorias" => $categorias,
				"rel" =>$rel
		) );
	}
	
	/**
	 * Action que guarda la información de la foto
	 */
	public function actionGuardarInformacionPhoto() {
		$idConcurso = Yii::app ()->user->concurso;
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		$participa = Yii::app ()->user->concursante->b_participa;
		
		$isUsuarioInscrito = ConRelUsersContest::isUsuarioInscrito ( $idUsuario, $idConcurso );
		
		if (Yii::app ()->user->concursante->b_participa == 1) {
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		
		if ($isUsuarioInscrito && $participa == 0) {
			$pic = new WrkPics ();
			
			if (isset ( $_POST ["WrkPics"] )) {
				$pic->setAttributes ( $_POST ["WrkPics"], false );
				
				$pic = WrkPics::validarUsuarioFoto ( $idConcurso, $idUsuario, $pic->txt_pic_number );
				
				if (! empty ( $pic )) {
					
					$image = $pic->txt_file_name;
					
					$pic->attributes = $_POST ["WrkPics"];
					$pic->ID = $idUsuario;
					
					$pic->txt_file_name = $image;
					
					foreach ( $pic->getAttributes () as $attribute => $value ) {
						if (empty ( $pic->$attribute )) {
							$pic->$attribute = null;
						}
					}
					
					$pic->scenario = "complete";
					$valid = $pic->save ();
					
					if ($valid) {
						
						// do anything here
						echo CJSON::encode ( array (
								'status' => 'success' 
						) );
						Yii::app ()->end ();
					} else {
						$error = CActiveForm::validate ( $pic );
						if ($error != '[]')
							echo $error;
						Yii::app ()->end ();
					}
				} else {
					throw new CHttpException ( 404, 'The requested page does not exist.' );
				}
			}
		}
	}
	
	/**
	 * Usuario no inscrito
	 */
	public function usuarioNoInscrito() {
		$this->layout = 'mainScroll';
		$idConcurso = Yii::app ()->user->concurso;
		
		$concurso = ConContests::model()->findByPk($idConcurso);
		
		// Obtenemos los productos y los tipos de pagos para el concurso
		$productos = ConProducts::obtenerProductosPorConcurso ( $idConcurso );
		$tiposPagos = ConRelContestPayments::model ()->findAll ( array (
				"condition" => "id_contest=:idContest",
				"params" => array (
						":idContest" => $idConcurso 
				) 
		) );
		
		// Obtiene los terminos y condiciones del concurso
		$terminosCondiciones = ConTerminosCondiciones::model ()->find ( array (
				"condition" => "id_contest=:idContest AND b_Actual=1",
				
				"params" => array (
						":idContest" => $idConcurso 
				) 
		) );
		
		$this->render ( "inscripcion", array (
				"productos" => $productos,
				"tiposPagos" => $tiposPagos,
				"terminosCondiciones" => $terminosCondiciones, 
				"concurso"=>$concurso,
		) );
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
	 * Action inscripcion
	 */
	public function actionInscripcion($concurso = null) {
		$concursos = ConContests::model ()->findAll ();
		
		$this->render ( "inscripcion", array (
				"concursos" => $concursos 
		) );
	}
	
	/**
	 * Action para ver las fotos subidas del usuario al concurso
	 */
	public function actionFotosUsuario() {
		$this->revisarSesion ();
		
		$this->render ( "fotosUsuario" );
	}
	
	/**
	 * Revisa que la sesion sea valida si no lo desloguea
	 */
	public function revisarSesion() {
		$idConcurso = Yii::app ()->user->concurso;
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		
		$session = Yii::app ()->user->getState ( md5 ( "sesion-" . $idUsuario . "-" . $idConcurso ) );
		
		if (empty ( $session )) {
			
			Yii::app ()->user->logout ();
			$this->redirect ( Yii::app ()->homeUrl );
		}
	}
	
	/**
	 * Busca el concurso
	 *
	 * @param unknown $idConcurso        	
	 */
	private function searchConcurso($idConcurso) {
		$concurso = ConContests::model ()->findByPK ( $idConcurso );
		
		if (empty ( $concurso )) {
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		
		return $concurso;
	}
	
	/**
	 * Action para agregar fotos del usuario
	 */
	public function actionGuardarFotosCompetencia() {

		$sizePost = (int) $_SERVER['CONTENT_LENGTH'];

	

		if( ($sizePost/1048576) > 6){
			$respuesta ["status"] = "error";
			$respuesta ["message"] = Yii::t('formFotos', 'errorFileSize');	
			echo json_encode ( $respuesta );
			return;	
			exit;
		}


		$idConcurso = Yii::app ()->user->concurso;
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		$tokenUsuario = Yii::app ()->user->concursante->txt_usuario_number;
		$respuesta = array ();
		
		if (Yii::app ()->user->concursante->b_participa == 1) {
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		
		// Recupera el concurso
		$concurso = $this->searchConcurso ( $idConcurso );
		
		$isUsuarioInscrito = ConRelUsersContest::isUsuarioInscrito ( $idUsuario, $idConcurso );
		try {
			$pics = new WrkPics ();
			
			if (isset ( $_POST ["WrkPics"] )) {
				$pics->setAttributes ( $_POST ["WrkPics"], false );
				
				// Metodo que valida que la foto realmente pertenezca al usuario
				$pic = WrkPics::validarUsuarioFoto ( $idConcurso, $idUsuario, $pics->txt_pic_number );
				
				if (empty ( $pic )) {
					$respuesta ["status"] = "error";
					$respuesta ["message"] = Yii::t('formFotos', 'error500');
					echo json_encode ( $respuesta );
					
					return;
				}
				
				$pics->txt_file_name = CUploadedFile::getInstance ( $pics, 'txt_file_name' );		
				
				$raw_file_name = $pics->txt_file_name->getTempName ();
				
				// Valida que sea una imagen
				$size = getimagesize ( $raw_file_name );
				list ( $width, $height, $otro, $wh ) = getimagesize ( $raw_file_name );
				
				if (! is_array ( $size )) {
					$respuesta ["status"] = "error";
					$respuesta ["message"] = Yii::t('formFotos', 'errorFileWrong');
					echo json_encode ( $respuesta );
					return;
				}
				
				if (! array_key_exists ( "channels", $size )) {
					$respuesta ["status"] = "error";
					$respuesta ["message"] =  Yii::t('formFotos', 'errorFileWrong');
					echo json_encode ( $respuesta );
					return;
				}
				
				$bits = $size ['bits'];
				$channels = $size ['channels'];
				$mime = $size ['mime'];
				
				if ($bits > 6144) {
					$respuesta ["message"] = Yii::t('formFotos', 'errorFileSize');
				}
				
				// echo ("<br><br><br><br><br><br>w:" . $width . " H:" . $height . " wh:" . $wh . " b:" . $bits . " c:" . $channels . " m:" . $mime);
				
				if ($size == null || $size = 0 || empty ( $size )) {
					
					$respuesta ["status"] = "error";
					$respuesta ["message"] =  Yii::t('formFotos', 'errorFileWrong');
					
					echo json_encode ( $respuesta );
					return;
					
					// No es una imagen
					// $uploadedImageMessage = "<p class='dgom-ui-message-error'>Archivo incorrecto, intentalo de nuevo.</p>";
					$error = true;
				}
				
				if ($width > 4000 || $height > 4000) {
					$respuesta ["status"] = "error";
					$respuesta ["message"] =  Yii::t('formFotos', 'errorFilePx');
					
					echo json_encode ( $respuesta );
					return;
					// $uploadedImageMessage = "<p class='dgom-ui-message-error'>La foto no debe exeder 4,000 pixeles.</p>";
					$error = true;
				}
				
				if ($mime !== 'image/jpeg') {
					$respuesta ["status"] = "error";
					$respuesta ["message"] =  Yii::t('formFotos', 'errorFileType');
					
					echo json_encode ( $respuesta );
					return;
					// echo("MIME ERROR");
					// $uploadedImageMessage = "<p class='dgom-ui-message-error'>Tu archivo debe ser JPG.</p>";
					$error = true;
				}
				
				$dirBase = "pictures/contests/con_" . $concurso->txt_token . "/idu_" . $tokenUsuario;
				$iuf = uniqid ( "pic_" ) . ".jpg";
				
				// Elimina la foto anterior
				if ($this->hasPreviousImage ( $pic )) {
					$this->deleteImages ( $dirBase, "small_" . $pic->txt_file_name );
					$this->deleteImages ( $dirBase, "medium_" . $pic->txt_file_name );
					$this->deleteImages ( $dirBase, "large_" . $pic->txt_file_name );
					$this->deleteImages ( $dirBase, $pic->txt_file_name );
				}
				// Verificamos que exista el directorio si no es así lo crea
				$this->validarDirectorio ( $dirBase );
				
				// Guarda la imagen el el path
				$archivoGuardado = $pics->txt_file_name->saveAs ( $dirBase . "/" . $iuf );
				
				// Redimencionar small
				$nombreNuevo = $dirBase . "/small_" . $iuf;
				$this->rezisePicture ( $dirBase . "/" . $iuf, $width, $height, 400, $nombreNuevo );
				
				// Redimencionar medium
				$nombreNuevo = $dirBase . "/medium_" . $iuf;
				$this->rezisePicture ( $dirBase . "/" . $iuf, $width, $height, 800, $nombreNuevo );
				
				// Redimencionar large
				$nombreNuevo = $dirBase . "/large_" . $iuf;
				$this->rezisePicture ( $dirBase . "/" . $iuf, $width, $height, 1280, $nombreNuevo );
				
				// Guardamos la imagen
				$pic->txt_file_name = $iuf;
				
				$pic->save ();
				// print_r ( $pic->getErrors () );
				$respuesta ["status"] = "success";
				$respuesta ["message"] = Yii::t('formFotos', 'successMessage');
				$respuesta ["urlSmall"] = Yii::app ()->request->baseUrl . "/" . $dirBase . "/small_" . $pic->txt_file_name;
				$respuesta ["urlLarge"] = Yii::app ()->request->baseUrl . "/" . $dirBase . "/large_" . $pic->txt_file_name;
				
				echo json_encode ( $respuesta );
				return;
			}
		} catch ( ErrorException $e ) {
			// echo $e;
			throw new CHttpException ( 500, Yii::t('formFotos', 'error500') );
		}
	}
	
	/**
	 * Elimina la foto del servidor
	 */
	private function deleteImages($path, $name) {
		if (file_exists ( $path . "/" . $name )) {
			unlink ( $path . "/" . $name );
		}
	}
	
	/**
	 * Verifica que exita una foto
	 */
	private function hasPreviousImage($pic) {
		if (isset ( $pic->txt_file_name )) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Metodo para cambiar el tamaño de una imagen
	 *
	 * @param unknown $file        	
	 * @param unknown $ancho        	
	 * @param unknown $alto        	
	 * @param unknown $nuevo_ancho        	
	 * @param unknown $nuevo_alto        	
	 */
	private function rezisePicture($file, $ancho, $alto, $redimencionar, $nombreNuevo) {
		// Factor para el redimensionamiento
		$factor = $this->calcularFactor ( $ancho, $alto, $redimencionar );
		
		$nuevo_ancho = $ancho * $factor;
		$nuevo_alto = $alto * $factor;
		
		// Cargar
		$thumb = imagecreatetruecolor ( $nuevo_ancho, $nuevo_alto );
		$origen = imagecreatefromjpeg ( $file );
		// Cambiar el tamaño
		imagecopyresampled ( $thumb, $origen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto );
		imagejpeg ( $thumb, $nombreNuevo );
	}
	
	/**
	 * Calcula el factor
	 *
	 * @param unknown $ancho        	
	 * @param unknown $alto        	
	 * @param unknown $redimension        	
	 */
	private function calcularFactor($ancho, $alto, $redimension) {
		if ($ancho >= $alto) {
			$factor = $redimension / $ancho;
		} else if ($ancho <= $alto) {
			$factor = $redimension / $alto;
		}
		
		return $factor;
	}
	
	/**
	 * IPN para payl pal
	 */
	public function actionIPNPayPal() {
		$payPal = new IPNPayPal ();
		$payPal->payPalIPN ();
	}
	
	/**
	 * Devuelve un nombre unico
	 */
	private function getNombreUnico() {
		$nombreUnico = md5 ( uniqid ( "dgom" ) );
		return $nombreUnico;
	}
	
	/**
	 * Guarda la imagen que subio el competidor (Perfil)
	 *
	 * @param UsrUsuarios $competidor        	
	 */
	private function guardarImagenCompetidor($competidor) {
		// Path base donde se encuentran las imagenes de perfil
		$dirBase = Yii::app ()->params ['pathBaseImagenes'] . "profiles/" . $competidor->txt_usuario_number . "/";
		
		// Verificamos que exista el directorio si no es así lo crea
		$this->validarDirectorio ( $dirBase );
		
		// Guarda la imagen el el path
		$archivoGuardado = $competidor->nombreImagen->saveAs ( $dirBase . $competidor->txt_image_url );
		
		return $archivoGuardado;
	}
	
	/**
	 * Valida si existe el directorio si no lo crea
	 *
	 * @param String $file        	
	 */
	private function validarDirectorio($dir) {
		if (! file_exists ( $dir )) {
			mkdir ( $dir, 0777, true );
		}
	}
	
	/**
	 * Envia correo
	 *
	 * @param unknown $view        	
	 * @param unknown $data        	
	 * @param unknown $usuario        	
	 */
	public function sendEmail($view, $data, $usuario) {
		$template = $this->generateTemplateRecoveryPass ( $view, $data );
		$sendEmail = new SendEMail ();
		$sendEmail->SendMailPass ( Mensajes::TITLE_EMAIL_RECOVERY, $usuario->txt_correo, $usuario->txt_usuario, $template );
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
	 * Valida el formulario de la foto
	 */
	public function actionValidateForm() {
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		$model = new WrkPics ();
		$model->ID = $idUsuario;
		
		if (isset ( $_POST ['WrkPics'] )) {
			$model->setAttributes ( $_POST ['WrkPics'], false );
			
			$model = WrkPics::model ()->find ( array (
					"condition" => "txt_pic_number=:id",
					"params" => array (
							":id" => $model->txt_pic_number 
					) 
			) );
			// $this->performAjaxValidation ( $model );
			
			if (empty ( $model )) {
				throw new CHttpException ( 404, 'The requested page does not exist.' );
			}
			$model->scenario = "complete";
			$valid = $model->validate ();
			
			if ($valid) {
				
				// do anything here
				echo CJSON::encode ( array (
						'status' => 'success' 
				) );
				Yii::app ()->end ();
			} else {
				$error = CActiveForm::validate ( $model );
				if ($error != '[]')
					echo $error;
				Yii::app ()->end ();
			}
		}
	}
	
	/**
	 * Performs the AJAX validation.
	 *
	 * @param Candidatos $model
	 *        	the model to be validated
	 */
	protected function performAjaxValidation($model) {
		if (isset ( $_POST ['ajax'] )) {
			
			echo CActiveForm::validate ( $model );
			Yii::app ()->end ();
		}
	}
	
	/**
	 * Callback con la respuesta de facebook
	 */
	public function actionCallbackFacebook($t = null) {
		
		
	// Buscamos el concurso
		$concurso = $this->validarToken ( $t );
		
		Yii::log ( "\n\r En callback de facebook", "debug", 'facebook' );
		$fb = new Facebook ();
		
		// Obtenemos la respuesta de facebook
		$usuario = $fb->recoveryDataUserJavaScript ();
		if (gettype ( $usuario ) == "string") {
			if ($usuario == "error") {
				Yii::app ()->user->setFlash ( "error", "Se perdio la comunicación con Facebook. Vuelva a intentarlo" );
				$this->redirect ( Yii::app ()->homeUrl );
			}
		}
		
		if (empty ( $usuario )) {
			Yii::log ( "\n\r Regreso vacio", "debug", 'facebook' );
			Yii::app ()->user->setFlash ( "error", "Facebook rechazo la solicitud." );
			// Mandar error
			$this->redirect ( array (
					"site/login" 
			) );
		} else {
			
			Yii::log ( "\n\rDatos de facebook" . print_r ( $usuario ), "debug", 'facebook' );
			
			$entUsuario = new UsrUsuarios ();
			$entUsuario->id_usuario_facebook = $usuario ['profile'] ['id'];
			
			if (isset ( $usuario ['profile'] ['email'] )) {
				$entUsuario->txt_correo = $usuario ['profile'] ['email'];
			} else {
				// $entUsuario->txt_correo = str_replace ( " ", "_", $usuario ['profile'] ['name'] . uniqid () );
				Yii::app ()->user->setFlash ( "info", "Escribir correo electronico." );
				$this->render ( "ingresarCorreo" );
			}
			
			$usuarioDB = $entUsuario->searchUsuarioIdFacebook ();
			$login = new LoginForm ();
			
// 			print_r($usuario);
// 			exit;
			
			if (empty ( $usuarioDB )) {
				
				// Guarda la informacion de facebook
				$entUsuario->b_login_social_network = 1;
				$entUsuario->id_usuario_facebook = $usuario ['profile'] ['id'];
				$entUsuario->txt_nombre = $usuario ['profile'] ['first_name'];
				$entUsuario->txt_apellido_paterno = $usuario ['profile'] ['last_name'];
				$entUsuario->txt_password = NULL;
				$entUsuario->txt_image_url = $usuario ['pictureUrl'];
				$entUsuario->txt_usuario_number = "usr_" . md5 ( uniqid ( "usr_" ) ) . uniqid ();
				
				// Guarda al usuario
				if ($entUsuario->save ()) {
					Yii::log ( "\n\r Se crea un nuevo usuario", "debug", 'facebook' );
					
					// Loguea al usuario
					$login->loginFacebook ( $entUsuario );
					
					// Crea sesiones
					$this->crearSesionUsuarioConcurso ( Yii::app ()->user->concursante->id_usuario, $concurso );
				} else {
					Yii::app ()->user->setFlash ( "error", "No se pudieron guardar los datos." );
					Yii::log ( "\n\r No se pudo guardar el usuario" . $this->getErrors ( $entUsuario->getErrors () ), "debug", 'facebook' );
					// no se pudo guardar
					
					$this->redirect ( array (
							"usrUsuarios/concurso" 
					) );
				}
			} else {
				$usuarioDB->id_usuario_facebook = $entUsuario->id_usuario_facebook;
				$usuarioDB->txt_nombre = $usuario ['profile'] ['first_name'];
				$usuarioDB->txt_apellido_paterno = $usuario ['profile'] ['last_name'];
				$usuarioDB->txt_image_url = $usuario ['pictureUrl'];
				// $usuarioDB->scenario = "register";
				if ($usuarioDB->save ()) {
					Yii::log ( "\n\r Se edita al usuario por face", "debug", 'facebook' );
				} else {
					Yii::log ( "\n\r Ocurrio un error al actualizar el usuario" . $this->getErrors ( $usuarioDB->getErrors () ), "debug", 'facebook' );
				}
				$login->loginFacebook ( $usuarioDB );
				
				// Crea sesiones
				$this->crearSesionUsuarioConcurso ( Yii::app ()->user->concursante->id_usuario, $concurso );
			}
			Yii::log ( "\n\r Redirecciona al index", "debug", 'facebook' );
			$this->redirect ( array (
					"usrUsuarios/concurso" 
			) );
		}
	}
	
	/**
	 * Desenglosa los errores
	 *
	 * @param unknown $errores        	
	 */
	public function getErrors($errores) {
		$erroresS = "";
		foreach ( $errores as $key => $error ) {
			
			foreach ( $error as $e ) {
				
				$erroresS .= $e . " ";
			}
		}
		
		return $erroresS;
	}
	
	/**
	 * Elimina la imagen
	 *
	 * @param unknown $id        	
	 */
	public function actionDeletePhoto($id) {
		$idConcurso = Yii::app ()->user->concurso;
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		$tokenUsuario = Yii::app ()->user->concursante->txt_usuario_number;
		
		// Recupera el concurso
		$concurso = $this->searchConcurso ( $idConcurso );
		
		$isUsuarioInscrito = ConRelUsersContest::isUsuarioInscrito ( $idUsuario, $idConcurso );
		
		if (Yii::app ()->user->concursante->b_participa == 1) {
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		
		if ($isUsuarioInscrito) {
			$pic = WrkPics::model ()->find ( array (
					"condition" => "txt_pic_number=:pic AND ID=:idUsuario",
					"params" => array (
							":pic" => $id,
							":idUsuario" => $idUsuario 
					) 
			) );
			
			if (empty ( $pic )) {
				throw new CHttpException ( 404, 'The requested page does not exist.' );
			}
			
			$pic->id_category_original = null;
			$pic->txt_file_name = null;
			$pic->txt_pic_name = null;
			$pic->txt_pic_desc = null;
			
			if ($pic->save ()) {
				$dirBase = "pictures/contests/con_" . $concurso->txt_token . "/idu_" . $tokenUsuario;
				$iuf = uniqid ( "pic_" ) . ".jpg";
				
				// Elimina la foto anterior
				if ($this->hasPreviousImage ( $pic )) {
					$this->deleteImages ( $dirBase, "small_" . $pic->txt_file_name );
					$this->deleteImages ( $dirBase, "medium_" . $pic->txt_file_name );
					$this->deleteImages ( $dirBase, "large_" . $pic->txt_file_name );
					$this->deleteImages ( $dirBase, $pic->txt_file_name );
				}
			}
		}
	}
	
	/**
	 * Usuario se encuentra participando
	 */
	public function usuarioParticipa() {
	}
	
	/**
	 * Action para cuando el usuario decide participar al concurso
	 */
	public function actionUsurioParticipar() {
		$idConcurso = Yii::app ()->user->concurso;
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		$tokenUsuario = Yii::app ()->user->concursante->txt_usuario_number;
		
		// Recupera el concurso
		$concurso = $this->searchConcurso ( $idConcurso );

		$rel = ConRelUsersContest::model()->find(array(
			'condition' => 'id_usuario=:idUs AND id_contest=:idCon',
			'params' => array(
				':idUs' => $idUsuario,
				':idCon' => $idConcurso
			)
		));
		if(!$rel){
			return false;
		}
		
		
		$isUsuarioInscrito = ConRelUsersContest::isUsuarioInscrito ( $idUsuario, $idConcurso );
		
		if ($isUsuarioInscrito) {
			$usuario = UsrUsuarios::model ()->find ( array (
					"condition" => "id_usuario=:idUsuario",
					"params" => array (
							":idUsuario" => $idUsuario 
					) 
			) );
			
			if (empty ( $usuario )) {
				throw new CHttpException ( 404, 'The requested page does not exist.' );
			}
			
			// Buscamos las fotos del competidor
			$fotosCompetidor = WrkPics::model ()->findAll ( array (
					"condition" => "ID=:idUsuario AND id_contest=:idConcurso",
					"params" => array (
							":idUsuario" => $idUsuario,
							":idConcurso" => $idConcurso 
					) 
			) );
			
			foreach ( $fotosCompetidor as $foto ) {
				$foto->scenario = "complete";
				
				if ($foto->validate ()) {
					
					$foto->b_status = 2;
				} else {
					
					$foto->b_status = 3;
				}
				
				$foto->scenario = "";
				$foto->save ();
			}
			
			$rel->b_participa = 1;
			$rel->save();
			
			$usuario->b_participa = true;
			
			Yii::app ()->user->setState ( "concursante", $usuario );
Yii::app()->user->concursante->b_participa = 1;

			
		}
	}
	
	/**
	 * Action Pago
	 */
	// public function actionReinscrip() {
	// $this->layout = 'mainScroll';
	// $this->render ( 'reinscrip' );
	// }
	public function actionReinscrip() {
		$this->layout = 'mainScroll';
		$idConcurso = Yii::app ()->user->concurso;
		
		// Obtenemos los productos y los tipos de pagos para el concurso
		$productos = ConProducts::obtenerProductosPorConcurso ( $idConcurso );
		$tiposPagos = ConRelContestPayments::model ()->findAll ( array (
				"condition" => "id_contest=:idContest",
				"params" => array (
						":idContest" => $idConcurso 
				) 
		) );
		
		// Obtiene los terminos y condiciones del concurso
		$terminosCondiciones = ConTerminosCondiciones::model ()->find ( array (
				"condition" => "id_contest=:idContest AND b_Actual=1",
				
				"params" => array (
						":idContest" => $idConcurso 
				) 
		) );
		
		$this->render ( "reinscrip", array (
				"productos" => $productos,
				"tiposPagos" => $tiposPagos,
				"terminosCondiciones" => $terminosCondiciones 
		) );
	}
	
	/**
	 * Action Pago
	 */
	// public function actionReinscrip() {
	// $this->layout = 'mainScroll';
	// $this->render ( 'reinscrip' );
	// }
	public function actionRevisarPago() {
		$this->layout = 'mainRevisarPago';
		
		$idConcurso = Yii::app ()->user->concurso;
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		
		// Recupera el concurso
		$concurso = $this->searchConcurso ( $idConcurso );
		
		$isUsuarioInscrito = ConRelUsersContest::isUsuarioInscrito ( $idUsuario, $idConcurso );
		
		if ($isUsuarioInscrito) {
			$this->redirect ( "concurso" );
		} else {
			$this->render ( "revisarPago" );
		}
	}
	
	/**
	 * Revisa que el pago ya se encuentre en la base de datos
	 */
	public function actionRevisarValidarPago() {
		$idConcurso = Yii::app ()->user->concurso;
		$idUsuario = Yii::app ()->user->concursante->id_usuario;
		
		// Recupera el concurso
		$concurso = $this->searchConcurso ( $idConcurso );
		
		$isUsuarioInscrito = ConRelUsersContest::isUsuarioInscrito ( $idUsuario, $idConcurso );
		
		if ($isUsuarioInscrito) {
			echo "success";
		} else {
			echo "wait";
		}
	}
	
	/**
	 * Envia reporte via email del problema que se presenta al usuario
	 */
	public function actionSendReport() {
		$this->layout = false;
		$concursante = Yii::app ()->user->concursante;
		
		if (isset ( $_POST ["txt_tipo_incidencia"] ) && isset ( $_POST ["txt_descripcion"] )) {
			
			$data = array (
					"reporte" => $_POST,
					"concursante" => $concursante 
			);
			
			// $this->sendEmailReporte("Reporte de usuario","reporteUsuario", $data, "soporte@comitefotomx.com", "Centro de soporte");
			$this->sendEmailReporte ( "Reporte de usuario", "reporteUsuario", $data, "humberto@2gom.com.mx", "Centro de soporte" );
		} else {
			
			$this->render ( "_formReporte" );
		}
	}
	
	/**
	 * Envia correo
	 *
	 * @param unknown $view        	
	 * @param unknown $data        	
	 * @param unknown $usuario        	
	 */
	public function sendEmailReporte($asunto, $view, $data, $email, $usuario) {
		$template = $this->generateTemplateRecoveryPass ( $view, $data );
		$sendEmail = new SendEMail ();
		$sendEmail->sendMailSoporte ( $asunto, $email, $usuario, $template );
	}
	public function actionTest() {
		$dirBase = "pictures/";
		
		// Redimencionar large
		$nombreNuevo = $dirBase . "/large_e066c319e1ceb745d0818944340ad2bc.JPG";
		$this->rezisePicture ( $dirBase . "/e066c319e1ceb745d0818944340ad2bc.JPG", 5184, 3456, 400, $nombreNuevo );
	}
	
	/**
	 * Checkout
	 */
	public function actionCheckOut($t=null) {
		$this->layout = 'mainScroll';
		
		$idContest = Yii::app ()->user->concurso;
		$oc = PayOrdenesCompras::getOrdenCompraByToken ( $t, $idContest );
		
		if (empty ( $oc )) {
			$this->redirect ( 'concurso' );
			return;
			throw new CHttpException ( 404, 'The requested page does not exist.' );
		}
		
		
		// Tipos de pagos
		$tiposPagos = ConRelContestPayments::model ()->findAll ( array (
				"condition" => "id_contest=:idContest AND id_tipo_pago != 3",
				"params" => array (
						":idContest" => $idContest
				)
		) );
		
		if(!empty($oc->id_cupon)){
			$cupon = PayCupons::model()->findByPK($oc->id_cupon); 
		}else{
			$cupon = new PayCupons ();
		}
		$message = '';
		if (isset ( $_POST ['PayCupons'] )) {
			
		
			$cupon->attributes = $_POST ['PayCupons'];
			$cuponBaseDatos = PayCupons::getCupon ( $cupon->txt_identificador_unico, $idContest );
			
			if (empty ( $cuponBaseDatos )) {
				
				$oc->id_cupon = null;
				//$oc->num_total = $oc->num_sub_total * (0.13);
				$tax = number_format (($oc->num_sub_total * (0.13)),2);
				$oc->num_total =number_format (( $oc->num_sub_total+$tax),2);
				$oc->save();
				$cupon = new PayCupons ();
				$message = Yii::t('checkout', 'cuponNoExite');
			}else{
				//$message = 'Cupon existe';
				$cupon = $cuponBaseDatos;
				
				//$oc->num_sub_total = $oc->num_sub_total - (($cupon->num_porcentaje_descuento*$oc->num_sub_total)/100);
				
				$oc->id_cupon = $cupon->id_cupon;
				
				if($cupon->b_porcentaje==1){
					
					$subTotal = number_format (( ($oc->num_sub_total - (($cupon->num_porcentaje_descuento*$oc->num_sub_total)/100))),2);
						
					$tax = number_format (( $subTotal * (0.13)),2);
					$oc->num_total=$subTotal +$tax;
					
				}else{
					$subTotal = number_format ((((($oc->num_sub_total - $cupon->num_porcentaje_descuento)))),2) ;
					
					$tax = number_format (( $subTotal * (0.13)),2);
					$oc->num_total=$subTotal +$tax;
					
				}
				
				
				
				$oc->save();
				
			}
		}
		
		$concurso = ConContests::model()->findByPk($idContest);
		
		$this->render ( 'checkOut', array (
				'oc' => $oc,
				'cupon' => $cupon,
				'message' => $message,
				'tiposPagos' => $tiposPagos,
				'concurso'=>$concurso
		) );
		
		return;
	}
}
