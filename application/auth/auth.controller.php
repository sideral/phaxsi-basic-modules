<?php

class AuthController extends Controller{

	function auth_setup(){
		$this->plugin->Auth->authorize('guest-area', array('login', 'register', 'register_mail', 'unconfirmed'));
		$this->plugin->Auth->authorize('member-area', array('welcome'));
		$this->config = array_merge(array('theme' => 'default', 'old_password' => false), $this->config);
	}

	function login(){

		$this->view->setTemplate('themes/'.$this->config['theme'].'/login');

		$login = $this->view->set('login', $this->load->form('login'));
		$login->setAction("login_process");

		$login->setErrorSummary($this->lang->invalid_user);

		$login->next->setValue(Session::getFlash('next'));

		$this->view->addHelper('form_helper', 'form');

	}

	function login_process(){

		$form = $this->load->form('login', $_POST);

		if(!$form->validate()){
			Session::setFlash('next', $form->next->getValue());
			$this->helper->redirect->flash(UrlHelper::referer(), $form->getId(), $form->getFlashParams());
		}

		$values = $form->getValue();

		if($form->next->getValue()){
			#Parameters 'auth' => 'login' are passed as a flash to the next page
			$this->view->setRedirect($form->next->getValue(), 'auth', 'login');
		}
		else{
			$this->view->setRedirect('/');
		}

		$user_id = $this->db->Auth->getUserId($values['username'], $values['password']);

		if(!$user_id && $this->config['old_password']){
			$user_id = $this->db->Auth->getUserIdFromOldPassword($values['username'], $form->password->getRawValue());
		}

		if(!$user_id){
			$form->username->setErrorCode('invalid');
			$this->helper->redirect->flash(UrlHelper::referer(), $form->getId(), $form->getFlashParams());
		}

		if(!$this->db->Auth->isEnabled($user_id)){
			$this->helper->redirect->flash(UrlHelper::referer(), $form->getId(), $form->getFlashParams());
		}

		if(!$this->db->Auth->isActivated($user_id)){
			$this->helper->redirect->to('/auth/unconfirmed');
		}

		$this->plugin->Auth->login($user_id, $form->remember->isChecked(), $values['module']);

	}

	function logout_process(){
		$this->view->setRedirect("");
		$this->plugin->Auth->logout();
	}

	function register(){

		$this->view->setTemplate('themes/'.$this->config['theme'].'/register');

		$register = $this->view->set('register', $this->load->form('register'));
		$register->setAction("register_mail");

		$this->view->addHelper('form_helper', 'form');

	}

	function register_mail(){

		$this->view->setTemplate('themes/'.$this->config['theme'].'/register_mail');

		$form = $this->load->form('register', $_POST);

		$form->validateOrRedirect();

		$values = $form->getTargetValues('user');

		$values['activation_key'] = sha1(uniqid(null, true));

		$user_id = $this->db->into('user')->insert($values);

		$this->view->set('activation_key', $values['activation_key']);

		$contents = $this->view->send($values['email'], 'Registration confirmation.');

		exit;

		$this->view->setRedirect('welcome');

	}

	function confirm(){

	}

	function confirm_process(){

		$this->helper->filter->validate($this->args,
			array(0 => array('expression' =>'/^[a-f0-9]{40}$/'))
		);

		if(is_null($this->args[0])){
			$this->helper->redirect->to('/');
		}

		$user_id = $this->db->from('user')->select('user_id')->where('activation_key', $this->args[0])->fetchScalar();

		if(!$user_id){
			$this->helper->redirect->to('/');
		}

		$this->db->into('user')->where('user_id', $user_id)->update(array('activation_key' => '', 'role' => 'member'));

		$this->view->setRedirect('confirm');

	}

	function recover(){

		$this->view->setTemplate('themes/'.$this->config['theme'].'/recover');
		$recover = $this->view->set('recover', $this->load->form('recover'));
		$recover->setAction("recover_mail");

		$this->view->addHelper('form_helper', 'form');

	}

	function recover_mail(){


	}

	function reset(){

	}

	function reset_process(){

	}

	function donereset(){

	}

	function welcome(){}

	function unconfirmed(){}

	function activated(){}

}
