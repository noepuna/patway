<?php

	namespace Core\datatype;

	class ArrayNullNumeric
	{
		use \Resource\TUtilOps;

		private static $_value = null;

		function __construct( $VALUE ){

			if( false === self::_createFromArray($VALUE) ){
				throw new \Exception("Expects a numeric array or null", 1);
			}

		}

		public function __get( $NAME ){
			$value;

			switch( $NAME ) {
				case "value":
					$value = self::$_value;
					break;
				
				default:
					break;
			}

			return $value;
		}

		private static function _createFromArray( $VALUES ){

			if( true === is_array($VALUES) ){

				foreach( $VALUES as $key => $VALUE ){
					if( false === is_numeric($VALUE) ){
						return false;
						break;
					}
				}

				self::$_value = $VALUES;

			} else if( true === is_null($VALUES) ){

				$THIS->_value = $VALUES;

			} else {

				return false;

			}

			return true;
		}

		public static function createFromArray( Array $VALUES ){
			if( true === self::_createFromArray($VALUES) ){
				return new ArrayNullNumeric($VALUES);
			} else {
				return false;
			}
		}

	}

?>