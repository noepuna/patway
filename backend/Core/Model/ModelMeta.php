<?php

/**
 * All Business Logic for a Model.
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

use Core\Model\Settings;
use Core\Datatype\Undefined;

Class ModelMeta
{
    use \Core\Util\TUtilOps, \Core\Util\TUtilDateOps;

    /**
     * ...
     *
     * ...
     *
     * @var Array<any>;
     * @access private
     */
    private Array $_prop = [];

    /**
     * ...
     *
     * ...
     *
     * @var Array<any>;
     * @access private
     */
    private Array $_errors = [];

    /**
     * ...
     *
     * ...
     *
     * @var String;
     * @access private
     */
    private ?String $_lastError = null;

    /**
     * ...
     *
     * ...
     *
     * @var Array<any>;
     * @access protected
     */
    protected Array $_metadata = [];

    /**
     * ...
     *
     * ...
     *
     * @var Array<any>;
     * @access protected
     */
    protected Array $_listeners = [];

    /**
     * ...
     *
     * ...
     *
     * @var Array;
     * @access protected
     */
    protected Array $_numericFields =
    [
        'numeric', 'smallint' , 'mediumint'
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
    public function __construct()
    {
        //
    }





    /**
     *  ...
     *
     *  @access public
     *  @param ...
     *  @return ...
     *  @final
     *  @since Method available since Beta 1.0.0
     *
     *  ...
     *  ...
     *  ...
     *  ...
     */
    final public function __set( string $FIELD, $VALUE )
    {
        $this->setLastError(null);

        /*
         *  meta in string format
         */
        if( true === in_array($FIELD, $this->_metadata) )
        {
            if( null === $VALUE )
            {
                $this->setLastError("{$FIELD} is required");
            }
            else
            {
                if( true === is_string($VALUE) )
                {
                    if( 0 >= strlen($VALUE) )
                    {
                        $this->setLastError("{$FIELD} is required");
                    }
                }
                else
                {
                    $this->setLastError("{$FIELD} is invalid");
                }
            }
        }

        $initialCheckEvt = function( Settings $SETTINGS )
        {
            $validateEvt = function($SETTINGS)
            {
                $this->setLastError(null);

                $value = $SETTINGS->getNewValue();

                /*
                 * null check
                 */
                if( null === $value && false === $SETTINGS->nullAllowed() )
                {
                    $this->setLastError( $SETTINGS->getAlias() ?? $SETTINGS->getName() . " is required" );
                }

                /*
                 * type check for a non-null value
                 */
                if( !$this->getLastError() && $SETTINGS->getType() && false === is_null($value) )
                {
                    $this->_validateByType($SETTINGS, $value);
                }

                /*
                 * continue to custom validation
                 *
                 * cases where custom validation is applied
                 * (1) non-null values
                 * (2) null value but with settings of null-allowed is set to mixed
                 */
                if( !$this->getLastError() && ( false === is_null($value) || "mixed" === $SETTINGS->nullAllowed() ) )
                {
                   $this->_setSpecialProperty($SETTINGS);
                }
            };

            $name = $SETTINGS->getName();
            $field = $SETTINGS->getField();
            $value = $SETTINGS->getNewValue();

            if( $SETTINGS->isArray() )
            {
                if( is_array($value) )
                {
                    if( ($min = $SETTINGS->getCountMin()) && (count($value) < $min) )
                    {
                        $this->setError( $name, $field, "atleast {$min} is required", true );
                    }
                    else
                    {
                        $latestError = $this->getLastError();

                        /*
                         * multiple values for a metadata validation
                         */
                        foreach( $value as $key => $valueEntry )
                        {
                            $validateEvt( $SETTINGS->spawn($name, $valueEntry, $field, null, $key) );

                            if( $errorMsg = $this->getLastError() )
                            {
                                $latestError = $this->getLastError();

                                $this->setError( $name, $field, $errorMsg, true, $key );
                            }
                        }

                        /*
                         * reassign the latest error
                         */
                        $this->getLastError() ?? $this->setLastError($latestError);
                    }
                }
                else
                {
                    $this->setError( $name, $field, "must be a collection", true );
                }
            }
            else
            {
                /*
                 * single value for a metadata validation
                 */
                $validateEvt($SETTINGS);

                if( $errorMsg = $this->getLastError() )
                {
                    $this->setError( $name, $field, $errorMsg, true );
                }
            }
        };

        if( ($metadata = Settings::createInstance($FIELD, $VALUE, $this->_metadata, $this->_prop, null)) && $metadata->isArray() || !is_array($VALUE) )
        {
            /*
             * single meta
             */
            $initialCheckEvt($metadata);
        }
        else
        {
            /*
             * multiple meta
             */
            foreach( $VALUE as $subfield => $value )
            {
                $initialCheckEvt( $metadata->spawn($subfield, $value, $FIELD) );
            }
        }

        if( !$this->getLastError() )
        {
            $finalValueSettings =
            [
                'previous_value' => $this->_prop,
                'is_final' => true
            ];

            if( array_key_exists($FIELD, $this->_prop) && is_array($this->_prop[$FIELD]) )
            {
                $this->_prop[$FIELD] = $VALUE + $this->_prop[$FIELD];
            }
            else
            {
                $this->_prop[$FIELD] = $VALUE;
            }

            foreach( $this->_listeners as $listener )
            {
                $iRef = new \ReflectionClass($listener);

                if( $iRef->hasMethod("getRequirements") )
                {
                    /*
                     * since a listener always run after it is created
                     * so skip running in this current call
                     */
                    if( $listener->disabled )
                    {
                        $listener->disabled = false;
                    }
                    else
                    {
                        if( $this->require(...$listener->getRequirements()) && $dimensions = $listener->getCallback()() )
                        {
                            $dependency = $dimensions['metadata'];

                            $finalValueSettings['is_final'] = false;
                            $this->setError( $dependency->getName(), $dependency->getField(), $dimensions['message'] ?? "" );
                        }
                    }
                }
                else
                {
                    if( $listener->checkError() )
                    {
                        $finalValueSettings['is_final'] = false;
                    }
                }
            }

            if( !$finalValueSettings['is_final'] )
            {
                $this->_prop = $finalValueSettings['previous_value'];
            }
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
    public function __get( string $NAME )
    {
        if( true === array_key_exists($NAME, $this->_prop) )
        {
            return $this->_prop[$NAME];
        }
        else
        {
            return new Undefined();
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
    public function setError( String $SEGMENT, ?String $FIELD, String $MESSAGE, Bool $IS_LAST_ERROR = NULL, string $INDEX = NULL )
    {
        if( $IS_LAST_ERROR )
        {
            $this->setLastError($MESSAGE);
        }

        if( $FIELD ?? false )
        {
            if( NULL !== $INDEX )
            {
                $this->_errors[$FIELD][$SEGMENT][$INDEX] = $MESSAGE;
            }
            else
            {
                $this->_errors[$FIELD][$SEGMENT] = $MESSAGE;
            }
        }
        else
        {
            if( NULL !== $INDEX )
            {
                $this->_errors[$SEGMENT][$INDEX] = $MESSAGE;
            }
            else
            {
                $this->_errors[$SEGMENT] = $MESSAGE;
            }
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
    public function getError( String $SEGMENT, String $FIELD, String $VALUE ) :? String
    {
        return $this->_errors[$SEGMENT][$FIELD] ?? null;
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
    public function getErrors() : Array
    {
        return $this->_errors;
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
    public function setLastError( String $VALUE = NULL ) :? String
    {
        return $this->_lastError = $VALUE;
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
    public function getLastError() :? String
    {
        return $this->_lastError;
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
    final public function require( ...$ARGS )
    {
        $callback = null;

        if( is_callable($ARGS[count($ARGS) - 1]) )
        {
            $callback = array_pop($ARGS);
        }

        foreach( $ARGS as $key => $property )
        {
            if( is_string($property) )
            {
                if( $this->$property instanceOf Undefined )
                {
                    return false;
                }
            }
            else if( is_array($property) )
            {
                if( count($property) < 1 )
                {
                    return false;
                }

                $segment = array_values($property);
                $parentField = array_shift($segment);

                if( $this->$parentField instanceOf Undefined )
                {
                   return false;
                }

                foreach( $segment as $subfield )
                {
                    if( !is_array($this->$parentField) || !array_key_exists($subfield, $this->$parentField) )
                    {
                        return false;
                    }
                }
            }
            else
            {
                throw new \Exception("Uncaught TypeError: Arguments passed to Resource\AppMeta::require() must be of the type string or array");
            }
        }

        $callback && $callback();

        return true;
    }





    /**
     * ...
     *
     * @access public
     * @param ...
     * @static
     * @return ...
     * @since Method available since Beta 1.0.0
     *
     * ...
     * ...
     * ...
     * ...
     */
    public static function searchAndReplace( Array $DATA = NULL, $REPLACEE, $REPLACEMENT )
    {
        $newData = array();

        $replaceFn = function( Array $DATA, Array &$CURRENT_DIMENSION ) use (&$replaceFn, $REPLACEE, $REPLACEMENT)
        {
            foreach( $DATA as $key => $value )
            {
                if( is_array($value) )
                {
                    $CURRENT_DIMENSION[$key] = [];

                    $replaceFn($value, $CURRENT_DIMENSION[$key]);
                }
                else
                {
                    if( $REPLACEE === $value )
                    {
                        $CURRENT_DIMENSION[$key] = $REPLACEMENT;
                    }
                    else
                    {
                        $CURRENT_DIMENSION[$key] = $value;
                    }
                }
            }
        };

        $replaceFn($DATA ?$DATA :[], $newData);

        return $newData;
    }

    /**
     * ...
     *
     * @access public
     * @param ...
     * @static
     * @return ...
     * @since Method available since Beta 1.0.0
     *
     * ...
     * ...
     * ...
     * ...
     */
    public static function searchAndRemove( Array $DATA = NULL, $REPLACEE )
    {
        $newData = array();

        $replaceFn = function( Array $DATA, Array &$CURRENT_DIMENSION ) use (&$replaceFn, $REPLACEE)
        {
            foreach( $DATA as $key => $value )
            {
                if( is_array($value) )
                {
                    $CURRENT_DIMENSION[$key] = [];

                    $replaceFn($value, $CURRENT_DIMENSION[$key]);
                }
                else
                {
                    if( $REPLACEE !== $value )
                    {
                        $CURRENT_DIMENSION[$key] = $value;
                    }
                }
            }
        };

        $replaceFn($DATA ?$DATA :[], $newData);

        return $newData;
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
    protected function _setSpecialProperty( Settings $SETTINGS )
    {
        //
    }





    /**
     *  ...
     *
     *  @access public
     *  @param ...
     *  @return ...
     *  @since Method available since Beta 1.0.0
     *  @throws if type property is missing in the noneEmptyField or not supported.
     *
     *  ...
     *  ...
     *  ...
     *  ...
     */
    private function _validateByType( Settings $SETTINGS, $VALUE )
    {
        $type = $SETTINGS->getType();
        $alias = $SETTINGS->getAlias() ?? $SETTINGS->getName();

        switch( $type )
        {
            case "boolean":
                if( true === is_null($VALUE) )
                {
                    $this->setLastError("{$alias} is required");
                }
                else
                {
                    if( false === is_bool($VALUE) )
                    {
                        $this->setLastError("{$alias} must be boolean");
                    }
                }
            break;

            case 'numeric':
                $this->setLastError( $this->_validateNumber($VALUE, $SETTINGS) );
                break;

            case 'natural_number':
                $additionalSettings = [ 'unsigned' => true, 'zero-allowed' => false, 'decimal-allowed' => false ];
                $SETTINGS = $SETTINGS->spawn( $SETTINGS->getName(), $SETTINGS->getCurrentValue(), $SETTINGS->getField(), $additionalSettings );
                $this->setLastError( $this->_validateNumber($VALUE, $SETTINGS) );
            break;

            case 'smallint':
                $additionalSettings = [ 'max' => 32768, 'decimal-allowed' => false ];
                $SETTINGS = $SETTINGS->spawn( $SETTINGS->getName(), $SETTINGS->getCurrentValue(), $SETTINGS->getField(), $additionalSettings );
                $this->setLastError( $this->_validateNumber($VALUE, $SETTINGS) );
                break;

            case 'mediumint':
                $additionalSettings = [ 'max' => 8388608, 'decimal-allowed' => false ];
                $SETTINGS = $SETTINGS->spawn( $SETTINGS->getName(), $SETTINGS->getCurrentValue(), $SETTINGS->getField(), $additionalSettings );
                $this->setLastError( $this->_validateNumber($VALUE, $SETTINGS) );
                break;

            case 'int':
                $additionalSettings = [ 'max' => 4294967295, 'decimal-allowed' => false ];
                $SETTINGS = $SETTINGS->spawn( $SETTINGS->getName(), $SETTINGS->getCurrentValue(), $SETTINGS->getField(), $additionalSettings );
                $this->setLastError( $this->_validateNumber($VALUE, $SETTINGS) );
            break;

            case 'scalar':
                if( false === is_scalar($VALUE) )
                {
                    $this->error = "{$alias} must be a scalar value";
                }
                break;

            case 'string':
                $this->setLastError( $this->_validateString($VALUE, $SETTINGS) );
                break;

            case 'tinytext':
                $SETTINGS = $SETTINGS->spawn( $SETTINGS->getName(), $SETTINGS->getCurrentValue(), $SETTINGS->getField(), ["length-max" => 255] );
                $this->setLastError( $this->_validateString($VALUE, $SETTINGS) );
                break;

            case 'alphanumeric';
                $strlen = strlen($VALUE);

                if( true === is_scalar($VALUE) && $strlen > 0 )
                {
                    if( preg_match("/^[a-zA-Z0-9]+$/", $VALUE) )
                    {
                        if( true === isset($prop['length-max']) && $strlen > $prop['length-max'] ){
                            $this->error = "limited only to {$prop['length-max']} characters";
                        }

                        if( true === isset($prop['length-min']) && $strlen < $prop['length-min'] )
                        {
                            $this->error = "must be atleast {$prop['length-min']} characters";   
                        }
                    }
                    else
                    {
                        $this->error = "must be alphanumeric";
                    }
                }
                else
                {
                    $this->error = "invalid data";
                }
                break;

            case 'enum':
                $this->setLastError( $this->_validateEnum($VALUE, $SETTINGS) );
                break;

            case "date":
                throw new \Exception("Deprecated", 1);
                try
                {
                    $date = new \DateTime($VALUE);
                }
                catch( \Exception $e )
                {
                    $this->error = "failed to recognize as date";
                }

                //DateTime::createFromFormat($);
                $fieldMeta = $this->_metadata[$NAME];
                $format = @$fieldMeta['format'];

                if( false === is_null($format) )
                {
                    $date = \DateTime::createFromFormat($format, $VALUE);

                    if( false === $date )
                    {
                        $this->error = "invalid format";
                    }
                }
                break;
            case "datetime":
                if( false === \DateTime::createFromFormat("Y-m-d H:i:s", $VALUE) )
                {
                    $this->error = "invalid date";
                }
            break;

            default:
                if( false === is_a($VALUE, $type) )
                {
                    $this->setLastError("{$alias} must be an instanceof {$type}");
                }
                break;
        }
    }




    /**
     * ...
     *
     * @access public
     * @param ...
     * @static
     * @return ...
     * @since Method available since Beta 1.0.0
     *
     * ...
     * ...
     * ...
     * ...
     */
    protected function _validateNumber( $VALUE, Settings $SETTINGS )
    {
        $error;

        $setError = function( string $ERROR )
        {
            return trim($ERROR);
            exit;
        };

        $alias = $SETTINGS->getAlias() ?? $SETTINGS->getName();

        if( false === is_numeric($VALUE) )
        {
            return $setError("{$alias} must be a number");
        }

        if( !$SETTINGS->nullAllowed() && is_null($VALUE) )
        {
            $setError("{$alias} is required");
        }

        if( $SETTINGS->isUnsigned() && $VALUE < 0 )
        {
            return $setError("{$alias} must be greater than 0");
        }

        if( is_object($VALUE) )
        {
            $floatVal = $VALUE;
        }
        else
        {
            $floatVal = floatval($VALUE);
        }

        if( !$SETTINGS->zeroAllowed() && $floatVal === floatVal(0) )
        {
            return $setError("zero not allowed");
        }

        if( !$SETTINGS->decimalAllowed() && strpos($VALUE, ".") )
        {
            return $setError("decimals not allowed");
        }

        if( ($max = $SETTINGS->getMax()) ?? false )
        {
            if( $VALUE > $max )
            {
                return $setError("max value is {$max}");
            }
        }

        if( ($min = $SETTINGS->getMin()) ?? false )
        {
            if( $VALUE < $min )
            {
                return $setError("mim value is {$min}");
            }
        }

        return null;
    }




    /**
     * ...
     *
     * @access public
     * @param ...
     * @static
     * @return ...
     * @since Method available since Beta 1.0.0
     *
     * ...
     * ...
     * ...
     * ...
     */
    protected function _validateString( $VALUE, Settings $SETTINGS )
    {
        $error;

        $setError = function( string $ERROR )
        {
            return trim($ERROR);
            exit;
        };

        $alias = $SETTINGS->getAlias() ?? $SETTINGS->getName();

        if( true === is_string($VALUE) || true === is_numeric($VALUE) )
        {
            $strlen = strlen($VALUE);

            if( 0 === $strlen )
            {
                return $setError("{$alias} is required");
            }
            else
            {
                if( ($SETTINGS->getLengthMax() ?? false) && $strlen > $SETTINGS->getLengthMax() )
                {
                    return $setError("limited only to {$SETTINGS->getLengthMax()} characters");
                }

                if( ($SETTINGS->getLengthMin() ?? false) && $strlen < $SETTINGS->getLengthMin() )
                {
                    return $setError("must be atleast {$SETTINGS->getLengthMin()} characters");
                }
            }
        }
        else
        {
            return $setError("{$alias} must be a string");
        }
    }




    /**
     * ...
     *
     * @access public
     * @param ...
     * @return ...
     * @since Method available since Beta 1.0.0
     *
     * ...
     * ...
     * ...
     * ...
     */
    protected function _validateEnum( $VALUE, Settings $SETTINGS )
    {
        $error;

        $setError = function( string $ERROR )
        {
            return trim($ERROR);
            exit;
        };

        $alias = $SETTINGS->getAlias() ?? $SETTINGS->getName();

        if( !in_array($VALUE, $SETTINGS->getCollection(), true) )
        {
            return $setError("is unsupported");
        }
    }




    /**
     * ...
     *
     * @access protected
     * @param ...
     * @return ...
     * @since Method available since Beta 1.0.0
     *
     * ...
     * ...
     * ...
     * ...
     */
    protected function _dependencyRegister( $SETTINGS, Callable $CALLBACK, ...$DEPENDENCIES )
    {
        $listener = $this->_listeners[] = new class( $this, $SETTINGS, $DEPENDENCIES, $CALLBACK )
        {
            private $_callback = null;
            public bool $_saveAsLastError = true;

            public function __construct( ModelMeta $MODEL_META, Settings $SETTINGS, Array $DEPENDENCIES, Callable $CALLBACK )
            {
                $this->_meta = $MODEL_META;
                $this->_settings = $SETTINGS;
                $this->_dependencies = $DEPENDENCIES;
                $this->_callback = $CALLBACK;
            }

            public function checkError() : ?string
            {
                if( !$this->_meta->require(...$this->_dependencies) )
                {
                    return null;
                }

                $settings = $this->_settings;
                $callback = $this->_callback;
                $params = [];

                foreach( $this->_dependencies as $dependency )
                {
                    if( is_array($dependency) && count($dependency) > 1 )
                    {
                        $field = array_shift($dependency);

                        foreach( $dependency as $name )
                        {
                            $params[] = $settings->spawn($name, null, $field)->getCurrentValue();
                        }
                    }
                    else if( is_array($dependency) && count($dependency) === 1 )
                    {
                        $params[] = $settings->spawn($dependency[0])->getCurrentValue();
                    }
                    else
                    {
                        $params[] = $settings->spawn($dependency)->getCurrentValue();
                    }
                }

                if( $error = $callback($settings, ...$params) )
                {
                    $this->_meta->setError( $settings->getName(), $settings->getField(), $error, $this->_saveAsLastError, $settings->getIndex() );
                }

                /*  
                 * since its already run in here then this listener
                 * should not be a last error anymore by default
                 * during the iteration of all listeners
                 */
                $this->_saveAsLastError = false;

                return $error;
            }
        };

        #!important run the check error to disable the last error flag
        $listener->checkError();
    }
    /**
     * ...
     *
     * @access protected
     * @param ...
     * @return ...
     * @since Method available since Beta 1.0.0
     *
     * ...
     * ...
     * ...
     * ...
     */
    protected function _errorDependencyHandler( ...$ARGS )
    {
        $callback = null;

        if( is_callable($ARGS[count($ARGS) - 1]) )
        {
            $callback = array_pop($ARGS);
        }

        $listener = $this->_listeners[] = new class( $ARGS, $callback )
        {
            public bool $disabled = false;

            public function __construct( Array $DEPENDENCIES, Callable $CALLBACK )
            {
                $this->_dependencies = $DEPENDENCIES;
                $this->_callback = $CALLBACK;
            }

            public function getRequirements() : Array
            {
                return $this->_dependencies;
            }

            public function getCallback() : Callable
            {
                return $this->_callback;
            }
        };

        if( $this->require(...$listener->getRequirements()) )
        {
            if( $callbackErr = $listener->getCallback()() )
            {
                $this->setLastError($callbackErr['message']);
            }

            /*  
             * since its already run in here disable this listener by default
             * this prevents to be run again inside the _set function
             * during the iteration of all listeners
             */
            $listener->disabled = true;
        }
    }
}

?>
