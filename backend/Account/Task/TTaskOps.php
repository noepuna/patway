<?php

	/**
	 * ...
	 *
	 * @category	App\Account\Task
	 * @package    
	 * @author     	Original Author <solanoreynan@gmail.com>
	 * @copyright  
	 * @license    
	 * @version    	Beta 1.0.0
	 * @link       
	 * @see
	 * @since      	Class available since Beta 1.0.0
	 */

	namespace App\Account\Task;

	use App\IConfig;

	trait TTaskOps
	{
		public function t_TaskOps_exists( IConfig $CONFIG, string $TASK_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $TASK_ID ];

			$db->query("SELECT `uid` FROM `app_task` WHERE `uid` = :id LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}

		public function t_TaskOps_isAvailable( IConfig $CONFIG, string $TASK_ID )
		{
			$db = $CONFIG->getDbAdapter();
			$params = [ 'id' => $TASK_ID ];

			$db->query("SELECT `uid` FROM `app_task` WHERE `uid` = :id AND `disabled` = 0 LIMIT 0, 1", $params);

			return $db->query_num_rows() === 1;
		}

		public function t_TaskOps_isAvailableByAccount( IConfig $CONFIG, string $ACCOUNT_ID, string... $TASK_IDS )
		{
			$params = [ 'auth_id' => $ACCOUNT_ID ];
			$taskCount = count($TASK_IDS);
			$db = $CONFIG->getDbAdapter();

			if($taskCount)
			{
				$taskParam = [];

				foreach( $TASK_IDS as $key => $taskId )
				{
					$paramKey = "task" . $key;
					$params[$paramKey] = $taskId;
					$taskParam[] = ":" . $paramKey;
				}

				$inStr = "(" . implode(", ", $taskParam) . ")";

				$db->query("SELECT `auth_fk` FROM `account_tasks` WHERE `disabled` = 0 AND `auth_fk` = :auth_id AND `task_fk` IN {$inStr}", $params);

				return $db->query_num_rows() === $taskCount ;
			}

			return true;
		}

		public function t_TaskOps_isDisabledByAuthId( IConfig $CONFIG, string $ACCOUNT_ID, string $TASK_ID )
		{
			$params = [ 'account_id' => $ACCOUNT_ID ];
			$db = $CONFIG->getDbAdapter();

			$db->query
			("
				SELECT A.uid FROM `auth` A
				INNER JOIN `account_tasks` T ON A.uid = T.auth_fk
				WHERE A.uid = :account_id
				LIMIT 0, 1",

				$params
			);

			return $db->query_num_rows() === 1;
		}
	}

?>