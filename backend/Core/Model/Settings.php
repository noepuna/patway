<?php

/**
 * ...
 *
 * @category	Core\Model
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace Core\Model;

Class Settings
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
    private Array $_metadata;

    /**
     * ...
     *
     * ...
     *
     * @var String;
     * @access private
     */
    private ?String $_field;

    /**
     * ...
     *
     * ...
     *
     * @var String;
     * @access private
     */
    private ?String $_subfield;

    /**
     * ...
     *
     * ...
     *
     * @var Array<any>;
     * @access protected
     */
    private ?int $_index = null;

    /**
     * ...
     *
     * ...
     *
     * @var Array<any>;
     * @access protected
     */
    protected $_newValue = null;

    /**
     * ...
     *
     * ...
     *
     * @var Array<any>;
     * @access protected
     */
    protected Array $_metadataValue;

    /**
     * ...
     *
     * ...
     *
     * @var Array;
     * @access protected
     */
    protected Array $_settings;





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
    public function __construct( $SUBFIELD, $NEW_VALUE, $METADATA, &$METADATA_VALUE, $FIELD, int $INDEX = NULL )
    {
        $this->_metadata = $METADATA;
        $this->_field = $FIELD;
        $this->_subField = $SUBFIELD;
        $this->_index = $INDEX;
        $this->_newValue = $NEW_VALUE;
        $this->_metadataValue = &$METADATA_VALUE;

        if( $FIELD )
        {
            $this->_settings = $this->_metadata[$this->getField()][$this->getName()] ?? [];
        }
        else
        {
            $this->_settings = $this->_metadata[$this->getName()] ?? [];
        }
    }





    /**
     *  ...
     *
     *  @access public
     *  @static
     *  @param ...
     *  @return ...
     *  @since Method available since Beta 1.0.0
     *
     *  ...
     *  ...
     *  ...
     *  ...
     */
    public static function createInstance( $SUBFIELD, $NEW_VALUE, $METADATA, &$METADATA_VALUE, $FIELD, $INDEX = NULL ) : ?self
    {
        return new self( $SUBFIELD, $NEW_VALUE, $METADATA, $METADATA_VALUE, $FIELD, $INDEX );
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
    public function getCurrentValue()
    {
        if( $this->getField() )
        {
            return $this->_metadataValue[$this->getField()][$this->getName()] ?? null;
        }
        else
        {
            return $this->_metadataValue[$this->getName()] ?? null;
        }
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
    public function getNewValue()
    {
        return $this->_newValue;
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
    public function getIndex() : ?int
    {
        return $this->_index;
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
    public function getName() : string
    {
        return $this->_subField;
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
    public function getType() : ?string
    {
        return $this->_settings['type'] ?? null;
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
    public function getField() : ?string
    {
        return $this->_field;
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
    public function getAlias()
    {
        return $this->_settings['alias'] ?? null;
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
    public function nullAllowed() : bool | string
    {
        return $this->_settings['null-allowed'] ?? false;
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
    public function isArray() : bool
    {
        return $this->_settings['is-array'] ?? false;
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
    public function isUnsigned() : bool
    {
        return $this->_settings['unsigned'] ?? false;
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
    public function zeroAllowed() : bool
    {
        return $this->_settings['zero-allowed'] ?? true;
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
    public function decimalAllowed() : bool
    {
        return $this->_settings['decimal-allowed'] ?? true;
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
    public function getMin() : ?int
    {
        return $this->_settings['min'] ?? null;
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
    public function getMax() : ?int
    {
        return $this->_settings['max'] ?? null;
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
    public function getLengthMin() : ?int
    {
        return $this->_settings['length-min'] ?? null;
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
    public function getLengthMax() : ?int
    {
        return $this->_settings['length-max'] ?? null;
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
    public function getCountMin() : ?int
    {
        return $this->_settings['count-min'] ?? null;
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
    public function getCountMax() : ?int
    {
        return $this->_settings['count-max'] ?? null;
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
    public function getCollection() : Array
    {
        return $this->_settings['collection'] ?? [];
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
    public function spawn( $SUBFIELD, $NEW_VALUE = null, $FIELD = null, Array $ADDITIONAL_SETTINGS = null, int $INDEX = NULL ) : ?self
    {
        if( $ADDITIONAL_SETTINGS ?? false )
        {
            if( $FIELD )
            {
                $settings = &$this->_metadata[$this->getField()][$this->getName()] ?? [];
            }
            else
            {
                $settings = &$this->_metadata[$this->getName()] ?? [];
            }

            $settings = array_merge($settings, $ADDITIONAL_SETTINGS);
        }

        return self::createInstance( $SUBFIELD, $NEW_VALUE, $this->_metadata, $this->_metadataValue, $FIELD, $INDEX );
    }
}

?>
