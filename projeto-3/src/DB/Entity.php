<?php
namespace Code\DB;

use \PDO;

abstract class Entity
{
	/**
	 * @var PDO
	 */
	private $conn;

	protected $table;

	public function __construct(\PDO $conn)
	{
		$this->conn = $conn;
	}

	public function findAll($fields = '*')
	{
		$sql = 'SELECT ' . $fields . ' FROM ' . $this->table;

		$get = $this->conn->query($sql);

		return $get->fetchAll(PDO::FETCH_ASSOC);
	}

	public function find(int $id, $fields = '*')
	{
		return current($this->where(['id' => $id], '', $fields));
	}

	public function where(array $conditions, $operator = ' AND ', $fields = '*')
	{
		$sql = 'SELECT ' . $fields . ' FROM ' . $this->table . ' WHERE ';

		$binds = array_keys($conditions);

		$where  = null;
		foreach($binds as $v) {
			if(is_null($where)) {
				$where .= $v . ' = :' . $v;
			} else {
				$where .= $operator . $v . ' = :' . $v;
			}
		}

		$sql .= $where;

		$get = $this->bind($sql, $conditions);
		$get->execute();

		return $get->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function insert($data)
	{
		$binds = array_keys($data);

		$sql = 'INSERT INTO ' . $this->table . '('. implode(', ', $binds) . ', created_at, updated_at
				) VALUES(:' . implode(', :', $binds) .', NOW(), NOW())';

		$insert = $this->bind($sql, $data);

		return $insert->execute();
	}

	private function bind($sql, $data)
	{
		$bind = $this->conn->prepare($sql);

		foreach ($data as $k => $v) {
			gettype($v) == 'int' ? $bind->bindValue(':' . $k, $v, \PDO::PARAM_INT)
				: $bind->bindValue(':' . $k, $v, \PDO::PARAM_STR);
		}

		return $bind;
	}
}