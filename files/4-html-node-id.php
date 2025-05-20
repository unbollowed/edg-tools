<?php

$chars = 'abcdefghijklmnopqrstuvwxyz';
$length = 6;
$string = '';

for ($i = 0; $i < $length; $i++) {
	$string .= mb_substr($chars, mt_rand(0, mb_strlen($chars) - 1), 1);
}

header('Content-Type: text/plain; charset=utf-8');
echo "edg-{$string}";
