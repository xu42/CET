<?php

require 'vendor/autoload.php';

$cet = new \Cn\Xu42\Cet\Service\CetService();

var_dump($cet->query('张三', '123456789101112'));
