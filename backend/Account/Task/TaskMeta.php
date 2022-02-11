<?php

/**
 * ...
 *
 * @category	App\Account\Task
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

Class TaskMeta extends \App\AppMeta
{
    use \App\Account\Task\TTaskOps;

    /**
     * ...
     *
     * @var string
     * @access public
     */
    const EVENT_C      = 1;
    const EVENT_R      = 2;
    const EVENT_U      = 3;
    const EVENT_D      = 4;
    const EVENT_A      = 5;
    const BBS_OBSERVATION_C = 6;
    const BBS_OBSERVATION_R = 7;
    const BBS_OBSERVATION_U = 8;
    const BBS_OBSERVATION_D = 9;
    const BBS_OBSERVATION_A = 10;
    const CRISIS_MANAGEMENT_C = self::ALL_TASK['crisis_management_c'];
    const CRISIS_MANAGEMENT_R = self::ALL_TASK['crisis_management_r'];
    const CRISIS_MANAGEMENT_U = self::ALL_TASK['crisis_management_u'];
    const CRISIS_MANAGEMENT_D = self::ALL_TASK['crisis_management_d'];
    const CRISIS_MANAGEMENT_A = self::ALL_TASK['crisis_management_a'];

    /**
     * ...
     *
     * @var array
     * @access public
     */
    const ALL_TASK =
    [
        self::EVENT_C,
        self::EVENT_R,
        self::EVENT_U,
        self::EVENT_D,
        self::EVENT_A,
        self::BBS_OBSERVATION_C,
        self::BBS_OBSERVATION_R,
        self::BBS_OBSERVATION_U,
        self::BBS_OBSERVATION_D,
        self::BBS_OBSERVATION_A,
        'crisis_management_c' => 11,
        'crisis_management_r' => 12,
        'crisis_management_u' => 13,
        'crisis_management_d' => 14,
        'crisis_management_a' => 15,
    ];

    /**
     * ...
     *
     * @var string
     * @access public
     */
    const ENABLED = 1;
    const DISABLED = 0;

    /**
     * ...
     *
     * @var string
     * @access public
     */
    const CRUD_METHOD_CREATE = "create";
    const CRUD_METHOD_READ   = "read";
    const CRUD_METHOD_UPDATE = "update";

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
     * ...
     *
     * ...
     *
     * @var Array
     * @access private
     */
    protected Array $_metadata =
    [
        'crud_method' =>
        [
            'type' => "enum",
            'collection' => [ self::CRUD_METHOD_CREATE, self::CRUD_METHOD_READ, self::CRUD_METHOD_UPDATE ],
            'null-allowed' => false
        ],
        'task' =>
        [
            'id' =>
            [
                'type' => "numeric",
                'null-allowed' => false
            ],
            'disabled' =>
            [
                'type' => "boolean",
                'length-max' => 32,
                'null-allowed' => true
            ]
        ]
    ];





    /**
     *  ...
     *
     *  @access public
     *  @param ...
     *  @return ...
     *  @since Method available since Beta 1.0.0
     *
     *  ...
     *  ...
     *  ...
     *  ...
     */
    public function __construct( IConfig $CONFIG )
    {
        $this->_config = $CONFIG;
    }





    /**
     *  ...
     *
     *  @access public
     *  @param ...
     *  @return ...
     *  @since Method available since Beta 1.0.0
     *
     *  ...
     *  ...
     *  ...
     *  ...
     */
    protected function _setSpecialProperty( $SETTINGS )
    {
        Parent::_setSpecialProperty($SETTINGS);

        $iConfig = $this->_config;
        $db = $iConfig->getDbAdapter();
        $name = $SETTINGS->getName();
        $field = $SETTINGS->getField();
        $alias = $SETTINGS->getAlias() ?? $name;
        $newValue = $SETTINGS->getNewValue();

        switch( $field )
        {
            case "task":
                switch( $name )
                {
                    case "id":
                        if( !$this->t_TaskOps_isAvailable($iConfig, $newValue) )
                        {
                            $this->setLastError("{$alias} is not valid");
                        }
                    break;
                }
            break;
        }
    }
}

?>