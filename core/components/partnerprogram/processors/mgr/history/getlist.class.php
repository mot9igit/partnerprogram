<?php

class ppHistoryLogGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'ppHistoryLog';
    public $defaultSortField = 'id';
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
		$c->leftJoin('ppObjects', 'Object');
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
			$this->modx->getSelectColumns('ppHistoryLog', 'ppHistoryLog', '', array(), true) . ',
            UserProfile.fullname as customer, User.username as customer_username, Object.name as object_name'
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

return 'ppHistoryLogGetListProcessor';
