<?php

class ppBalanceCreateProcessor extends modObjectCreateProcessor
{
    /** @var ppStatus $object */
    public $object;
    public $classKey = 'ppBalance';
    public $languageTopics = array('partnerprogram');
    // public $permission = 'mssetting_save';


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }

}

return 'ppBalanceCreateProcessor';