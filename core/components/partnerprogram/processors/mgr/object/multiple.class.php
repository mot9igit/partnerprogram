<?php

class ppObjectsMultipleProcessor extends modProcessor
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

		$response = $partnerProgram->runProcessor('mgr/object/' . $method, array('ids' => $ids));
		if ($response->isError()) {
			return $response->getResponse();
		}


		return $this->success();
	}

}

return 'ppObjectsMultipleProcessor';