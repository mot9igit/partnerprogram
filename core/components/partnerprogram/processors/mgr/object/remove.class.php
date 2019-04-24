<?php

class ppObjectRemoveProcessor extends modObjectProcessor
{
    public $classKey = 'ppObjects';
    public $languageTopics = ['partnerprogram'];
    public $permission = 'remove';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->getProperty('ids');
        //$this->modx->log(1, $this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('partnerprogram_object_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var partnerProgramItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('partnerprogram_object_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'ppObjectRemoveProcessor';