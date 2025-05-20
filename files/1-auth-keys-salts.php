<?php

$generator = function() {
	$chars = 'abcdefghijklmnopqrstuvwxyz';
	$chars .= mb_strtoupper($chars);
	$chars .= '0123456789';
	$chars .= '!@#$%^&*()';
	$chars .= '-_ []{}<>~`+=,.;:/?|';
	$length = 64;
	$string = '';

	for ($i = 1; $i <= $length; $i++) {
		$string .= mb_substr($chars, mt_rand(0, mb_strlen($chars) - 1), 1);
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
$output .= "define('NONCE_SALT',\t\t\t\t'{$generator()}');\n";

header('Content-Type: text/plain; charset=utf-8');
echo $output;
