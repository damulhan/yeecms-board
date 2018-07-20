<?php 

if(!function_exists('getValue')) {
	function getValue($var, $default) {
		if(!isset($var) || is_null($var))
			return $default;
		else
			return $var;
	}
}

if(!function_exists('get_value')) {
	function get_value($var, $default) {
		return getValue($var, $default); 
	}
}

