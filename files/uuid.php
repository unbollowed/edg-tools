<?php

$output = random_bytes(16);
$output[6] = chr(ord($output[6]) & 0x0f | 0x40);
$output[8] = chr(ord($output[8]) & 0x3f | 0x80);
$output = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($output), 4));

header('Content-Type: text/plain; charset=utf-8');
echo $output;
