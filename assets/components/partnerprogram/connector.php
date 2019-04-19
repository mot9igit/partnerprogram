<?php

// For debug
ini_set('display_errors', 1);
ini_set('error_reporting', -1);


if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var partnerProgram $partnerProgram */
$corePath = $modx->getOption('partnerprogram_core_path', $config, $modx->getOption('core_path') . 'components/partnerprogram/');
$assetsUrl = $modx->getOption('partnerprogram_assets_url', $config, $modx->getOption('assets_url') . 'components/partnerprogram/');
$partnerProgram = $modx->getService('partnerProgram', 'partnerProgram', $corePath . 'model/');
$modx->lexicon->load('partnerprogram:default');

// handle request
//$corePath = $modx->getOption('partnerprogram_core_path', null, $modx->getOption('core_path') . 'components/partnerprogram/');
$path = $modx->getOption('processorsPath', $partnerProgram->config, $corePath . 'processors/');

// echo $corePath;

/** @var modConnectorRequest $request */
$path = $modx->getOption('processorsPath', $partnerProgram->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));