<?php

	namespace Core\datatype;

	class ArrayString
	{
		use \Core\Util\TUtilOps;

		private $_value = null;

		function __construct( $VALUE = null )
		{

			if( false === $this->_createInstance($VALUE) )
			{
				throw new \Exception("Expects a string array", 1);
			}

		}

		public function __get( string $NAME ){
			$value;

			switch( $NAME )
			{
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

		public function unshift( string $NEW_ELEMENT )
		{
			array_unshift($this->_value, $NEW_ELEMENT);
		}

		private function _createInstance( $VALUES )
		{
			if( true === is_array($VALUES) )
			{

				foreach( $VALUES as $key => $VALUE ){
					if( false === is_string($VALUE) ){
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

		public static function createInstance( $VALUES )
		{
			try {
				return new static($VALUES);
			} catch( \Exception $e){
				return false;
			}
		}

	}

?>