<?php

    /**
     * ...
     *
     * @category    App\Messaging\HashTag
     * @package    
     * @author      Original Author <solanoreynan@gmail.com>
     * @copyright  
     * @license    
     * @version     Beta 1.0.0
     * @link       
     * @see
     * @since       Class available since Beta 1.0.0
     */
    namespace App\Messaging\HashTag;

    use App\IConfig,
        App\Messaging\HashTag\HashTagMeta;





    class HashTag extends \App\HashTag\HashTag implements \App\Messaging\HashTag\IHashTag
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
            'message_hashtag' =>
            [
                'sent_message' => null,
                'deleted' => false
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
        private $_propertySegment = [ 'hashtag' => false ];





        /**
         * ...
         *
         * ...
         *
         * @var Array<any>
         * @access private
         */
        private const _CTOR_REQS =
        [
            'create' => [ [ "message_hashtag", "sent_message" ] ]
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
         * @access public
         * @static
         * @param
         * @return
         * @since Method available since Beta 1.0.0
         *
         *  ...
         */
        public function __construct( IConfig $CONFIG, HashTagMeta $META )
        {
            Parent::__construct( $CONFIG, $META );

            $h = $META->message_hashtag;

            if( $META->require( ...self::_CTOR_REQS['create'] ) )
            {
                $this->_prop['message_hashtag'] = 
                [
                    'sent_message' => $h['sent_message']->getId(),
                    'deleted' => $h['deleted'] ?? $this->isDeleted()

                ] + $this->_prop['message_hashtag'];
            }
            else
            {
                throw new \Exception("Invalid meta", 1);
            }

            $this->_prop =
            [
                'crud_method' => $META->crud_method

            ] + $this->_prop;

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
        public static function createInstance( IConfig $CONFIG, \App\HashTag\HashTagMeta $META )
        {
            assert( $META instanceof HashTagMeta );

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
        public function getSentMessage() : string
        {
            return $this->_prop['message_hashtag']['sent_message'];
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
            return $this->_prop['message_hashtag']['deleted'];
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
            $hashtagId = Parent::create();

            $iConfig = $this->_config;

            $iDb = $iConfig->getDbAdapter();
            $dbTransaction = $iDb->beginTransaction();

            $param['msg_hashtag'] =
            [
                'message' => $this->getSentMessage(),
                'hashtag' => $hashtagId,
                'deleted' => $this->isDeleted()
            ];

            $iDb->query
            ("
                INSERT INTO `message_hash_tags`(`message_fk`, `hash_tag_fk`, `deleted`)
                VALUES(:message, :hashtag, :deleted)
                ON DUPLICATE KEY UPDATE `deleted` = VALUES(`deleted`)",

                $param['msg_hashtag']
            );

            $dbTransaction && $iDb->commit();

            return true;
        }
    }

?>