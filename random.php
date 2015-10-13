<?php

// Include composer loader
require __DIR__.'/vendor/autoload.php';

use Push\Push;
use RedisCommunucate\Communicate;
use Predis\Client;

$redisClient = new Communicate(new Client());
$push = new Push('development', 'secret', $redisClient);
$push->publish('chart', ['point' => rand(0,100)]);