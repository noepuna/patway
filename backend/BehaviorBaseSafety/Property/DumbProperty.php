<?php

/**
 * Behavior Base Safety Property Dummy
 *
 * @category	App\BehaviorBaseSafety\Property
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
namespace App\BehaviorBaseSafety\Property;

use App\IConfig;
use App\BehaviorBaseSafety\Observation;





class DumbProperty implements \App\BehaviorBaseSafety\Property\IProperty
{
    use \Core\Util\TUtilOps;

    private IConfig $_config;





    public function __construct( IConfig $CONFIG )
    {
        $this->_config = $CONFIG;
    }





    public Observation $observation;

    public function getObservation() : Observation
    {
        return $this->observation;
    }





    public string $id;

    public function getId() : string
    {
        return $this->id;
    }





    public ?string $value;

    public function getValue() :? string
    {
        return $this->value;
    }





    public ?int $count;

    public function getCount() :? int
    {
        $this->_requireLeadSegment();

        return $this->count;
    }





    public bool $deleted = false;

    public function isDeleted() : bool
    {
        return $this->deleted;
    }
};

?>