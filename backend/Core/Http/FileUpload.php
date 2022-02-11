<?php

	/**
	 * ...
	 *
	 * @category	Core\HTTP
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */

	namespace Core\HTTP;





	class FileUpload
	{
		use \Core\Util\TUtilOps;

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<any>;
	     * @access private
	     */
	    private Array $_data = [];





		public function __construct( Array $DATA )
		{
			$this->_data = $DATA ?? [];
		}





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getFilename() : string
	    {
	    	return $this->_data['name'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getType() : string
	    {
	    	return $this->_data['type'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getFilepath() : string
	    {
	    	return $this->_data['tmp_name'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getError() : int
	    {
	    	return $this->_data['error'];
	    }





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getSize() : int
	    {
	    	return $this->_data['size'];
	    }
	}

?>