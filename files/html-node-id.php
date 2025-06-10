<?php

$output = '';
$errors = [];
$id = '';
$id_length = 6;
$id_chars = 'abcdefghijklmnopqrstuvwxyz';
$prefix_max_length = 20;
$prefix_allowed_chars = '/^[a-z]+$/';
$prefix_fallback = 'edg';
$prefix_param = $_GET['prefix'] ?? $prefix_fallback;

if (!is_string($prefix_param)) {
	$prefix_param = $prefix_fallback;
}

if (mb_strlen($prefix_param) > $prefix_max_length) {
	$errors[] = sprintf('Prefix must be %d characters or less', number_format($prefix_max_length));
}

if ($prefix_param !== '' && !preg_match($prefix_allowed_chars, $prefix_param)) {
	$errors[] = sprintf('Prefix can only contain lowercase ASCII letters (a-z)');
}


if ($prefix_param !== '') {
	$prefix_param = "{$prefix_param}-";
}

for ($i = 0; $i < $id_length; $i++) {
	$id .= mb_substr($id_chars, random_int(0, mb_strlen($id_chars) - 1), 1);
}

if ($errors !== []) {
	$output = $errors[0];
} else {
	$output = $prefix_param . $id;
}

header('Content-Type: text/plain; charset=utf-8');
echo $output;
