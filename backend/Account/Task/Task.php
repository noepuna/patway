<?php

/**
 * All Business Logic for a Account Task.
 *
 * @category	App\Lead\Property
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
namespace App\Account\Task;

use App\IConfig;
use App\Account\IAccount;
use App\Account\Task\TaskMeta;





class Task implements \App\Account\Task\ITask
{
	use \Core\Util\TUtilOps;

    /**
     * ...
     *
     * ...
     *
     * @var App\IConfig;
     * @access private
     */
    private $_config;

    /**
     * ...
     *
     * ...
     *
     * @var Array
     * @access private
     */
   	private $_prop =
   	[
   		'crud_method' => null,
   		'id' => null,
   		'disabled' => false
   	];

    /**
     * ...
     *
     * ...
     *
     * @var Array
     * @access private
     */
    private $_propertySegment = [ 'basic' => false, 'lead_property' => false ];





	/**
	 *	...
	 *
	 *	@access public
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 *	...
	 *	...
	 *	...
	 */
	public function __construct( IConfig $CONFIG, TaskMeta $META )
	{
		$d = $META->task;

		if( $META->require("crud_method", [ "task", "id" ]) && $META::CRUD_METHOD_CREATE === $META->crud_method )
		{
			$this->_prop =
			[
				'crud_method' => $META->crud_method,
				'id' => $d['id'],
                'disabled' => @$d['disabled'] ?? false

			] + $this->_prop;
		}
        else if( $META->require("crud_method", [ "task", "id" ]) && $META::CRUD_METHOD_READ === $META->crud_method )
        {
            $this->_prop =
            [
                'crud_method' => $META->crud_method,
                'id' => $d['id'],
                'disabled' => @$d['disabled'] ?? false

            ] + $this->_prop;
        }

		else
		{
			throw new \Exception("insufficient meta", 1);
		}

		$this->_config = $CONFIG;
	}





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
	public static function createInstance( IConfig $CONFIG, TaskMeta $META )
	{
		try
		{
			return new static($CONFIG, $META);
		}
		catch( \Exception $e )
		{
			return false;
		}
	}





    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getId() : ?int
    {
    	return $this->_prop['id'];
    }





    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function disabled() : bool
    {
        return $this->_prop['disabled'];
    }
}

?>