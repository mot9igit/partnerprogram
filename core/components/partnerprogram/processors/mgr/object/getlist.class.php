<?php

class ppObjectsGetListProcessor extends modObjectGetListProcessor
{
	public $classKey = 'ppObjects';
	public $defaultSortField = 'id';
	public $defaultSortDirection = 'asc';
	public $permission = 'list';


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
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c)
	{
		$c->leftJoin('modUser', 'User');
		$c->leftJoin('modUserProfile', 'UserProfile');
		$c->leftJoin('ppObjectsStatus', 'Status');
		$query = trim($this->getProperty('query'));
		if (!empty($query)) {
			if (is_numeric($query)) {
				$c->andCondition(array(
					'id' => $query,
					//'OR:User.id' => $query,
				));
			} else {
				$c->where(array(
					'num:LIKE' => "{$query}%",
					'OR:comment:LIKE' => "%{$query}%",
					'OR:User.username:LIKE' => "%{$query}%",
					'OR:UserProfile.fullname:LIKE' => "%{$query}%",
					'OR:UserProfile.email:LIKE' => "%{$query}%",
				));
			}
		}
		if ($status = $this->getProperty('status')) {
			$c->where(array(
				'status' => $status,
			));
		}
		if ($customer = $this->getProperty('customer')) {
			$c->where(array(
				'user_id' => (int)$customer,
			));
		}
		$this->query = $c;
		$c->select(
			$this->modx->getSelectColumns('ppObjects', 'ppObjects', '', array('Status'), true) . ',
            UserProfile.fullname as customer, User.username as customer_username,
            Status.name as status_name, Status.color as status_color'
		);
		return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object)
	{
		if ($this->getProperty('combo')) {
			$data = array(
				'id' => $object->get('id'),
				'name' => $object->get('name'),
			);
		} else {
			$data = $object->toArray();
			$data['actions'] = array();

			$data['actions'][] = array(
				'cls' => '',
				'icon' => 'icon icon-edit',
				'title' => $this->modx->lexicon('partnerprogram_menu_update'),
				'action' => 'updateObject',
				'button' => true,
				'menu' => true,
			);

			if (empty($data['active'])) {
				$data['actions'][] = array(
					'cls' => '',
					'icon' => 'icon icon-power-off action-green',
					'title' => $this->modx->lexicon('partnerprogram_menu_enable'),
					'multiple' => $this->modx->lexicon('partnerprogram_menu_enable'),
					'action' => 'enableObject',
					'button' => true,
					'menu' => true,
				);
			} else {
				$data['actions'][] = array(
					'cls' => '',
					'icon' => 'icon icon-power-off action-gray',
					'title' => $this->modx->lexicon('partnerprogram_menu_disable'),
					'multiple' => $this->modx->lexicon('partnerprogram_menu_disable'),
					'action' => 'disableObject',
					'button' => true,
					'menu' => true,
				);
			}
			$data['actions'][] = array(
				'cls' => array(
					'menu' => 'red',
					'button' => 'red',
				),
				'icon' => 'icon icon-trash-o',
				'title' => $this->modx->lexicon('partnerprogram_menu_remove'),
				'multiple' => $this->modx->lexicon('partnerprogram_menu_remove_multiple'),
				'action' => 'removeObject',
				'button' => true,
				'menu' => true,
			);
			if($data['status']) {
				$data['status_row'] = '<span style="color:#' . $data['status_color'] . ';">' . $data['status_name'] . '</span>';
			}
		}

		return $data;
	}


	/**
	 * @param array $array
	 * @param bool $count
	 *
	 * @return string
	 */
	public function outputArray(array $array, $count = false)
	{
		if ($this->getProperty('addall')) {
			$array = array_merge_recursive(array(
				array(
					'id' => 0,
					'name' => $this->modx->lexicon('partnerprogram_all'),
				),
			), $array);
		}

		return parent::outputArray($array, $count);
	}

}

return 'ppObjectsGetListProcessor';
