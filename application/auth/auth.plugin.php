<?php

class AuthPlugin extends Plugin {

	private $initialized = false;

	private $user_data;
	private $base_module = '';
	private $areas =  array();
	private $area_list = array();
	private $started = false;
	private $requested_module = '';
	protected $name = 'Auth';

	function initialize(){

		if($this->initialized){
			return;
		}

		$default_config = array(
			'enabled' => true,
			'security_key' => '',
			'module_name' => 'auth',
			'login_action' => 'login',
			'cookie_name' => 'phaxsi_user',
			'cookie_duration' => 15,
			'hash_function' => 'sha512',
			'cookie_path' => AppConfig::BASE_URL,
			'default_role' => 'guest',
			'default_area' => 'open-area',
			'areas' => array(
				'closed-area'		=> array(),
				'guest-area'		=> array('guest'),
				'unconfirmed-area'	=> array('unconfirmed'),
				'member-area'		=> array('member'),
				'open-area'			=> array('member', 'guest', 'unconfirmed')
			),
			'modules' => array('*')
		);

		$this->config = array_merge($default_config, $this->config);

		$this->areas = $this->config['areas'];

		$this->user_data = array('user_role' => $this->config['default_role'],
								 'user_id' => 0,
								 'username' => '');

		$this->initialized = true;

	}

	/**
	 * Method called after routing has taken place, just before the
	 * controller is executed.
	 * @param Context $context
	 */
	function requestStart($context){

		if(!$this->config['enabled']){
			return;
		}

		$this->requested_module = $context->getModule();

		if(count($this->config['modules']) > 1 || $this->config['modules'][0] != '*'){
			if(in_array($this->requested_module, $this->config['modules'])){
				$this->base_module = $this->requested_module;
			}
			elseif(!in_array('*', $this->config['modules'])){
				return;
			}
		}

		#Auth saves the current user_role, user_id and username in the session store
		$user_data = $this->session->get($this->base_module.'_user_data');

		#This means the session store doesn't have any user data
		if(!$user_data){
			#Attempt to login using persistent cookies (remember me feature)
			if(isset($_COOKIE[$this->base_module.$this->config['cookie_name']])){
				$this->loginFromCookie($_COOKIE[$this->base_module.$this->config['cookie_name']]);
			}
		}
		else{
			$this->user_data = $user_data;
		}

	}

	/**
	 * Method called after the module has been setup, but before the action
	 * is executed.
	 * @param Context $context
	 */
	function controllerStart($context){

		if(!$this->config['enabled']){
			return;
		}

		#Execute this only when it is a page controller and not when
		#it is a block, layout or some other type of controller
		if($context->getType() != 'controller'){
			return;
		}

		#Put the action in lowercase to avoid it not being detected due to case
		$action = strtolower($context->getAction());

		#Checks for user credentials and executes in case they are not valid
		if(!$this->isAuthorized($action)){

			$this->callDenyFunction($action);

			$view_type = $context->getViewType();

			switch($view_type){
				case 'html':
				case 'process':
				case 'mail':
					$this->loginRedirect();
					break;
				case 'json':
				case 'feed':
				default:
					exit;
			}

			exit;

		}

		$this->started = true;

	}

