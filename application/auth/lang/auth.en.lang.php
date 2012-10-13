<?php

class AuthLang extends Lang{

	public $invalid_user = "Invalid username or password";
	public $login_title = "Sign In";

	public $password = "Password";
	public $username = "Username";
	public $retype_password = "Retype password";
	public $email = 'Email';
	public $remember_me = "Keep me signed in";

	public $username_errors = array('required'   			=> "Username is required",
									'min_length'			=> "Username must have at least 4 characters",
									'max_length' 			=> "Username can have a maximum of 15 characters",
									'expression' 			=> "Username must have letters and numbers only, and must begin with a letter",
									'db_not_in_column'  	=> "Username already exists! Please select another one");

	public $email_errors = array('required' => 'Email is required',
									'expression' => 'Invalid email',
									'db_not_in_column' => 'Email already exists. Please sign up.');

	public $password_errors = array('required' => 'Password is required',
									'min_length' => 'Password must have at least 5 characters',
									'max_length' => "Password can have a maximum of 30 characters",
									'comparisons/0' => "Password and username must not be the same");

	public $confirmation_errors = array('comparisons/0' => "Confirmation didn't match the password");

	public $terms_errors = array('required' => "You must accept our terms of use");

	public $register_title = 'Register';

}
