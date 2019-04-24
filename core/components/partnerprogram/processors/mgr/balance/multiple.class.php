<?php

class ppBalanceMultipleProcessor extends modProcessor
{


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$method = $this->getProperty('method', false)) {
            return $this->failure();
        }
        $ids = json_decode($this->getProperty('ids'), true);
        if (empty($ids)) {
            return $this->success();
        }

        /** @var partnerProgram $partnerprogram */
		$partnerProgram = $this->modx->getService('partnerProgram');

        foreach ($ids as $id) {
            /** @var modProcessorResponse $response */
            $response = $partnerProgram->runProcessor('mgr/status/' . $method, array('id' => $id));
            if ($response->isError()) {
                return $response->getResponse();
            }
        }

        return $this->success();
    }

}

return 'ppBalanceMultipleProcessor';