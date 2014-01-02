<?php
require 'vendor/autoload.php';
if (!function_exists('storage_path'))
{
	function storage_path()
	{
		return sys_get_temp_dir();
	}
}
if (!function_exists('snake_case'))
{
	function snake_case($value, $delimiter = '_')
	{
		$replace = '$1' . $delimiter . '$2';

		return ctype_lower($value) ? $value : strtolower(preg_replace('/(.)([A-Z])/', $replace, $value));
	}
}