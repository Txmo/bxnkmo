<?php

define('PROJECT', '/bxnkmo');
define('ROOT', dirname(__DIR__));
define('BXNMKO', ROOT . PROJECT);
define('DOMAIN', strpos($_SERVER['HTTP_HOST'], PROJECT) !== false ? $_SERVER['HTTP_HOST'] : $_SERVER['HTTP_HOST'] . PROJECT);

/**
 * Function to escape the given $val and return it
 * @param $val
 * @param bool $doubleEncode
 * @return string
 */
function e($val, $doubleEncode = true): string
{
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8', $doubleEncode);
}