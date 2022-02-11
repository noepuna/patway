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

	namespace App\BehaviorBaseSafety\Property;

	use App\IConfig;





	trait TBBSObservationPropertyOps
	{
		protected function t_BBSObservationPropertyOps_exists( IConfig $CONFIG, string $PROPERTY_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $PROPERTY_ID ];

			$db->query("SELECT `uid` FROM `bbs_observation_property` WHERE `uid` = :id LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}





		protected function t_BBSObservationPropertyOps_getByObserverId( IConfig $CONFIG, string $OBSERVER_ID, $INCLUDE_DELETED = false ) : Array
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'observer' => $OBSERVER_ID ];
			$whereFrag = "WHERE `observation_fk` = :observer ";

			if( !$INCLUDE_DELETED )
			{
				$whereFrag .= "AND `deleted` = 0";
			}

			$db->query("SELECT `property_fk` AS `id`, `value`, `count` FROM `bbs_observation_properties` {$whereFrag}", $params);

			return $db->fetch();
		}





		protected function t_BBSObservationPropertyOps_getDetails( IConfig $CONFIG, string $PROPERTY_ID )
		{
			$iDb = $CONFIG->getDbAdapter();
			$params = [ 'property_id' => $PROPERTY_ID ];

			$iDb->query
			("
				SELECT `name`, `category_fk` AS `category`
				FROM `bbs_observation_property`
				WHERE `uid` = :property_id
				LIMIT 0, 1",

				$params
			);

			return $iDb->fetch()[0];
		}
	}

?>