<?php

	namespace Resource;
	
	class APIResponse
	{
	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var int;
	     * @access public
	     */
	    const INTERNAL_ERROR = 500;
	    const APPLICATION_ERROR = 400;
	    const SUCCESS = 200;

		private $_errors = [];
		
		public $data;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var
	     * @access private
	     */
	    public $code = APIResponse::SUCCESS;
		
		public function addError( $MESSAGE, ...$ERROR_NAME ) : void
		{
			$currentErrorDimension = &$this->_errors;

			foreach( $ERROR_NAME as $key => $name )
			{
				if( -1 == $name )
				{
					$currentErrorDimension = &$currentErrorDimension[];
					break;
				}

				if( false === isset($currentErrorDimension[$name]) )
				{
					$currentErrorDimension = &$currentErrorDimension[$name];
					$currentErrorDimension = array();
				}
				else
				{
					$currentErrorDimension = &$currentErrorDimension[$name];
				}
			}

			$errorMsgRegisterFn = function( $MESSAGE, &$REGISTER ) use (&$errorMsgRegisterFn)
			{
				if( true == is_array($MESSAGE) )
				{
					foreach( $MESSAGE as $currentKey => $message )
					{
						$errorMsgRegisterFn($message, $REGISTER[$currentKey]);
					}
				}
				else
				{
					$REGISTER = $MESSAGE;
				}
			};

			$errorMsgRegisterFn($MESSAGE, $currentErrorDimension);
		}
		
		public function hasError( string $NAME = NULL ) : bool
		{
			if( $NAME ){
				return isset($this->_errors[$NAME]);
			} else {
				return count($this->_errors) > 0;
			}
		}

		public function setHeader()
		{	
			//	
		}
		
		public function build( string $FORMAT ) : string
		{
			$response = array( 'status' => $this->code );

			if( $this->hasError() )
			{
				$response['error'] = $this->_errors;
			}

			if( true === is_array($this->data) )
			{
				$response = array_merge($response, $this->data);
			}			
			
			/*if( false === $this->hasError() )
			{
				if( true === is_array($this->data) )
				{
					$response = array_merge($response, $this->data);
				}
			}
			else
			{
				$response['error'] = $this->_errors;
			}*/

			switch($FORMAT)
			{
				case 'array':
					return "<pre>" . print_r($response, 1) . "</pre>";
				default:
					return json_encode($response);
			}
		}

		public function toArray() : Array
		{
			$response = array();
			
			if( false === $this->hasError() ) {
				
				$response['status'] = 0;
				
				if(true === is_array($this->data) ) {
					$response = array_merge( $response, $this->data );
				}
			
			} else {
			
				$response = array( 'status' => 1, 'error' => $this->_errors );
			
			}
		
			return $response;
		}
	}

?>