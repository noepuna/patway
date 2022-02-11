<?php

    /**
     * ...
     *
     * @category	App\Office
     * @package    
     * @author     	Original Author <solanoreynan@gmail.com>
     * @copyright  
     * @license    
     * @version    	Beta 1.0.0
     * @link       
     * @see
     * @since      	Class available since Beta 1.0.0
     */

    namespace App\Office;

    use App\IConfig;
    use App\Office\OfficeMeta;
    use App\Account\IAccount;





    Class Office
    {
        use \Core\Util\TUtilOps;

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
            'office' => []
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
        public function __construct( IConfig $CONFIG, OfficeMeta $META )
        {
            $d = $META->office;

            if( $META->require("crud_method", ["office", "creator", "account"]) && OfficeMeta::CRUD_METHOD_UPDATE === $META->crud_method )
            {
                $this->_prop['office'] =
                [
                    'creator' => $d['creator'],
                    'account' => $d['account'],
                    'task' => $d['task'] ?? null,
                    'disabled' => $d['disabled'] ?? null

                ] + $this->_prop['office'];
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
        public static function createInstance( IConfig $CONFIG, OfficeMeta $META )
        {new self($CONFIG, $META);
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
        public function getCreator() : IAccount
        {
            return $this->_prop['office']['creator'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function getAccount() : string
        {
            return $this->_prop['office']['account'];
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function update( Array $changes = [] ) :? Array
        {
            $iConfig = $this->_config;

            $meta =
            [
                'crud_method' => OfficeMeta::CRUD_METHOD_UPDATE,
                'office' =>
                [
                    'creator' => $this->_prop['office']['creator'],
                    'account' => $this->_prop['office']['account'],
                    'task' => $changes['task'] ?? []
                ]
            ];

            if( is_array($iMeta = OfficeMeta::createInstance( $iConfig, $meta )) )
            {
                return $iMeta;
            }

            $db = $iConfig->getDbAdapter();
            $existingTransaction = $db->beginTransaction();

            $tasks = $iMeta->office['task'] ?? [];

            if( $tasks && count($tasks) )
            {
                foreach( $tasks as $key => $iTask )
                {
                    $accountIdKey = "auth" . $key;
                    $taskIdKey = "task" . $key;
                    $taskStatusKey = "status" . $key;
                    $param['task'][$accountIdKey] = $this->_prop['office']['account'];
                    $param['task'][$taskIdKey] = $iTask->getId();
                    $param['task'][$taskStatusKey] = $iTask->disabled() ?1 :0;
                    $taskValStr[] = "(:{$accountIdKey}, :{$taskIdKey}, :{$taskStatusKey})";
                }

                $db->query
                ("
                    INSERT INTO `account_tasks`(`auth_fk`, `task_fk`, `disabled`)
                    VALUES" . implode(", ", $taskValStr) . " ON DUPLICATE KEY UPDATE `disabled` = VALUES(`disabled`)",

                    $param['task']
                );
            }

            $existingTransaction && $db->commit();

            return null;
        }
    }

?>