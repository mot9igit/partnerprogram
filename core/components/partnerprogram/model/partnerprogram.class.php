<?php

class partnerProgram
{
	public $version = '1.0.0-beta';
	/** @var modX $modx */
	public $modx;
	/** @var pdoFetch $pdoTools */
	public $pdoTools;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, $config = array())
    {
        $this->modx =& $modx;

        //$this->modx->log(1, 'test');

		$corePath = $this->modx->getOption('partnerprogram_core_path', $config, $this->modx->getOption('core_path') . 'components/partnerprogram/');
		$assetsUrl = $this->modx->getOption('partnerprogram_assets_url', $config, $this->modx->getOption('assets_url') . 'components/partnerprogram/');

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'components/partnerprogram/model/',
            'processorsPath' => $corePath . 'components/partnerprogram/processors/',
			'actionUrl' => $assetsUrl . 'action.php',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
			'apiKey' => $this->modx->getOption("partnerprogram_apikey_yandex"),
			'minimal_paid' => $this->modx->getOption("partnerprogram_minimal_paid"),
			'json_response' => false,
			'default_map_data' => json_encode($this->getMapCoordinates())
        ], $config);

        $this->modx->addPackage('partnerprogram', $this->config['modelPath']);
        $this->modx->lexicon->load('partnerprogram:default');

		if ($this->pdoTools = $this->modx->getService('pdoFetch')) {
			$this->pdoTools->setConfig($this->config);
		}
    }

	/**
	 * Initializes component into different contexts.
	 *
	 * @param string $ctx The context to load. Defaults to web.
	 * @param array $scriptProperties Properties for initialization.
	 *
	 * @return bool
	 */
	public function initialize($ctx = 'web', $scriptProperties = array())
	{
		if (isset($this->initialized[$ctx])) {
			return $this->initialized[$ctx];
		}
		$this->config = array_merge($this->config, $scriptProperties);
		$this->config['ctx'] = $ctx;
		$this->modx->lexicon->load('partnerprogram:default');
		if ($ctx != 'mgr' && (!defined('MODX_API_MODE') || !MODX_API_MODE)) {
			$config = $this->pdoTools->makePlaceholders($this->config);
			// Register CSS
			$css = trim("[[+cssUrl]]web/default.css");
			if (!empty($css) && preg_match('/\.css/i', $css)) {
				if (preg_match('/\.css$/i', $css)) {
					$css .= '?v=' . substr(md5($this->version), 0, 10);
				}
				$this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
			}
			// Register JS
			$js = trim("[[+jsUrl]]web/default.js");
			if (!empty($js) && preg_match('/\.js/i', $js)) {
				if (preg_match('/\.js$/i', $js)) {
					$js .= '?v=' . substr(md5($this->version), 0, 10);
				}
				$this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));
				$data = json_encode(array(
					'cssUrl' => $this->config['cssUrl'] . 'web/',
					'jsUrl' => $this->config['jsUrl'] . 'web/',
					'actionUrl' => $this->config['actionUrl'],
					'default_map_data' => $this->config['default_map_data'],
					'minimal_paid' => $this->config['minimal_paid'],
					'ctx' => $ctx,
				), true);
				$this->modx->regClientStartupScript(
					'<script type="text/javascript">ppConfig = ' . $data . ';</script>', true
				);
			}
		}
		$this->initialized[$ctx] = $this->config;
		return $this->initialized[$ctx];
	}

	/**
	 * Function for sending email
	 *
	 * @param string $email
	 * @param string $subject
	 * @param string $body
	 *
	 * @return void
	 */
	public function sendEmail($email, $subject, $body = '')
	{
		$this->modx->getParser()->processElementTags('', $body, true, false, '[[', ']]', array(), 10);
		$this->modx->getParser()->processElementTags('', $body, true, true, '[[', ']]', array(), 10);
		/** @var modPHPMailer $mail */
		$mail = $this->modx->getService('mail', 'mail.modPHPMailer');
		$mail->setHTML(true);
		$mail->address('to', trim($email));
		$mail->set(modMail::MAIL_SUBJECT, trim($subject));
		$mail->set(modMail::MAIL_BODY, $body);
		$mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
		$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
		if (!$mail->send()) {
			$this->modx->log(modX::LOG_LEVEL_ERROR,
				'An error occurred while trying to send the email: ' . $mail->mailer->ErrorInfo
			);
		}
		$mail->reset();
	}

	/**
	 * Function for formatting dates
	 *
	 * @param string $date Source date
	 *
	 * @return string $date Formatted date
	 */
	public function formatDate($date = '')
	{
		$df = $this->modx->getOption('partnerprogram_date_format', null, '%d.%m.%Y %H:%M');
		return (!empty($date) && $date !== '0000-00-00 00:00:00')
			? strftime($df, strtotime($date))
			: '&nbsp;';
	}

	/**
	 * Shorthand for original modX::invokeEvent() method with some useful additions.
	 *
	 * @param $eventName
	 * @param array $params
	 * @param $glue
	 *
	 * @return array
	 */
	public function invokeEvent($eventName, array $params = array(), $glue = '<br/>')
	{
		if (isset($this->modx->event->returnedValues)) {
			$this->modx->event->returnedValues = null;
		}
		$response = $this->modx->invokeEvent($eventName, $params);
		if (is_array($response) && count($response) > 1) {
			foreach ($response as $k => $v) {
				if (empty($v)) {
					unset($response[$k]);
				}
			}
		}
		$message = is_array($response) ? implode($glue, $response) : trim((string)$response);
		if (isset($this->modx->event->returnedValues) && is_array($this->modx->event->returnedValues)) {
			$params = array_merge($params, $this->modx->event->returnedValues);
		}
		return array(
			'success' => empty($message),
			'message' => $message,
			'data' => $params,
		);
	}
	/**
	 * This method returns an error of the order
	 *
	 * @param string $message A lexicon key for error message
	 * @param array $data .Additional data, for example cart status
	 * @param array $placeholders Array with placeholders for lexicon entry
	 *
	 * @return array|string $response
	 */
	public function error($message = '', $data = array(), $placeholders = array())
	{
		$response = array(
			'success' => false,
			'message' => $this->modx->lexicon($message, $placeholders),
			'data' => $data,
		);
		return $this->config['json_response']
			? json_encode($response)
			: $response;
	}
	/**
	 * This method returns an success of the order
	 *
	 * @param string $message A lexicon key for success message
	 * @param array $data .Additional data, for example cart status
	 * @param array $placeholders Array with placeholders for lexicon entry
	 *
	 * @return array|string $response
	 */
	public function success($message = '', $data = array(), $placeholders = array())
	{
		$response = array(
			'success' => true,
			'message' => $this->modx->lexicon($message, $placeholders),
			'data' => $data,
		);
		return $this->config['json_response']
			? json_encode($response)
			: $response;
	}
	/**
	 * Shorthand for the call of processor
	 *
	 * @access public
	 *
	 * @param string $action Path to processor
	 * @param array $data Data to be transmitted to the processor
	 *
	 * @return mixed The result of the processor
	 */
	public function runProcessor($action = '', $data = array())
	{
		if (empty($action)) {
			return false;
		}
		$this->modx->error->reset();
		$processorsPath = !empty($this->config['processorsPath'])
			? $this->config['processorsPath']
			: MODX_CORE_PATH . 'components/partnerprogram/processors/';
		return $this->modx->runProcessor($action, $data, array(
			'processors_path' => $processorsPath,
		));
	}

	/**
	 * Get coordinates info
	 */
	public function getCoordinates($address)
	{
		$addressData = file_get_contents('http://geocode-maps.yandex.ru/1.x/?format=json&apiKey'.$this->config['apiKey'].'=&geocode=' . urlencode($address));
		$jsonAddressData = json_decode($addressData, 1);
		$datas = array();
		if($jsonAddressData['response']['GeoObjectCollection']['featureMember'][0]){
			$data = $jsonAddressData['response']['GeoObjectCollection']['featureMember'][0];
			$datas['address'] = $data['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];
			$datas['coordinates'] = str_replace(" ", ",", $data['GeoObject']['Point']['pos']);;
			$datas['postal_code'] = $data['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['postal_code'];
			foreach($data['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'] as $comp){
				$datas[$comp['kind']] = $comp['name'];
			}
		}

		return $datas;
	}

	/**
	 * Get address info
	 */
	public function getAddress($address)
	{
		$addressData = file_get_contents('http://geocode-maps.yandex.ru/1.x/?format=json&apiKey='.$this->config['apiKey'].'&geocode=' . urlencode($address));
		$jsonAddressData = json_decode($addressData, 1);
		$datas = array();
		if($jsonAddressData['response']['GeoObjectCollection']['featureMember'][0]){
			$data = $jsonAddressData['response']['GeoObjectCollection']['featureMember'][0];
			$datas['address'] = $data['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];
			$datas['coordinates'] = str_replace(" ", ",", $data['GeoObject']['Point']['pos']);
			$datas['postal_code'] = $data['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['postal_code'];
			foreach($data['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'] as $comp){
				$datas[$comp['kind']] = $comp['name'];
			}
		}

		return $datas;
	}

	/**
	 * Object check
	 */
	public function objectCheck($address)
	{
		$yaData = $this->getCoordinates($address);
		$yaData['action'] = "object/check";
		$criteria = array(
			'locality' => $yaData['province'],
			'city' => $yaData['locality'],
			'street' => $yaData['street'],
			'house' => $yaData['house']
		);
		$object = $this->modx->getObject("ppObjects", $criteria);
		if($object){
			$data["action"] = "object/check";
			$addressData = $this->error("partnerprogram_error_object_exist", $data);
		}else{
			$data = $yaData;
			$addressData = $this->success("partnerprogram_object_no_exist", $data);
		}
		//$this->modx->log(1, print_r($data, 1));
		return $addressData;
	}

	/**
	 * Object add
	 */
	public function objectAdd($data)
	{
		if(!$data['user_id']){
			$data['user_id'] = $this->modx->user->id;
		}
		if ($data['name']){
			$object = $this->modx->newObject('ppObjects');
			$object->set('user_id', $data['user_id']);
			$object->set('name', $data['name']);
			$object->set('area', $data['area']);
			$object->set('locality', $data['province']);
			$object->set('city', $data['locality']);
			$object->set('street', $data['street']);
			$object->set('house', $data['house']);
			$object->set('createdon', date('Y-m-d H:i:s'));
			$object->set('updatedon', date('Y-m-d H:i:s'));
			$object->set('status', 1);
			$object->set('coordinates', $data['coordinates']);
			$object->save();
		}
		$data['action'] = "object/add";
		return $this->success("partnerprogram_object_add", $data);
	}

	/**
	 * Object add
	 */
	public function balanceAdd($userId)
	{
		if ($userId){
			$object = $this->modx->newObject('ppBalance');
			$object->set('user_id', $userId);
			$object->save();
		}
		$data['action'] = "object/add";
		return $this->success("partnerprogram_object_add", $data);
	}

	/**
	 * Balance update
	 */
	public function balanceUpdate($data)
	{
		$criteria = array(
			"user_id" => $this->modx->user->id
		);
		$balance = $this->modx->getObject("ppBalance", $criteria);
		if($balance){
			$balance->set("phone", $data["phone"]);
			$balance->set("card", $data["card"]);
			$balance->save();
		}else{
			$object = $this->modx->newObject('ppBalance');
			$object->set('user_id', $this->modx->user->id);
			$object->set("phone", $data["phone"]);
			$object->set("card", $data["card"]);
			$object->save();
		}
		$data['action'] = "balance/update";
		return $this->success("partnerprogram_balance_updated", $data);
	}

	/**
	 * Object update
	 */
	public function objectUpdate($datas)
	{
		$object = $this->modx->getObject("ppObjects", $datas['id']);
		$user_id = $object->get("user_id");
		$status = $object->get("status");
		$data['action'] = "object/update";
		if($user_id != $this->modx->user->id){
			return $this->error("partnerprogram_nonono", $data);
		}
		if($status != 1){
			return $this->error("partnerprogram_nonono_status", $data);
		}
 		foreach($datas as $key => $value){
			if($key != 'user_id' && $key != 'pp_action' && $key != 'status' && $key != 'id'){
				$object->set($key, $value);
			}
		}
		$object->save();
		return $this->success("partnerprogram_object_updated", $data);
	}

	/**
	 * Object remove
	 */
	public function objectRemove($data)
	{
		$object = $this->modx->getObject("ppObjects", $data['id']);
		$user_id = $object->get("user_id");
		$status = $object->get("status");
		$data['action'] = "object/remove";
		if($user_id != $this->modx->user->id){
			return $this->error("partnerprogram_nonono", $data);
		}
		if($status != 1){
			return $this->error("partnerprogram_nonono_status", $data);
		}
		if ($object->remove() == false) {
			$this->error("partnerprogram_removing_error", $data);
		}
		return $this->success("partnerprogram_removing_success", $data);
	}

	/**
	 * Object get
	 */
	public function objectGet($data)
	{
		$box = $this->modx->getObject('ppObjects',$data['id']);
		$data['action'] = "object/get";
		if ($box) {
			$data["fields"] = $box->toArray();
			$this->success("partnerprogram_object_found", $data);
		}
		return $this->error("partnerprogram_object_not_found", $data);
	}

	/**
	 *
	 * GET COORDINATES
	 *
	 */
	public function getMapStartCoordinates($data){

		$mapdata = array(
			'action' => "mapdata/get",
			'position' => $data['coordinates'],
			'mark' => $data['coordinates'],
			'balloon' => $data['address'],
			'zoom' => 16
		);
		$addressData = $this->success("partnerprogram_object_data", $mapdata);
		//$this->modx->log(1, print_r($addressData, 1));
		return $addressData;
	}

	/**
	 *
	 * GET COORDINATES
	 *
	 */
	public function getMapCoordinates($object = false){
		if(!$object){
			return array(
				'position' => array(
					'100.540011',
					'62.973206'
				),
				'mark' => false,
				'zoom' => 4
			);
		}
		$coordinates = $object->get("coordinates");
		if(count($coordinates) && $coordinates !== ''){
			return array(
				'position' => $coordinates,
				'mark' => $coordinates,
				'balloon' => $object->get("name"),
				'zoom' => 14
			);
		}else {
			return array(
				'position' => array(
					'100.540011',
					'62.973206'
				),
				'mark' => false,
				'zoom' => 4
			);
		}
	}

	/**
	 *
	 * SEND_MONEY
	 *
	 */
	public function sendmoney(){
		$criteria = array(
			'user_id' => $this->modx->user->id,
		);
		$balance = $this->modx->getObject("ppBalance", $criteria);
		$possible = $balance->get("possible_balance");
		$balance->set("possible_balance", 0);
		$balance->set("balance", 0);
		$balance->set("paid_balance", $possible);
		$balance->save();

		$subject = "Запрос на выплату от пользователя";
		$tpl = 'paid_email_tpl';
		$body = $this->pdoTools->getChunk($tpl, array('user' => $this->modx->user->id));
		$emails = array_map('trim', explode(',',
				$this->modx->getOption('partnerprogram_email_manager', null, $this->modx->getOption('emailsender')))
		);
		if (!empty($subject)) {
			foreach ($emails as $email) {
				if (preg_match('#.*?@.*#', $email)) {
					$this->sendEmail($email, $subject, $body);
				}
			}
		}
		$data = array(
			"money" => $possible,
			"user_id" => $this->modx->user->id,
			"action" => "balance/sendmoney"
		);
		return $this->success("partnerprogram_payed", $data);
	}


	/**
	 * Function for logging changes of the object
	 *
	 * @param integer $object_id The id of the order
	 * @param string $action The name of action made with object
	 * @param string $entry The value of action
	 *
	 * @return boolean
	 */
	public function objectLog($object_id, $action = 'status', $entry)
	{
		/** @var object $object */
		if (!$object = $this->modx->getObject('ppObjects', array('id' => $object_id))) {
			return false;
		}
		if (empty($this->modx->request)) {
			$this->modx->getRequest();
		}
		$user_id = ($action == 'status' && $entry == 1) || !$this->modx->user->id
			? $object->get('user_id')
			: $this->modx->user->id;
		$log = $this->modx->newObject('ppHistoryLog', array(
			'object_id' => $object_id,
			'user_id' => $user_id,
			'timestamp' => time(),
			'action' => $action,
			'entry' => $entry,
			'ip' => $this->modx->request->getClientIp(),
		));
		return $log->save();
	}

	public function newPaid($object){
		$this->modx->log(1, "пытаюсь выставить выплату");
		$paid = $this->modx->getOption("partnerprogram_paid");
		$minimal_paid = $this->modx->getOption("partnerprogram_minimal_paid");
		$criteria = array(
			'object_id' => $object->get("id"),
			'user_id' => $object->get("user_id"),
		);
		$reward = $this->modx->getObject("ppRewards", $criteria);
		if($reward){
			$this->modx->log(1, "упс");
			return true;
		}else{
			$reward = $this->modx->newObject('ppRewards', array(
				'object_id' => $object->get("id"),
				'user_id' => $object->get("user_id"),
				'sum' => $paid
			));
			$reward->save();
			$criteria = array(
				'user_id' => $object->get("user_id"),
			);
			$balance = $this->modx->getObject("ppBalance", $criteria);
			$current = $balance->get("balance");
			$newb = $current+$paid;
			$balance->set("balance", $newb);
			if($minimal_paid <= $newb){
				$balance->set("possible_balance", $newb);
			}
			$balance->save();
		}
	}

	/**
	 * Switch order status
	 *
	 * @param integer $order_id The id of ppObjects
	 * @param integer $status_id The id of ppObjectStatus
	 *
	 * @return boolean|string
	 */
	public function changeObjectStatus($object_id, $status_id)
	{
		/** @var ppObjects $object */
		if (!$object = $this->modx->getObject('ppObjects', array('id' => $object_id))) {
			return $this->modx->lexicon('partnerprogram_object_not_found');
		}
		$error = '';
		/** @var ppObjectsStatus $status */
		if (!$status = $this->modx->getObject('ppObjectsStatus', array('id' => $status_id, 'active' => 1))) {
			$error = 'partnerprogram_object_not_found';
		} /** @var ppObjectsStatus $old_status */
		else {
			if ($old_status = $this->modx->getObject('ppObjectsStatus',
				array('id' => $object->get('status'), 'active' => 1))
			) {
				if ($old_status->get('final')) {
					$error = 'partnerprogram_err_status_final';
				} else {
					if ($old_status->get('fixed')) {
						if ($status->get('rank') <= $old_status->get('rank')) {
							$error = 'partnerprogram_err_status_fixed';
						}
					}
				}
			}
		}
		if ($object->get('status') == $status_id) {
			$error = 'partnerprogram_err_status_same';
		}
		if (!empty($error)) {
			return $this->modx->lexicon($error);
		}
		$response = $this->invokeEvent('ppOnBeforeChangeObjectStatus', array(
			'object' => $object,
			'status' => $object->get('status'),
		));
		if (!$response['success']) {
			return $response['message'];
		}
		$object->set('status', $status_id);
		if ($object->save()) {
			$this->objectLog($object->get('id'), 'status', $status_id);
			$response = $this->invokeEvent('ppOnChangeObjectStatus', array(
				'object' => $object,
				'status' => $status_id,
			));
			if (!$response['success']) {
				return $response['message'];
			}

			if ($status->get('email_manager')) {
				$pls = array();
				$subject = $this->pdoTools->getChunk('@INLINE ' . $status->get('subject_manager'), $pls);
				$tpl = '';
				if ($chunk = $this->modx->getObject('modChunk', array('id' => $status->get('body_manager')))) {
					$tpl = $chunk->get('name');
				}
				$body = $this->pdoTools->getChunk($tpl);
				$emails = array_map('trim', explode(',',
						$this->modx->getOption('partnerprogram_email_manager', null, $this->modx->getOption('emailsender')))
				);
				if (!empty($subject)) {
					foreach ($emails as $email) {
						if (preg_match('#.*?@.*#', $email)) {
							$this->sendEmail($email, $subject, $body);
						}
					}
				}
			}
			if ($status->get('email_user')) {
				if ($profile = $this->modx->getObject('modUserProfile', array('internalKey' => $object->get('user_id')))) {
					$pls = array();
					$subject = $this->pdoTools->getChunk('@INLINE ' . $status->get('subject_user'), $pls);
					$tpl = '';
					if ($chunk = $this->modx->getObject('modChunk', array('id' => $status->get('body_user')))) {
						$tpl = $chunk->get('name');
					}
					$body = $this->pdoTools->getChunk($tpl);
					$email = $profile->get('email');
					if (!empty($subject) && preg_match('#.*?@.*#', $email)) {
						$this->sendEmail($email, $subject, $body);
					}
				}
			}
		}
		return true;
	}



	/**
	 * Handle frontend requests with actions
	 *
	 * @param $action
	 * @param array $data
	 *
	 * @return array|bool|string
	 */
	public function handleRequest($action, $data = array())
	{
		$ctx = !empty($data['ctx'])
			? (string)$data['ctx']
			: 'web';
		if ($ctx != 'web') {
			$this->modx->switchContext($ctx);
		}
		$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		$this->initialize($ctx, array('json_response' => $isAjax));
		switch ($action) {
			case 'object/check':
				if($data['address']){
					$datas = $data['address'];
				}else{
					if($_REQUEST["province"]){
						$datas = $_REQUEST["province"].', '.$_REQUEST["city"].', '.$_REQUEST["street"].', '.$_REQUEST["house"];
					}else{
						$datas = $_REQUEST["locality"].', '.$_REQUEST["city"].', '.$_REQUEST["street"].', '.$_REQUEST["house"];
					}

				}
				$response = $this->objectCheck($datas);
				break;
			case 'object/add':
				$response = $this->objectAdd(@$data);
				break;
			case 'object/get':
				$response = $this->objectGet(@$data);
				break;
			case 'object/remove':
				$response = $this->objectRemove(@$data);
				break;
			case 'object/update':
				$response = $this->objectUpdate(@$data);
				break;
			case 'balance/update':
				$response = $this->balanceUpdate(@$data);
				break;
			case 'balance/sendmoney':
				$response = $this->sendmoney(@$data);
				break;
			case 'mapdata/get':
				//$this->modx->log(1, print_r($_REQUEST, 1));
				$data['coordinates'] = explode(",", $_REQUEST["coordinates"]);
				$data['address'] = $_REQUEST["province"].', '.$_REQUEST["city"].', '.$_REQUEST["street"].', '.$_REQUEST["house"];
				$response = $this->getMapStartCoordinates($data);
				break;
			default:
				$message = ($data['pp_action'] != $action)
					? 'partnerprogram_err_register_globals'
					: 'partnerprogram_err_unknown';
				$response = $this->error($message);
		}
		return $response;
	}
}