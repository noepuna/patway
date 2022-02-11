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

	use Core\HTTP\FileUpload;
	use App\File\FileMeta;





	class PostRequest implements \Core\HTTP\IRequest
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

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<any>;
	     * @access private
	     */
	    private $_files = [];





		public function __construct( Array $DATA = NULL, Array $FILES = NULL )
		{
			$this->_data = $DATA ?? [];
			$this->_files = $FILES ?? [];
		}





	    /**
	     * ...
	     *
	     * @access public
	     * @param
	     * @return
	     * @since Method available since Beta 1.0.0
	     */
	    public function getFileUpload() : Array
	    {
	    	$uploads = [];
	    	//
	    	// transpose file post data
	    	//
	    	$transposeUploadDataFn = function( $currentArray, &$currentDimension, $uploadDimension ) use ( &$transposeUploadDataFn )
	    	{
	    		if( is_scalar($currentArray) )
	    		{
	    			$currentDimension[$uploadDimension] = $currentArray;
	    		}
	    		else
	    		{
		    		foreach( $currentArray as $key => $data )
		    		{
		    			$transposeUploadDataFn( $data, $currentDimension[$key], $uploadDimension );

		    		}
	    		}
	    	};

    		foreach( $this->_files as $primaryIndex => $fileUpload )
    		{
    			foreach( $fileUpload as $fileSegment => $value )
    			{
    				$transposeUploadDataFn( $fileUpload[$fileSegment], $uploads[$primaryIndex], $fileSegment );
    			}
    		}
    		//
    		// convert data into FileUpload instance
    		//
    		$fileBuilderFn = function( &$fileUpload ) use ( &$fileBuilderFn )
    		{
		    	foreach( $fileUpload as $uploadKey => $files )
		    	{
		    		foreach( $files as $fileKey => $fileOrData )
		    		{
		    			if( is_scalar($fileOrData) )
		    			{
		    				$filedata =
		    				[
					            'name' => $files['name'],
					            'type' => $files['type'],
					            'tmp_name' => $files['tmp_name'],
					            'error' => $files['error'],
					            'size' => $files['size']
		    				];

		    				$fileUpload[$uploadKey] = new FileUpload($filedata);
		    				break;
		    			}
		    			else
		    			{
		    				$fileBuilderFn($fileUpload[$uploadKey]);
		    				continue;
		    			}
		    		}
		    	}
    		};

			$fileBuilderFn($uploads);

	    	return $uploads;
	    }
	}

?>