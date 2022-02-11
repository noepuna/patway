<?php

	Namespace Core\DB;

	interface IDatabase {

		public function query( string $sql );

		public function fetch( Callable $callback );

		public function query_num_rows();

		public function fetchColumn();

		public function rowCount();

		public function lastInsertID();

		public function errors();

		public function beginTransaction();

		public function commit();

		public function rollback();

	}

?>