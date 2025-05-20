<?php

$letters = 'abcdefghijklmnopqrstuvwxyz';
$numbers = '0123456789';
$chars = $letters . $numbers;
$length = 14;
$string = '';

for ($i = 1; $i <= $length; $i++) {
	if ($i === 1) {
		$string .= mb_substr($letters, mt_rand(0, mb_strlen($letters) - 1), 1);
		continue;
	}

	$string .= mb_substr($chars, mt_rand(0, mb_strlen($chars) - 1), 1);
}

header('Content-Type: text/plain; charset=utf-8');
echo "{$string}_";
