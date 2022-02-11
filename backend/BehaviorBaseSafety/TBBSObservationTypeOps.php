<?php

/**
 * ...
 *
 * @category	App\BehaviorBaseSafety
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */

	namespace App\BehaviorBaseSafety;

	use App\IConfig;





	trait TBBSObservationTypeOps
	{
		protected function t_BBSObservationTypeOps_exists( IConfig $CONFIG, string $TYPE_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $TYPE_ID ];

			$db->query("SELECT `uid` FROM `bbs_observation_type` WHERE `uid` = :id LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_BBSObservationTypeOps_isAvailable( IConfig $CONFIG, string $TYPE_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $TYPE_ID ];

			$db->query("SELECT `uid` FROM `bbs_observation_type` WHERE `uid` = :id AND `disabled` = 0 LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}
	}

?>