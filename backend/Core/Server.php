<?php

 	namespace Resource;

 	use Core\HTTP\PostRequest;





	class Server
	{
		public $post = null;
		public $get  = null;

		public function __construct()
		{
			$this->_handlePreFlightCORS();
		  	//$content 	 = file_get_contents('php://input');
			$contentType = @$_SERVER['CONTENT_TYPE'];

			if( false === isset( $_GET ) )
			{
				$_GET = null;
			}

			switch ($contentType) {

				case 'application/json':
					$content 	 = file_get_contents('php://input');
					$this->post = json_decode($content, true);
					$this->get  = $_GET;
					break;

				case 'application/x-www-form-urlencoded':
					if( isset($_POST['param']['pagetoken']) )
					{
						$_POST['param']['pagetoken'] = urldecode($_POST['param']['pagetoken']);
					}

					$this->get  	= $_GET;
					$this->post 	= $_POST;
					$this->files 	= $_FILES;					
					break;
				
				default:
					$this->get  	= $_GET;
					$this->post 	= $_POST;
					$this->files 	= $_FILES;
					break;
			}

			unset($_GET);
			unset($_POST);
			unset($_FILES);
		}

		public function getPostRequest() : PostRequest
		{
			return new PostRequest( $this->post, $this->files );
		}

		private function _handlePreFlightCORS()
		{
			header("Access-Control-Allow-Origin: http://localhost:4200");
			header("Access-Control-Allow-Credentials: true");
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
			// respond to preflights
			if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
			{
				// return only the headers and not the content
				// only allow CORS if we're doing a GET - i.e. no saving for now.
				//if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) &&
				//$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'GET')
				//{
				
				header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, Auth-Login-Password, Auth-Login-Re-Password, Auth-Login-Token");
				header("Access-Control-Expose-Headers: MCOR-Pagination-Token, MCOR-Pagination-Count");
				header('Access-Control-Max-Age: 1000');
				//header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept');
				//}
				exit;
			}
		}
	}

?>