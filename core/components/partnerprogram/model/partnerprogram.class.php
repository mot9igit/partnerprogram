<?php

class partnerProgram
{
    /** @var modX $modx */
    public $modx;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $this->modx->log(1, 'test');

		$corePath = $this->modx->getOption('partnerprogram_core_path', $config, $this->modx->getOption('core_path') . 'components/partnerprogram/');
		$assetsUrl = $this->modx->getOption('partnerprogram_assets_url', $config, $this->modx->getOption('assets_url') . 'components/partnerprogram/');

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);

        $this->modx->addPackage('partnerprogram', $this->config['modelPath']);
        $this->modx->lexicon->load('partnerprogram:default');
    }

}