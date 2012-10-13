<?php

class AuthModel extends Model{

	function getUserId($username, $password_hash){
		$result = $this->query(
			"SELECT user_id FROM user WHERE username = ? AND password = ?",
			$username, $password_hash
		);
		return $result->fetchScalar();
	}

	function getUserIdFromOldPassword($username, $password){

		$password_hash = md5($this->plugin->Auth->getConfig('security_key').$password);
		$user_id = $this->getUserId($username, $password_hash);

		if(!$user_id){
			$password_hash = md5($password);
			$user_id = $this->getUserId($username, $password_hash);
		}

		if($user_id){
			$new_password_hash = $this->plugin->Auth->getPasswordHash($password, $username);
			$this->query('UPDATE user SET password = ? WHERE user_id = ?', $new_password_hash, $user_id);
		}

		return $user_id;

	}

	function getData($user_id){
		$result = $this->query("SELECT * FROM user WHERE user_id = ?", $user_id);
		return $result->fetchRow();
	}

	function isEnabled($user_id){
		$result = $this->query("SELECT enabled FROM user WHERE user_id = ?", $user_id);
		return $result->fetchScalar() == 1;
	}

	function isActivated($user_id){
		$user = $this->getData($user_id);
		return $user['activation_key'] == '' && $user['role'] != 'unconfirmed';
	}

	function isBanned($user_id, $ip = null){

		$result = $this->query("SELECT COUNT(*) FROM user_banned WHERE user_id = ?", $user_id);
		$count = $result->fetchScalar();

		if($count == 0 && $ip){
			$result = $this->query("SELECT COUNT(*) FROM user_banned WHERE ip = ?", $ip);
			$count = $result->fetchScalar();
		}

		return (bool)$count;

	}

	function changePassword($user_id, $new_password_hash){
		$result = $this->query(
				"UPDATE user SET password = ? WHERE user_id = ?",
				$new_password_hash, $user_id
		);
		return !$result->isError();
	}

}

