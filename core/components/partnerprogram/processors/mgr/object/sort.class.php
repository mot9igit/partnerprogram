<?php

class ppObjectsSortProcessor extends modObjectProcessor
{
	public $classKey = 'ppObjects';
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
	 * @return array|string
	 */
	public function process()
	{
		if (!$this->modx->getCount($this->classKey, $this->getProperty('target'))) {
			return $this->failure();
		}

		$sources = json_decode($this->getProperty('sources'), true);
		if (!is_array($sources)) {
			return $this->failure();
		}
		foreach ($sources as $id) {
			/** @var ppObjects $source */
			$source = $this->modx->getObject($this->classKey, compact('id'));
			/** @var ppObjects $target */
			$target = $this->modx->getObject($this->classKey, array('id' => $this->getProperty('target')));
			$this->sort($source, $target);
		}
		$this->updateIndex();

		return $this->modx->error->success();
	}

	/**
	 *
	 */
	public function updateIndex()
	{
		// Check if need to update indexes
		$c = $this->modx->newQuery($this->classKey);
		$c->groupby('rank');
		$c->select('COUNT(rank) as idx');
		$c->sortby('idx', 'DESC');
		$c->limit(1);
		if ($c->prepare() && $c->stmt->execute()) {
			if ($c->stmt->fetchColumn() == 1) {
				return;
			}
		}

		// Update indexes
		$c = $this->modx->newQuery($this->classKey);
		$c->select('id');
		$c->sortby('rank ASC, id', 'ASC');
		if ($c->prepare() && $c->stmt->execute()) {
			$table = $this->modx->getTableName($this->classKey);
			$update = $this->modx->prepare("UPDATE {$table} SET rank = ? WHERE id = ?");
			$i = 0;
			while ($id = $c->stmt->fetch(PDO::FETCH_COLUMN)) {
				$update->execute(array($i, $id));
				$i++;
			}
		}
	}
}

return 'ppObjectsSortProcessor';
