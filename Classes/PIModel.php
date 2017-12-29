<?php
/**
* 
*/
class PIModel
{
	private static $_instance = NULL;
	private $_pdo = NULL,
			$_count = 0,
			$_error = FALSE,
			$_query,
			$_results;

	public function __construct( $host = '', $username = '', $pass = '' )
	{
		$host = ($host == '') ? 'mysql:host=' . Config::get('mysql/host') . '; dbname=' . Config::get('mysql/db') : $host;
		$username = ($username == '') ? Config::get('mysql/username') : $username;
		$pass = ($pass == '') ? Config::get('mysql/password') : $pass;

		if ($this->_pdo === NULL) {
			try {
				$this->_pdo = new PDO($host, $username, $pass);
				$this->_pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
				$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->_pdo->setAttribute(PDO::ATTR_TIMEOUT, 72000);
			} catch (PDOException $e) {
				echo "Database Connection failed. ";//.$e->getMessage();
			}
		}
	}

	public static function getInstance()
	{
		if (!isset(self::$_instance)) {
			self::$_instance = new PIModel();
		}
		return self::$_instance;
	}


	private function query($sql, $params = array()) {
		$this->_error = false;
		if ($this->_pdo)
		{
			if($this->_query = $this->_pdo->prepare($sql)) {
				$x = 1;
				if (count($params)) {
					foreach ($params as &$param) {
						$this->_query->bindParam($x, $param);
						$x++;
					}
				}

			}
		}

		return $this;
	}



	/**
	* move() is for update() and insert() which does not require fetchAll()
	* PHP might barf if you try to update with fetchAll()
	*/
	private function move($sql, $params = array()) {
		$this->_error = false;
		if($this->_pdo !== NULL) {
			if($this->_query = $this->_pdo->prepare($sql)) {
				$x = 1;
				if (count($params)) {
					foreach ($params as &$param) {
						$this->_query->bindParam($x, $param);
						$x++;
					}
				}

				$this->_query->execute();

			}
		}

		return $this;
	}


	public function counting($table, $params='') {
		$this->_error = false;
		if($this->_pdo !== NULL) {
			if ($this->_pdo->query("SELECT count(*) FROM {$table} {$params}")->execute())
			{
				$this->_count = $this->_pdo->query("SELECT count(*) FROM {$table} {$params}")->fetchColumn();
				return $this->_count;
			}
			else
			{
				return false;
			}
		}

		return false;
	}

	public function lastInsert()
	{
		return $this->_pdo->lastInsertId();
	}

	private function action($action, $table, $whereAr = array(), $valuesWhere = array(), $whereOr = array(), $valuesOr = array(), $otherparams = null)
	{
		$count_param = '';

		if (count($whereAr) !== 0 || count($whereOr) !== 0) {
			if (count($whereAr) !== 0) {
				$where = "WHERE ";
				$count_param = "WHERE ";
				$ct = 1;
				foreach ($whereAr as $key => $value) {
					$where .= $value.' ?';
					$count_param .= $value."'".escape($valuesWhere[$key])."'";
					if ($ct < count($whereAr)) {
						$where .= " AND ";
						$count_param .= " AND ";
						$ct++;
					}
				}
			}
			if (count($whereOr) !== 0) {
				$where .= " OR ";
				$count_param .= " OR ";
				$cnt = 1;
				foreach ($whereOr as $key2 => $value2) {
					$where .= $value2.' ?';
					$count_param .= $value2."'".escape($valuesOr[$key2])."'";
					if ($cnt < count($whereOr)) {
						$where .= " AND ";
						$count_param .= " OR ";
						$cnt++;
					}
				}
			}

			$values = array_merge($valuesWhere, $valuesOr);
		} else {
			$where = "";
			$values = array();
		}

		$this->_count = $this->counting($table, $count_param);

		$sql = "{$action} FROM {$table} {$where} {$otherparams}";

		if ( trim($action) == "DELETE" )
		{
			if(!$this->move($sql, $values)->error()) {
				return $this;
			}
		}
		else
		{
			if(!$this->query($sql, $values)->error()) {
				return $this;
			}
		}
	}

	/**
     * get
     * @param Parameters
     * @param value are array Paramters to bind save otherparams
     * @param get('table', array('type =', 'active ='), array('a', '1'),, array('type ='), array('c'), 'ORDER BY id DESC')
     * @return `get` FROM `table` WHERE type='a' AND active='c' OR type='c' ORDER BY id DESC
     * @return mixed => mysqli statement
    */
	public function get($table, $where = array(), $values = array(), $whereOr = array(), $valuesOr = array(), $otherparams = null)
	{
		return $this->action('SELECT *', $table, $where, $values, $whereOr, $valuesOr, $otherparams);
	}

