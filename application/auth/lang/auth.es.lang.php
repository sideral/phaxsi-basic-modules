<?php

class AuthLang extends Lang{

	public $invalid_user = "Nombre de usuario o contraseña inválido";
	public $login_title = "Entrar";

	public $password = "Contraseña";
	public $username = "Usuario";
	public $retype_password = "Verificar contraseña";
	public $email = 'Email';
	public $remember_me = "Recordarme";

	public $username_errors = array('required'   			=> "El nombre de usuario es requerido",
									'min_length'			=> "El nombre de usuario debe tener al menos 4 caracteres",
									'max_length' 			=> "El nombre de usuario puede tener un máximo de 15 caracteres",
									'expression' 			=> "El nombre de usuario debe tener numeros y letras únicamente, y empezar con una letra",
									'db_not_in_column'  	=> "El nombre de usuario ya existe! Por favor seleccione uno diferente.");

	public $email_errors = array('required' => 'El correo electrónico es requerido',
									'expression' => 'Email inválido.',
									'db_not_in_column' => 'Ya existe un usuario con este email');

	public $password_errors = array('required' => 'La contraseña es requerida.',
									'min_length' => 'La contraseña debe tener al menos 5 catacteres',
									'max_length' => "La contraseña puede tener una máximo de 30 caracteres",
									'comparisons/0' => "La contraseña y el usuario no deben ser iguales.");

	public $confirmation_errors = array('comparisons/0' => "Las contraseñas no son iguales");

	public $terms_errors = array('required' => "Debe aceptar nuestros términos de uso");

	public $register_title = 'Registrarse';

}
