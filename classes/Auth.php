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

	public function generate_password($number) {
  
	  $arr = [
			'a','b','c','d','e','f',
			'g','h','i','j','k','l',
			'm','n','o','p','r','s',
			't','u','v','x','y','z',
			'A','B','C','D','E','F',
			'G','H','I','J','K','L',
			'M','N','O','P','R','S',
			'T','U','V','X','Y','Z',
			'1','2','3','4','5','6',
			'7','8','9','0','.',',',
			'(',')','[',']','!','?',
			'&','^','%','@','*','$',
			'<','>','/','|','+','-',
			'{','}','`','~'
	   ];

    $password = "";

    for($i = 0; $i < $number; $i++) {
      $index = rand(0, count($arr) - 1);
      $password .= $arr[$index];
    }

    return $password;
  }

}