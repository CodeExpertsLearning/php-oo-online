<?php
namespace CodeExperts\Tools;

class Validator
{
	public static function validateEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);	
	}

	public static function fieldsRequired($data) {
		foreach($data as $key => $d) {
			if(!$data[$key]) {
				return false;
			}
		}
		return true;
	}

	public static function validateLengthPassword($password)
	{
		return strlen($password) >= 6;
	}
}