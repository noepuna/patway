<?php

/**
 * All Business Logic for Staff Department Entry
 *
 * @category	App\Office\Staff
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
namespace App\Office\Staff;

use App\IConfig;
use App\Office\Staff\DepartmentEntryMeta;





class DepartmentEntry implements \App\Office\Staff\IDepartmentEntry
{
	use \Core\Util\TUtilOps;





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
   		'department' => null,
        'deleted' => false
   	];





    /**
     * ...
     *
     * ...
     *
     * @var Array
     * @access private
     */
    private $_propertySegment = [];





    /**
     * ...
     *
     * ...
     *
     * @var Array
     * @access private
     */
    private const _META_REQS =
    [
        'create' =>
        [
            "crud_method",
            [
                "department_entry",
                "department"
            ]
        ]
    ];





    /**
     * ...
     *
     * ...
     *
     * @var App\IConfig;
     * @access private
     */
    private IConfig $_config;





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
	public function __construct( IConfig $CONFIG, DepartmentEntryMeta $META )
	{
		$d = $META->department_entry;

        if( $META->require(...self::_META_REQS['create']) && $META::CRUD_METHOD_CREATE === $META->crud_method )
		{
			$this->_prop =
			[
				'crud_method' => $META->crud_method,
                'id'        => $d['department']->getId(),
                'deleted'    => $d['deleted'] ?? $this->_prop['deleted']

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
	public static function createInstance( IConfig $CONFIG, DepartmentEntryMeta $META )
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
    public function getId() : int
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
    public function isDeleted() : bool
    {
        return $this->_prop['deleted'];
    }
}

?>