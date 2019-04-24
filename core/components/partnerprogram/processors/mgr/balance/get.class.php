<?php

class ppBalanceGetProcessor extends modObjectGetProcessor
{
    /** @var ppObjectsStatus $object */
    public $object;
    public $classKey = 'ppBalance';
    public $languageTopics = array('partnerprogram');
    //public $permission = 'mssetting_view';


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

return 'ppBalanceGetProcessor';