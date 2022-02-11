<?php

/**
 * Interface for a Behavior Based Safety Sent Message
 *
 * @category	App\BehaviorBaseSafety\Messaging
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace App\BehaviorBaseSafety\Messaging;

interface ISentMessage extends \App\Messaging\ISentMessage
{
    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getObservation() : string;
}

?>