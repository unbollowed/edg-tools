<?php

$data = random_bytes(16);
$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

header('Content-Type: text/plain; charset=utf-8');
echo vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
