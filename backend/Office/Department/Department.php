<?php

    /**
     * ...
     *
     * @category	App\Office\Department
     * @package    
     * @author     	Original Author <solanoreynan@gmail.com>
     * @copyright  
     * @license    
     * @version    	Beta 1.0.0
     * @link       
     * @see
     * @since      	Class available since Beta 1.0.0
     */

    namespace App\Office\Department;

    use App\IConfig,
        App\Office\Department\DepartmentMeta;





    Class Department
    {
        use \Core\Util\TUtilOps,
            \App\Office\Department\TOfficeDepartmentOps;

        /**
         * ...
         *
         * ...
         *
         * @var Array<any>
         * @access private
         */
        private Array $_prop =
        [
            'office_department' =>
            [
                'id' => null,
                'name' => null,
                'description' => null,
                'admin' => null,
                'enabled' => null
            ]
        ];





        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private const _CTOR_REQS =
        [
            'create' =>
            [
                "crud_method",
                [
                    "office_department",
                    "name",
                    "admin"
                ]
            ],
            'read' =>
            [
                "crud_method",
                [
                    "office_department",
                    "id",
                    "admin"
                ]
            ],
            'update' =>
            [
                "crud_method",
                [
                    "office_department",
                    "id",
                    "admin"
                ]
            ]
        ];





        /**
         * ...
         *
         * ...
         *
         * @var Array
         * @access private
         */
        private $_propertySegment = [ 'department' => false ];





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
        public function __construct( IConfig $CONFIG, DepartmentMeta $META )
        {
            $d = $META->office_department;

            if( $META->require(...self::_CTOR_REQS['create']) && $META->crud_method === $META::CRUD_METHOD_CREATE )
            {
                $this->_prop['office_department'] =
                [
                    'name' => $d['name'],
                    'description' => $d['description'] ?? $this->_prop['office_department']['description'],
                    'admin' => $d['admin']->getId(),
                    'enabled' => $d['enabled'] ?? true

                ] + $this->_prop['office_department'];
            }
            else if( $META->require(...self::_CTOR_REQS['read']) && $META->crud_method === $META::CRUD_METHOD_READ )
            {
                $this->_prop['office_department'] =
                [
                    'id' => $d['id']

                ] + $this->_prop['office_department'];
            }
            else if( $META->require(...self::_CTOR_REQS['update']) && $META->crud_method === $META::CRUD_METHOD_UPDATE )
            {
                $this->_prop['office_department'] =
                [
                    'id' => $d['id']

                ] + $this->_prop['office_department'];
            }
            else
            {
                throw new \Exception("Invalid meta", 1);
            }

            $this->_prop['crud_method'] = $META->crud_method;

            $this->_config = $CONFIG;
        }





        /**
         *  ...
         *
         * @access public
         * @static
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        public static function createInstance( IConfig $CONFIG, DepartmentMeta $META )
        {
            try
            {
                return new self($CONFIG, $META);
            }
            catch( \Exception $EXCEPTION )
            {
                return null;
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
            return $this->_prop['office_department']['id'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getName() : string
        {
            $this->_requireDepartmentSegment();

            return $this->_prop['office_department']['name'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getDescription() :? string
        {
            $this->_requireDepartmentSegment();

            return $this->_prop['office_department']['description'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getAdmin() : string
        {
            $this->_requireDepartmentSegment();

            return $this->_prop['office_department']['admin'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function isEnabled() : bool
        {
            $this->_requireDepartmentSegment();

            return $this->_prop['office_department']['enabled'];
        }





        /**
         *  ...
         *
         * @access private
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        private function _requireDepartmentSegment()
        {
            if( !$this->_propertySegment['department'] && $this->getId() )
            {
                $d = self::t_OfficeDepartmentOps_getInfoById($this->_config, $this->getId());

                $this->_prop['office_department'] =
                [
                    'name' => $d['name'],
                    'description' => $d['description'],
                    'admin' => $d['admin'],
                    'enabled' => $d['enabled']

                ] + $this->_prop['office_department'];

                $this->_propertySegment['department'] = true;
            }
        }




        /**
         *  ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        public function create() : ?string
        {
            $params =
            [
                'office_department' =>
                [
                    'name' => $this->getname(),
                    'description' => $this->getDescription(),
                    'admin_fk' => $this->getAdmin(),
                    'enabled' => $this->isEnabled()
                ]
            ];

            $iDb = $this->_config->getDbAdapter();
            $dbTransaction = $iDb->beginTransaction();

            $transcFieldsQryFrag = [];
            $transcValuesQryFrag = [];

            foreach( $params['office_department'] as $column => $value )
            {
                $transcFieldsQryFrag[$column] = "`{$column}`";
                $transcValuesQryFrag[$column] = ":{$column}";
            }

            $iDb->query
            ("
                INSERT INTO `office_department`(" . implode(", ", $transcFieldsQryFrag) . ")
                VALUES(" . implode(", ", $transcValuesQryFrag) . ")",

                $params['office_department']
            );

            $this->_prop['office_department']['id'] = $iDb->lastInsertId();

            $dbTransaction && $this->getId() && $iDb->commit();

            return $this->getId();
        }





        /**
         *  ...
         *
         * @access public
         * @static
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        public function update( DepartmentMeta $META ) : ?bool
        {
            # requiring crud_method, id and admin is essential in validating other office department properties

            $updateRequirements =
            [
                "crud_method",
                [
                    "office_department", "id", "admin"
                ]
            ];

            # crud_method must be update

            if( $META->require(...$updateRequirements) && $META->crud_method === DepartmentMeta::CRUD_METHOD_UPDATE )
            {
                # id must be equals the value during the class construction

                $officeDepartment = $META->office_department;
                $departmentId = $officeDepartment['id'] ?? null;

                if( (string)$this->getId() !== (string)$departmentId )
                {
                    return false;
                }
            }
            else
            {
                return false;
            };

            # certain properties can only be made changable
            #
            # synopsis: [ database table column name => property name ]

            $allowedProperties['office_department'] =
            [
                [ "name", "name"],
                [ "description", "description"],
                [ "enabled", "enabled"],
            ];

            $param = $fieldSetQry = [];

            foreach($allowedProperties as $segment => $props)
            {
                if( !($segmentProp = $META->$segment) )
                {
                    continue;
                }

                foreach( $props as [$column, $name] )
                {
                    if( array_key_exists($name, $segmentProp) )
                    {
                        $param[$segment][$name] = $segmentProp[$name];
                        $fieldSetQry[$segment][] = "`{$column}` = :{$name}";
                    }
                }
            }

            # empty properties

            if( !$param )
            {
                return null;
            }

            # save the changes

            $iDb = $this->_config->getDbAdapter();
            $dbTransaction = $iDb->beginTransaction();

            # save the detail changes

            if( $param['office_department'] ?? null )
            {
                $param['office_department']['uid'] = $this->getId();
                $param['office_department']['admin'] = $this->getAdmin();

                $departmentSetQry = implode(", ", $fieldSetQry['office_department']);

                $iDb->query
                ("
                    UPDATE `office_department` SET {$departmentSetQry}
                    WHERE `uid` = :uid AND `admin_fk` = :admin",

                    $param["office_department"]
                );

                $this->_propertySegment['department'] = false;
            }

            $dbTransaction && $iDb->commit();

            return true;
        }
    }

?>