	public function delete($table, $where = array(), $values = array(), $whereOr = array(), $valuesOr = array(), $otherparams = null)
	{
		return $this->action('DELETE ', $table, $where, $values, $whereOr, $valuesOr, $otherparams);
	}

	public function join($stmt, $whereAr = array(), $valuesWhere = array(), $whereOr = array(), $valuesOr = array(), $otherparams = null)
	{
		if (count($whereAr) !== 0 || count($whereOr) !== 0) {
			if (count($whereAr) !== 0) {
				$where = "WHERE ";
				$ct = 1;
				foreach ($whereAr as $key => $value) {
					$where .= $value.' ?';
					if ($ct < count($whereAr)) {
						$where .= " AND ";
						$ct++;
					}
				}
			}
			if (count($whereOr) !== 0) {
				$where .= " OR ";
				$cnt = 1;
				foreach ($whereOr as $key2 => $value2) {
					$where .= $value2.' ?';
					if ($cnt < count($whereOr)) {
						$where .= " AND ";
						$cnt++;
					}
				}
			}
		} else {
			$where = "";
			$value = array();
		}
		$sql = "SELECT  {$stmt} {$where} {$otherparams}";
		$values = array_merge($valuesWhere, $valuesOr);
		if(!$this->query($sql, $values)->error()) {
			return $this;
		}

		return false;
	}

	public function search($stmt, $whereAr = array(), $valuesWhere = array(), $whereOr = array(), $valuesOr = array(), $otherparams = null)
	{
		if (count($whereAr) !== 0 || count($whereOr) !== 0) {
			if (count($whereAr) !== 0) {
				$where = "WHERE ";
				$ct = 1;
				foreach ($whereAr as $key => $value) {
					$where .= $value.' ?';
					if ($ct < count($whereAr)) {
						$where .= " AND ";
						$ct++;
					}
				}
			}
			if (count($whereOr) !== 0) {
				$where .= " OR ";
				$cnt = 1;
				foreach ($whereOr as $key2 => $value2) {
					$where .= $value2.' ?';
					if ($cnt < count($whereOr)) {
						$where .= " AND ";
						$cnt++;
					}
				}
			}
		} else {
			$where = "";
			$value = array();
		}
		$sql = "SELECT  {$stmt} {$where} {$otherparams}";
		$values = array_merge($valuesWhere, $valuesOr);
		if(!$this->query($sql, $values)->error()) {
			return $this;
		}

		return false;
	}


	public function insert($table, $fields = array())
	{
		if (count($fields)) {
			$keys = array_keys($fields);
			$values = '';
			$x = 1;

			foreach ($fields as $field) {
				$values .= "?";
				if($x < count($fields)) {
					$values .= ', ';
				}
				$x++;
			}

			$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

			if (!$this->move($sql, $fields)->error()) {
				return true;
			}
		}
		return false;
	}

	public function update( $table, $fields, $wheres, $param='' )
	{
		$set = '';
		$where = '';
		$x = 1;
		$values = array();

		foreach ($fields as $name => $value) {
			$set .= "{$name} = ?";
			$values[$name] = $value;
			if($x < count($fields)) {
				$set .= ', ';
			}
			$x++;
		}

		foreach ($wheres as $key => $value) {
			$where .= "{$key} ?";
			$values[$key] = $value;
			if ($x < count($wheres)+1) {
				$where .= ' AND ';
			}
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE {$where} $param";

		if (!$this->move($sql, $values)->error()) {
			return true;
		}

		return false;
	}

	public function results()
	{
		if($this->_pdo !== NULL) {
			if ($this->_query->execute()) {
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
			} else {
				$this->_error = true;
			}

			return $this->_results;
		}
	}

	public function first()
	{
		if($this->_pdo !== NULL) {
			if ($this->_query->execute()) {
				$this->_results = $this->_query->fetch(PDO::FETCH_OBJ);
			} else {
				$this->_error = true;
			}

			return $this->results()[0];
		}
	}

	public function error()
	{
		return $this->_error;
	}

	public function count()
	{
		return $this->_count;
	}

	public static function close()
	{
		if (self::$_instance != '')
		{
			self::$_instance->close();
		}
	}

}
