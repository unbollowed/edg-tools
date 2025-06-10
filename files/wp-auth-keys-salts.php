<?php

$generator = function() {
	$string = '';
	$string_length = 64;
	$chars = 'abcdefghijklmnopqrstuvwxyz';
	$chars .= mb_strtoupper($chars);
	$chars .= '0123456789';
	$chars .= '!@#$%^&*()';
	$chars .= '-_ []{}<>~`+=,.;:/?|';

	for ($i = 1; $i <= $string_length; $i++) {
		$string .= mb_substr($chars, random_int(0, mb_strlen($chars) - 1), 1);
	}

	return $string;
};

$output = '';
$output .= "define('AUTH_KEY',\t\t\t\t\t'{$generator()}');\n";
$output .= "define('SECURE_AUTH_KEY',\t\t'{$generator()}');\n";
$output .= "define('LOGGED_IN_KEY',\t\t\t'{$generator()}');\n";
$output .= "define('NONCE_KEY',\t\t\t\t\t'{$generator()}');\n";
$output .= "define('AUTH_SALT',\t\t\t\t\t'{$generator()}');\n";
$output .= "define('SECURE_AUTH_SALT',\t'{$generator()}');\n";
$output .= "define('LOGGED_IN_SALT',\t\t'{$generator()}');\n";
$output .= "define('NONCE_SALT',\t\t\t\t'{$generator()}');";

header('Content-Type: text/plain; charset=utf-8');
echo $output;
