<?php

	namespace Resource;

	class Rest
	{
		protected $request;
		protected $serviceName;
		protected $param;

		public function _construct()
		{
			//
		}

		public function validateRequest( $request )
		{
			
		}

		public function processApi()
		{
			//
		}

		public function validateParameter( $fieldName, $value, $datatye, $required )
		{
			//
		}

		public function throwError( $code, $message )
		{
			header("content-type: application/json");

			$errorMsg = json_encode(['error' => ['status'=>$code, 'message' => $message] ]);

			echo $errorMsg; exit;
		}

		public function returnResponse()
		{
			//
		}
	}

?>