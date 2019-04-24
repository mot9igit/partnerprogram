<?php

class ppRewardsUpdateProcessor extends modObjectUpdateProcessor
{
    /** @var ppObjectsStatus $object */
    public $object;
    public $classKey = 'ppRewards';
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


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $required = array('sum');
        foreach ($required as $field) {
            if (!$tmp = trim($this->getProperty($field))) {
                $this->addFieldError($field, $this->modx->lexicon('field_required'));
            } else {
                $this->setProperty($field, $tmp);
            }
        }

        return !$this->hasErrors();
    }

}

return 'ppObjectsStatusUpdateProcessor';