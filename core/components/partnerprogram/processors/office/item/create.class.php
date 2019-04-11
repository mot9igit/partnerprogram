<?php

class partnerProgramOfficeItemCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'partnerProgramItem';
    public $classKey = 'partnerProgramItem';
    public $languageTopics = ['partnerprogram'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('partnerprogram_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('partnerprogram_item_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'partnerProgramOfficeItemCreateProcessor';