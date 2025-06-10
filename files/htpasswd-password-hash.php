<?php

$output = '';
$errors = [];
$username_max_length = 128;
$username_allowed_chars = '/^[a-zA-Z0-9]+$/';
$password_max_length = $username_max_length;
$password_allowed_chars = $username_allowed_chars;
$username_param = $_GET['username'] ?? '';
$password_param = $_GET['password'] ?? '';

if (!is_string($username_param)) {
	$username_param = '';
}

if (!is_string($password_param)) {
	$password_param = '';
}

if ($username_param === '') {
	$errors[] = sprintf('Enter a username');
}

if (mb_strlen($username_param) > $username_max_length) {
	$errors[] = sprintf('Username must be %d characters or less', number_format($username_max_length));
}

if (!preg_match($username_allowed_chars, $username_param)) {
	$errors[] = sprintf('Username can only contain ASCII letters (a-z, A-Z) and digits (0-9)');
}

if ($password_param === '') {
	$errors[] = sprintf('Enter a password');
}

if (mb_strlen($password_param) > $password_max_length) {
	$errors[] = sprintf('Password must be %d characters or less', number_format($password_max_length));
}

if (!preg_match($password_allowed_chars, $password_param)) {
	$errors[] = sprintf('Password can only contain ASCII letters (a-z, A-Z) and digits (0-9)');
}

if ($errors !== []) {
	$output = $errors[0];
} else {
	$output = sprintf('%s:%s', $username_param, password_hash($password_param, PASSWORD_BCRYPT));
}

header('Content-Type: text/plain; charset=utf-8');
echo $output;
