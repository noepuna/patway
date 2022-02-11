<?php

	/**
	 * ...
	 *
	 * @category	App
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */
	namespace App;

	use Core\DB\PDOMySQL;
	use Resource\EmailServer;
	use Resource\ReturnMsg;

	class Config extends \App\IConfig
	{
	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var int
	     * @access private
	     */
	    private $_currentTime;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var \Resource\IDatabase
	     * @access private
	     */
	    private $_database;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var string
	     * @access private
	     */
		private static $base = "/patway";

		/**
		 *	...
		 *
		 * @access public
		 * @static
		 * @param
		 * @return
		 * @since Method available since Beta 1.0.0
		 *
		 *	...
		 */
		function __construct()
		{
			$this->_currentTime = time();

			$this->_setupDatabase();
		}

		private function _setupDatabase() {

			# establish a connection to the database

			$db = new PDOMySQL();
 
			$db->connect('localhost', 'root', '');
			$db->selectDB('patway');

			$this->_database = $db;

		}

		public static function base() : string {
			return self::$base;
		}

		public static function baseURL() : string 
		{
			return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . self::$base;
		}

		public static function emailServer() : EmailServer
		{
			return new EmailServer;
		}

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
		public function getDocumentRoot() : string
		{
			return $_SERVER['DOCUMENT_ROOT'] . "/" . getenv("BASE_PATH");
		}

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
		public function getBaseURL() : string
		{
			return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/" . getenv("BASE_PATH");
		}

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
		public function getCurrentTime() : int
		{
			return $this->_currentTime;
		}

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	     public function getDbAdapter() : \Core\DB\IDatabase
	     {
	     	return $this->_database;
	     }

	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	     public function getMaxUploadSize() : int
	     {
		  /*static $max_size = -1;

		  if ($max_size < 0) {
		    // Start with post_max_size.
		    $post_max_size = parse_size(ini_get('post_max_size'));
		    if ($post_max_size > 0) {
		      $max_size = $post_max_size;
		    }

		    // If upload_max_size is less, then reduce. Except if upload_max_size is
		    // zero, which indicates no limit.
		    $upload_max = parse_size(ini_get('upload_max_filesize'));
		    if ($upload_max > 0 && $upload_max < $max_size) {
		      $max_size = $upload_max;
		    }
		  }
		  return $max_size;*/
	     	$size = ini_get("upload_max_filesize");

	     	//
	     	// remove the non-unit characters from the size
	     	//
  			$unit = preg_replace('/[^bkmgtpezy]/i', '', $size);

  			//
  			// Remove the non-numeric characters from the size
  			//
			$size = preg_replace('/[^0-9\.]/', '', $size);

			//
			// find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by
			//
  			if ($unit)
  			{
    			return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
 			}
			else
			{
				return round($size);
 			}
	     }

		/**
		 *	...
		 *
		 * @param ...
		 * @return
		 *
		 * @access public
		 * @static
		 * @since Method available since Beta 1.0.0
	     */
	    public static function encrypt( Array $DATA ) : ?string
	    {
			$key = "1f8ed5489bee647c4d82f232204de038";
			$cipher = "aes-128-gcm";
			$initializationVector = "0";

			$serializeArray = serialize($DATA);
			$ciphertext = openssl_encrypt($serializeArray, $cipher, $key, $options=0, $initializationVector, $authenticationTag, null, 2);

			return base64_encode($authenticationTag . $ciphertext);
	    }

		/**
		 *	...
		 *
		 * @param ...
		 * @return ReturnMsg
		 *
		 * @access public
		 * @static
		 * @since Method available since Beta 1.0.0
		 */
		public static function decrypt( string $TOKEN )
		{
			$key = "1f8ed5489bee647c4d82f232204de038";
			$cipher = "aes-128-gcm";
			$initializationVector = "0";

			$TOKEN = base64_decode($TOKEN);
			$text = openssl_decrypt
			(
				substr($TOKEN, 2),
				$cipher,
				$key,
				$options=0,
				$initializationVector,
				substr($TOKEN, 0, 2)
			);

			if($text)
			{
				return unserialize($text);
			}

			return NULL;
		}
	}
?>