<?php

/**
 * The home manager controller for partnerProgram.
 *
 */
class partnerProgramHomeManagerController extends modExtraManagerController
{
    /** @var partnerProgram $partnerProgram */
    public $partnerProgram;


    /**
     *
     */
    public function initialize()
    {
    	$config = array();
		$corePath = $this->modx->getOption('partnerprogram_core_path', $config, $this->modx->getOption('core_path') . 'components/partnerprogram/');
		//$assetsUrl = $this->modx->getOption('partnerprogram_assets_url', $config, $this->modx->getOption('assets_url') . 'components/partnerprogram/');
		$this->partnerProgram = $this->modx->getService('partnerProgram', 'partnerProgram', $corePath . 'components/partnerprogram/model/');
		parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['partnerprogram:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('partnerprogram');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
		//$this->modx->log(1, 'test');
        $this->addCss($this->partnerProgram->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/partnerprogram.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/misc/combo.js');
		$this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/misc/default.grid.js');
		$this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/objects/grid.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/objects/window.js');
		$this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/status/grid.js');
		$this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/status/window.js');
		$this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/balance/grid.js');
		$this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/balance/window.js');
		$this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/rewards/grid.js');
		$this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/rewards/window.js');
		$this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/history/grid.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        partnerProgram.config = ' . json_encode($this->partnerProgram->config) . ';
        partnerProgram.config.connector_url = "' . $this->partnerProgram->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "partnerprogram-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="partnerprogram-panel-home-div"></div>';

        return '';
    }
}