	/**
	 * Set a list of actions for a given role.
	 *
	 * @param mixed $zones One of the possible values from AuthPlugin enumeration
	 * @param array $actions An array of action names that are to be checked
	 *						 againts the $role previously specified
	 * @param array $on_deny An array of callback functions to be called
	 *						 when authentication fails.
	 */
	function authorize($zones, $actions, $on_deny = array()){
		/**
		 * @TODO: Check for the actions to exist by means of reflection
		 * to avoid a typo which could create a security hole.
		 */

		$zones = (array)$zones;

		$authorized_roles = array();
		foreach($zones as $name){
			 if(!isset($this->areas[$name])){
				trigger_error("Area '$name' is not defined", E_USER_ERROR);
				return;
			 }
			 $authorized_roles = array_merge($authorized_roles, $this->areas[$name]);
		}

		if($this->started){
			trigger_error("'authorize' method is meant to be used only during
							the module setup. Using it here won't have any effect.", E_USER_WARNING);
		}

		if($on_deny == 'exit' || is_callable($on_deny) || is_string($on_deny)){
			$deny = array();
			foreach($actions as $action){
				$deny[$action] = $on_deny;
			}
			$on_deny = $deny;
		}

		foreach($actions as $action){
			$class_name = $this->requested_module.PhaxsiConfig::$type_info['controller']['suffix'];
			if(AppConfig::DEBUG_MODE && !method_exists($class_name, $action)){
				trigger_error("Action '$action' does not exist in module '$this->requested_module'", E_USER_ERROR);
			}
			$deny = isset($on_deny[$action]) ? $on_deny[$action]: null;
			$this->area_list[strtolower($action)] = array('role'=> $authorized_roles,
														  'on_deny'=> $deny);
		}

	}


	private function isAuthorized($action){

		$authorized_role = isset($this->area_list[$action]) ?
				$this->area_list[$action]['role']
				: $this->areas[$this->config['default_area']];

		return in_array($this->user_data['user_role'], $authorized_role);

	}

	private function callDenyFunction($action){

		if(!isset($this->area_list[$action])){
			return;
		}

		$on_deny = $this->area_list[$action]['on_deny'];

		if(!$on_deny)
			return;
		if($on_deny == 'exit')
			exit;
		if(is_string($on_deny) && !is_callable($on_deny)){
			RedirectHelper::to($on_deny);
		}

		call_user_func($on_deny);

	}

	private function loginRedirect(){
		if($this->requested_module != $this->config['module_name']){
			RedirectHelper::flash($this->config['module_name'].'/'.$this->config['login_action'], 'next', UrlHelper::current('', false));
		}
		else{
			RedirectHelper::to('/');
		}
	}

	function addRole($role_name, array $allowed_areas){
		foreach($allowed_areas as $area){
			if(isset($this->areas[$area])){
				if(!in_array($role_name, $this->areas[$area])){
					$this->areas[$area][] = $role_name;
				}
			}
			else{
				trigger_error("Area '$area' doesn't exist.", E_USER_ERROR);
			}
		}
	}

	function addArea($area_name, $allowed_roles = array()){
		if(isset($this->areas[$area_name])){
			trigger_error("Area '$area_name' already exists.", E_USER_ERROR);
		}
		$this->areas[$area_name] = $allowed_roles;
	}

	function login($user_id, $persistent = false, $base_module = ''){

		if($base_module){
			if(!in_array($base_module, $this->config['modules'])){
				return false;
			}
			$this->base_module = $base_module;
		}

		$data = $this->db->Auth->getData($user_id);

		if(!$data || $data['enabled'] == 0){
			return false;
		}

		if($this->isBanned($user_id, $_SERVER['REMOTE_ADDR'])){
			$this->logout();
			return false;
		}

		$this->user_data = array('user_role' => $data['role'],
								 'user_id' => $user_id,
								 'username' => $data['username']);

		$this->session->set($this->base_module.'_user_data', $this->user_data);

		if($persistent){
			$this->persistLogin($user_id);
		}

		return true;

	}

	private function loginFromCookie($value){

		$parts = explode('|', $value);

		if(count($parts) != 2){
			return false;
		}

		$user_id = $this->db->Auth->getUserId($parts[0], $parts[1]);

		if(!$user_id){
			return false;
		}

		return $this->login($user_id, true);

	}

	private function persistLogin($user_id){
		$data = $this->db->Auth->getData($user_id);
		$value = $data['username'] . '|' . $data['password'];
		$expiration_timestamp = time() + $this->config['cookie_duration'] * 24 * 60 * 60;
		setcookie($this->base_module.$this->config['cookie_name'], $value, $expiration_timestamp , $this->config['cookie_path']);
	}

	function isAuthenticated(){
		return $this->user_data['user_id'] != 0;
	}

	function is($role){
		return $this->user_data['user_role'] == $role;
	}

	function get($name){
		if(isset($this->user_data[$name]))
			return $this->user_data[$name];
		trigger_error("Auth plugin: Trying to get non-existent property '$name'", E_USER_ERROR);
		return null;
	}

	function logout(){

		$this->user_data = array('user_role' => $this->config['default_role'],
								 'user_id' => 0,
								 'username' => '');

		foreach($this->config['modules'] as $module){
			if($module == '*') $module = '';
			setcookie($module.$this->config['cookie_name'], '', mktime(1,0,0,1,1,2000), $this->config['cookie_path']);
		}

		Session::destroy();

	}
	
	function getPasswordHash($password, $username = ''){
		return hash($this->config['hash_function'], $this->config['security_key'].$username.$password);
	}

	function isBanned(){
		return false;
	}

}
