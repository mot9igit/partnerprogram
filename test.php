<?php
/**
 * Created by PhpStorm.
 * User: Артем
 * Date: 11.04.2019
 * Time: 13:00
 */
// Подключаем
define('MODX_API_MODE', true);
require '../index.php';

// Включаем обработку ошибок
$modx->getService('error','error.modError');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

echo '<pre>';
print_r($modx->config);