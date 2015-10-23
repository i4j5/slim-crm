<?php

class Auth {

	protected static $instance;

	public static function instance() {

		if (self::$instance === null) {
			self::$instance = new Auth();
		}

		return self::$instance;
	}

	public function login($password) {

		$hash = $this->hash();

		if (password_verify($password, $hash)) {
			$_SESSION['hash'] = $hash;
			return true;
		}

		return false;
	}

	public function logout() {
		unset($_SESSION['hash']);

		return true;
	}

	public function logged_in() {

		if ($_SESSION['hash'] === $this->hash()) {
			return true;
		}

		return false;
	}

	protected function hash() {
		return \Model::factory('Models\Settings')
							->where('key', 'password_hash')
							->find_one()
							->value;
	}

}