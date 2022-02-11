<?php

	namespace Core\Datatype;

	class ArrayNumeric
	{
		use \Resource\TUtilOps;

		private $_value = null;

		function __construct( $VALUE = null ){

			if( false === $this->_createFromArray($VALUE) ){
				throw new \Exception("Expects a numeric array or null", 1);
			}

		}

		public function __get( string $NAME ){
			$value;

			switch( $NAME ) {
				case "value":
					$value = $this->_value;
					break;
				
				default:
					throw new \Exception("Undefined property {$NAME}", 1);
					break;
			}

			return $value;
		}

		public function isEmpty() : bool {
			return count($this->_value) <= 0;
		}

		private function _createFromArray( $VALUES ){
			if( true === is_array($VALUES) ){

				foreach( $VALUES as $key => $VALUE ){
					if( false === is_numeric($VALUE) ){
						return false;
						break;
					}
				}

				$this->_value = $VALUES;

			} else if( true === is_null($VALUES) ){

				$this->_value = array();

			} else {

				return false;

			}

			return true;
		}

		public static function createInstance( $VALUES ){
			try {
				return new ArrayNumeric($VALUES);
			} catch( \Exception $e){
				return false;
			}
		}

		public static function createFromArray( $VALUES ){
			try {
				return new ArrayNumeric($VALUES);
			} catch( \Exception $e){
				return false;
			}
		}

	}

?>