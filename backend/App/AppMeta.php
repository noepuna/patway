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

	Class AppMeta extends \Core\Model\ModelMeta
	{
	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<string>;
	     * @access private
	     */
	    public const MUTATION_FLAG = [ 'remove_null' => 1 ];

	    /**
	     * ...
	     *
	     * ...
	     *
	     * @var Array<string>;
	     * @access private
	     */
	    public const REMOVE_NULL = self::MUTATION_FLAG['remove_null'];

	    /**
	     *  ...
	     *
	     *  @access public
	     *	@static
	     *  @param ...
	     *  @return ...
	     *  @since Method available since Beta 1.0.0
	     *
	     *  ...
	     *  ...
	     *  ...
	     *  ...
	     */
	    public static function createInstance( IConfig $CONFIG, Array $META, string ...$FLAGS )
	    {
	    	if( $FLAGS )
	    	{
	    		if( in_array(self::REMOVE_NULL, $FLAGS) )
	    		{
	    			$META = self::searchAndRemove($META, null);
	    		}
	    	}

	        $iMeta = new static($CONFIG);

	        foreach( $META as $property => $value )
	        {
	            $iMeta->$property = $value;
	        }

	        $errors = $iMeta->getErrors();

	        return empty($errors) ?$iMeta :$errors;
	    }
	}

?>