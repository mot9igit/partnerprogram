<?php

class ppBalanceGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'ppBalance';
    public $defaultSortField = 'user_id';
    public $defaultSortDirection = 'asc';
    //public $permission = 'mssetting_list';


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
		$query = trim($this->getProperty('query'));
		if (!empty($query)) {
			if (is_numeric($query)) {
				$c->andCondition(array(
					'id' => $query,
					//'OR:User.id' => $query,
				));
			} else {
				$c->where(array(
					'id:LIKE' => "{$query}%",
					'OR:description:LIKE' => "%{$query}%",
					'OR:User.username:LIKE' => "%{$query}%",
					'OR:UserProfile.fullname:LIKE' => "%{$query}%",
					'OR:UserProfile.email:LIKE' => "%{$query}%",
				));
			}
		}
		if ($customer = $this->getProperty('customer')) {
			$c->where(array(
				'user_id' => (int)$customer,
			));
		}
		$this->query = $c;
		$c->select(
			$this->modx->getSelectColumns('ppBalance', 'ppBalance', '', array(), true) . ',
            UserProfile.fullname as customer, User.username as customer_username'
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
                'action' => 'updateBalance',
                'button' => true,
                'menu' => true,
            );
            if ($data['editable']) {
                $data['actions'][] = array(
                    'cls' => array(
                        'menu' => 'red',
                        'button' => 'red',
                    ),
                    'icon' => 'icon icon-trash-o',
                    'title' => $this->modx->lexicon('partnerprogram_menu_remove'),
                    'multiple' => $this->modx->lexicon('partnerprogram_menu_remove_multiple'),
                    'action' => 'removeBalance',
                    'button' => true,
                    'menu' => true,
                );
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

return 'ppBalanceGetListProcessor';
