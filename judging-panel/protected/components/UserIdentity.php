<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {
	private $_id;
	
	public function authenticate() {
		
		$usuario = EntJueces::model ()->find ( array (
				'condition' =>"txt_user_name=:txtUserName AND b_juez_admin=0",'params'=>array(':txtUserName'=> $this->username), 
		) ); // here I use Email as user name which comes from database
		
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
		
		else {
			
			$this->_id=$usuario->id_juez;
			
			Yii::app ()->user->setState ( "juezLogueado", $usuario);
			Yii::app ()->user->setState ( "roles", $usuario->b_juez_admin);
			
			$this->errorCode = self::ERROR_NONE;
		}
		
		return ! $this->errorCode;
	}
	
	
	public function getId() 	// override Id
	{
		return $this->_id;
	}
}