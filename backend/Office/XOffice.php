<?php

    /**
     * Abstract class for Office Account
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
    use App\Account\AccountMeta;
    //use App\Account\AccountAddressMeta;
    //use App\Account\AccountContactMeta;





    Abstract Class XOffice extends \App\Account\Account implements \App\Office\IOffice
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
        public function __construct( IConfig $CONFIG, AccountMeta $META )
        {
            $this->_config = $CONFIG;

            Parent::__construct($CONFIG, $META);
        }





        /**
         * ...
         *
         * @access public
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         */
        public function create() :? string
        {
            return Parent::create();
        }
    }

?>