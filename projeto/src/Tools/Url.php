<?php
namespace CodeExperts\Tools;

class Url
{
	public static function isUrl($term = null)
	{
		if(is_null($term))
			throw new Exception("Invalid term {$term} to verify URL");
			
		return preg_match("/{$term}/", $_SERVER['REQUEST_URI']);
	}
}