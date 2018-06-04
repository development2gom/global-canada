<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
	private $_id;
	
	public function authenticate() {
		$error = 1;
		$usuario = UsrUsuarios::model ()->find ( array (
				'condition' =>"txt_correo=:txtUserName",'params'=>array(':txtUserName'=> $this->username), 
		) ); // here I use Email as user name which comes from database
		
		$activacion = null;
		if($usuario){
			$activacion = ActivarUsuario::model()->find(array(
				'condition' => 'id_usuario=:idUser',
				'params' => array(
					':idUser' => $usuario->id_usuario
				)
			));
		}
		
		if ($usuario === null) {
			$this->_id = 'user Null';
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} else if ($usuario->txt_password !== $this->password) 		// here I compare db password with passwod field
		{
			$this->_id = $this->username;
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		} 		// else if ($record ['E_STATUS'] !== 'Active') // here I check status as Active in db
		// {
		// $err = "You have been Inactive by Admin.";
		// $this->errorCode = $err;
		// }
		else if(!$activacion->fch_activacion){
			$this->_id = $this->username;
			$this->errorCode = "Cuenta no se ha activado";
			$error = 2;
		}else {
			
			$this->_id=$usuario->id_usuario;
			
			Yii::app ()->user->setState ( "concursante", $usuario);
			//Yii::app ()->user->setState ( "roles", $usuario->b_juez_admin);
			
			$this->errorCode = self::ERROR_NONE;

			$error = 0;
		}
		
		//return ! $this->errorCode;
		return $error;
	}
	
	public function authenticateFacebook($usuario){
	
		$this->_id = $usuario->id_usuario;
	
		
		Yii::app ()->user->setState ( "concursante", $usuario );
		
	
		$this->errorCode = self::ERROR_NONE;
	
	}
	
	
	public function getId() 	// override Id
	{
		return $this->_id;
	}
}