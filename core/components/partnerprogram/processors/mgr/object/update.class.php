<?php

class partnerProgramObjectUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'ppObjects';
    public $classKey = 'ppObjects';
    public $languageTopics = ['partnerprogram'];
    public $permission = 'save';

	/** @var  partnerProgram $partnerProgram */
	protected $partnerProgram;

	/**
	 * @return bool|null|string
	 */
	public function initialize()
	{
		$this->partnerProgram = $this->modx->getService('partnerProgram');
		if (!$this->modx->hasPermission($this->permission)) {
			return $this->modx->lexicon('access_denied');
		}
		return parent::initialize();
	}

	/**
	 * @return bool|string
	 */
	public function beforeSave()
	{
		if ($this->object->get('status') != $this->status) {
			$change_status = $this->partnerProgram->changeObjectStatus($this->object->get('id'),
				$this->object->get('status'));
			if ($change_status !== true) {
				return $change_status;
			}
		}
		$this->object->set('updatedon', time());
		return parent::beforeSave();
	}


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->getProperty('id');
        $name = trim($this->getProperty('name'));
        if (empty($id)) {
            return $this->modx->lexicon('partnerprogram_object_err_ns');
        }

        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('partnerprogram_object_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'id:!=' => $id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('partnerprogram_object_err_ae'));
        }

        return parent::beforeSet();
    }
}

return 'partnerProgramObjectUpdateProcessor';
