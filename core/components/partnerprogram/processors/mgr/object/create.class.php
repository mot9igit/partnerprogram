<?php

class partnerProgramObjectCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'ppObjects';
    public $classKey = 'ppObjects';
    public $languageTopics = ['partnerprogram'];
    //public $permission = 'create';


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('partnerprogram_object_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name])) {
            $this->modx->error->addField('name', $this->modx->lexicon('partnerprogram_object_err_ae'));
        }

        return parent::beforeSet();
    }

}

return 'partnerProgramObjectCreateProcessor';