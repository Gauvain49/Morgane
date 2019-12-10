<?php
namespace App\Services;

class TokenUtils
{
	public function generateToken($lenght)
	{
		$key = '0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN';
		return substr(str_shuffle(str_repeat($key, $lenght)), 0, $lenght);
	}
}