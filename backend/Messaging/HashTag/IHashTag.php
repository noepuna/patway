<?php

/**
 * Interface for HashTag
 *
 * @category	App\Messaging\HashTag
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

namespace App\Messaging\HashTag;





interface IHashTag extends \App\HashTag\IHashTag
{
    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function getSentMessage() : string;

    /**
     * ...
     *
     * @access public
     * @param
     * @return
     * @since Method available since Beta 1.0.0
     */
    public function isDeleted() : bool;
}

?>