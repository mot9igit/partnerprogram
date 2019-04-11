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
        $this->partnerProgram = $this->modx->getService('partnerProgram', 'partnerProgram', MODX_CORE_PATH . 'components/partnerprogram/model/');
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
        $this->addCss($this->partnerProgram->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/partnerprogram.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/items.grid.js');
        $this->addJavascript($this->partnerProgram->config['jsUrl'] . 'mgr/widgets/items.windows.js');
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