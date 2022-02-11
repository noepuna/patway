<?php

	Namespace Core\DB;

	use \PDO;

	class PDOMySQL implements \Core\DB\IDatabase
	{
		private $_conn;

		private $_host;

		private $_username;

		private $_password;

		private $_qry;

		public function __construct() {}

		public function _connect( $host, $username, $password ) {

			$this->_host = $host;

			$this->_username = $username;

			$this->_password = $password;

		}

		public function _selectDB( $databaseName ) {

			$settings = array( PDO::ATTR_EMULATE_PREPARES => false );

			$this->_conn = new PDO('mysql:host=' . $this->_host . ';dbname=' . $databaseName, $this->_username, $this->_password, $settings);

		}

		public function connect( $host, $username, $password ) {

			$this->_host = $host;

			$this->_username = $username;

			$this->_password = $password;

		}

		public function selectDB( $databaseName ) {

			$settings = array( PDO::ATTR_EMULATE_PREPARES => false );

			$this->_conn = new PDO('mysql:host=' . $this->_host . ';dbname=' . $databaseName, $this->_username, $this->_password, $settings);

		}

		public function query( string $sql, Array $DATA = null )
		{
    		$qry = $this->_qry = $this->_conn->prepare($sql);

    		foreach( ($DATA)?$DATA:[] as $key => $value )
    		{
    			$param =
    			[
    				'datatype' => \PDO::PARAM_STR,
    				'value' => $value
    			];

    			if( is_int($value) )
    			{
    				$param['datatype'] = \PDO::PARAM_INT;
    			}

    			$qry->bindValue($key, $param['value'], $param['datatype']);
    		}

    		if( $qry )
    		{
    			if( false === $qry->execute() )
    			{
    				echo $sql;
    				echo "<pre>" . print_r($this->errors(), 1) . "</pre>";
    				die("<h1>Database Query failed</h1>");
    			}
    		}
    		else
    		{
    			echo $sql;
    			echo "<pre>" . print_r($this->errors(), 1) . "</pre>";
    			die("<h1>Invalid query<h1>");
    		}

		}

		public function fetch( Callable $callback = null ){

    		//$result = $this->_qry->setFetchMode(PDO::FETCH_ASSOC);

    		$rows = array();

			while( $row = $this->_qry->fetch(PDO::FETCH_ASSOC) ){

				if( null !== $callback ) {

					$callback($row);

				}

				$rows[] = $row;

			}

			return $rows;

		}

		public function query_num_rows() {

			$qry = $this->_conn->query("SELECT FOUND_ROWS()");

			return (int)$qry->fetchColumn();

		}


		public function fetchColumn( $column = 0 ) {

			return $this->_qry->fetchColumn($column);

		}

		public function rowCount()
		{
			return $this->_qry->rowCount();
		}

		public function lastInsertId() {

			return $this->_conn->lastInsertId();

		}

		public function beginTransaction()
		{
			if( $this->_conn->inTransaction() )
			{
				return false;
			}
			else
			{
				return $this->_conn->beginTransaction();
			}
		}

		public function commit() {

			$this->_conn->commit();

		}

		public function rollback() {

			return $this->_conn->rollback();

		}

		public function errors() {

			$handle = $this->_conn;

			if( $this->_qry ) {

				$handle = $this->_qry;

			}

			return $handle->errorInfo();

		}

	}

?>