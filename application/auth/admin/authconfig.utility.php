<?php

class AuthConfigUtility extends AdminConfigUtility{

	function configureSchema($schema){

		$schema->addTable('user',
			array('user_id' => array('primary'),
				  'username'	=> array('string', 'Username'),
				  'password'	=> array('string', 'Password'),
				  'email'		=> array('string', 'Email'),
				  'role'		=> array('string', 'Role'),
				  'activation_key' => array('string', 'Activation Key'),
				  'enabled'		=> array('bool', 'Enabled'),
				  'created'		=> array('timestamp')),
			array(),
			array(
				'username' => array('attributes' => array('autocomplete' => 'off')),
				'password' => array(
					'type' => 'password',
					'attributes' => array('autocomplete' => 'off')						
				)
			)

		);

	}

}
