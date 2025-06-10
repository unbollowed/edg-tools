<?php

$output = '';
$output_length = 14;
$letters = 'abcdefghijklmnopqrstuvwxyz';
$numbers = '0123456789';
$chars = $letters . $numbers;

$generator = function($string) {
	return mb_substr($string, random_int(0, mb_strlen($string) - 1), 1);
};

for ($i = 1; $i <= $output_length; $i++) {
	if ($i === 1) {
		$output .= $generator($letters);
		continue;
	}

	$output .= $generator($chars);
}

$output = "{$output}_";

header('Content-Type: text/plain; charset=utf-8');
echo $output;
