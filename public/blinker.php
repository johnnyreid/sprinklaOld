#!/usr/bin/env php
<?php
# Usage :
#
#   $ sudo blinker 17 200000
#
# = Blink GPIO #17 pin with 0.2 second delay
require 'vendor/autoload.php';
use PhpGpio\Gpio;
// Am i using php-cli?
if ('cli' != PHP_SAPI) {
    echo $msg = "This script must be run using php-cli";
    throw new \Exception($msg);
}
// Am I a sudoer or the root user?
if ('root' !== $_SERVER['USER'] || empty($_SERVER['SUDO_USER'])) {
    echo $msg = "Please run this script as root, using sudo -t ; please check the README file";
    throw new \Exception($msg);
}
// Am I using only 2 integer arguments ?
if (
    !(3 === $argc)
    || (0 >= (int)($argv[1]))
    || (0 >= (int)($argv[2]))
) {
    echo $msg = "This script expect 2 positive integer arguments: please check the README file";
    throw new \Exception($msg);
}
$pin = (int)$argv[1];
$sleeper = (int)$argv[2];
$gpio = new GPIO();
if(!in_array($pin, $gpio->getHackablePins())){
    echo $msg = "$pin is not a hackable gpio pin number";
    throw new \InvalidArgumentException($msg);
}
$gpio->setup($pin, "out");
$gpio->output($pin, 1);
usleep($sleeper);
$gpio->output($pin, 0);
$gpio->unexportAll();