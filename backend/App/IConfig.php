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

	use Resource\EmailServer;

	abstract class IConfig
	{
		abstract public static function base() : string;
		abstract public static function baseURL() : string;
		abstract public static function emailServer() : EmailServer;

		abstract public function getDocumentRoot() : string;
		abstract public function getBaseURL() : string;
		abstract public function getCurrentTime() : int;
		abstract public function getDbAdapter() : \Core\DB\IDatabase;
		abstract public function getMaxUploadSize() : int;
	}

?>