<?php

class partnerProgramObjectCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'ppObjects';
    public $classKey = 'ppObjects';
    public $languageTopics = ['partnerprogram'];
    //public $permission = 'create';

	/**
	 * Set defaults for the fields if values are not passed
	 * @return mixed
	 */
	public function setFieldDefaults() {
		$scriptProperties = $this->getProperties();

		if(empty($scriptProperties['createdon'])){
			$scriptProperties['createdon'] = strftime('%Y-%m-%d %H:%M:%S');
		}

		$this->setProperties($scriptProperties);
		return true;
	}

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
		$this->object->set('createdon', time(), 'integer');
        return parent::beforeSet();
    }

}

return 'partnerProgramObjectCreateProcessor';