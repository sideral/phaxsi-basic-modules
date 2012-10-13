<?php

class AuthForm extends Form {

	function login(){

		$username = $this->add('text', 'username');
		$password = $this->add('password', 'password');
		$remember = $this->add('checkbox', 'remember');

		$module = $this->add('hidden', 'module');
		$next = $this->add('hidden', 'next');

		$submit = $this->add('submit', 'submit', $this->lang->login_title);
		$submit_image = $this->add('image', 'submit_image', $this->lang->login_title);

		$this->setErrorSummary($this->lang->invalid_user);

		$username->setValidator(array('required' => true));
		$password->setValidator(array('required' => true));
		$next->setValidator($this->valid->hostless_url);

		$password->setFilter(array(&$this,'_filterSaltedHash'));
		$username->setFilter(array(&$this,'_filterStrtolower'));

	}

	function register(){

		/****** Components ******/

		$username = $this->add('text', 'username');
		$email = $this->add('text', 'email');

		$password = $this->add('password', 'password');
		$confirm = $this->add('password', 'confirm');

		$terms = $this->add('checkbox', 'terms');

		$submit = $this->add('submit', 'submit', $this->lang->register_title);
		$submit_image = $this->add('image', 'submit_image', $this->lang->register_title);

		/****** Validators ******/

		$username->setValidator(
					array('required'   			=> true,
						  'expression' 			=> "/^[a-zA-Z][a-zA-Z0-9]+$/",
						  'min_length' 			=> 4,
						  'max_length' 			=> 15,
						  'db_not_in_column' 	=> array('user', 'username')),
					$this->lang->username_errors
		);

		$options = $this->valid->get('required', 'email');
		$options['db_not_in_column'] = array('user', 'email');

		$email->setValidator(
			$options,
			$this->lang->email_errors
		);

		$password->setValidator(
					array('required' => true,
						  'min_length' => 5,
						  'max_length' => 30,
						  'comparisons' => array($password->getValue() != $username->getValue())),
					$this->lang->password_errors
		);

		$confirm->setValidator(
					array('comparisons' => array($password->getValue() == $confirm->getValue())),
					$this->lang->confirmation_errors
		);

		$terms->setValidator(
					array('required' => true),
					$this->lang->terms_errors
		);

		/****** Filters and targets ******/

		$password->setFilter(array(&$this,'_filterSaltedHash'));
		$username->setFilter(array(&$this,'_filterStrtolower'));

		$username->setTarget('user');
		$email->setTarget('user');
		$password->setTarget('user');

	}

	function recover(){

		$email = $this->add('text', 'email');
		$submit = $this->add('submit', 'submit', $this->lang->register_title);
		$submit_image = $this->add('image', 'submit_image', $this->lang->register_title);

		$options = $this->valid->get('required', 'email');
		$options['db_in_column'] = array('user', 'email');

		$email->setValidator(
			$options,
			$this->lang->email_errors
		);
		
	}

	function _filterSaltedHash($value){
		return $this->plugin->Auth->getPasswordHash($value, $this->username->getValue());
	}

	function _filterStrtolower($value, $name){
		return strtolower($value);
	}


}
