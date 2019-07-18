<?php

class partnerProgramObjectUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'ppObjects';
    public $classKey = 'ppObjects';
    public $languageTopics = ['partnerprogram'];
    public $permission = 'save';

	protected $status;

	/** @var  partnerProgram $partnerProgram */
	protected $partnerProgram;


	/**
	 * @return bool|null|string
	 */
	public function beforeSet()
	{
		foreach (array('status') as $v) {
			$this->$v = $this->object->get($v);
			if (!$this->getProperty($v)) {
				$this->addFieldError($v, $this->modx->lexicon('parnerProgram_err_ns'));
			}
		}

		return parent::beforeSet();
	}

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
		//$this->modx->log(1, $this->object->get('status')." != ".$this->status);
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
}

return 'partnerProgramObjectUpdateProcessor';
