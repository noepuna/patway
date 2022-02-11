<?php

	namespace Resource;

	trait TPaginationDate
	{
		public static function t_paginationdate_convert( $DATE_VALUES )
		{
			$convertFn = function( $dateRepresentation )
			{
				switch($dateRepresentation)
				{
					case "now":
					case "today":
						$currentDate = new \DateTime();
						$currentDate->setTime(0, 0, 0);
						$startofdaytoday = $currentDate->format('Y-m-d H:i:s');
						$currentDate->setTime(23, 59, 59);
						$endofdaytoday = $currentDate->format('Y-m-d H:i:s');

						return
						[
							array( 'value' => $startofdaytoday, 'operator' => ">", 'logic' => "AND" ),
							array( 'value' => $endofdaytoday, 	'operator' => "<", 'logic' => "AND" )
						];

					case "yesterday":
						$currentDate = new \DateTime();
						$currentDate->sub(new \DateInterval('P1D'));
						$currentDate->setTime(0, 0, 0);
						$startofyesterday = $currentDate->format('Y-m-d H:i:s');
						$currentDate->setTime(23, 59, 59);
						$endofyesterday = $currentDate->format('Y-m-d H:i:s');						

						return
						[
							array( 'value' => $startofyesterday, 'operator' => ">", 'logic' => "AND" ),
							array( 'value' => $endofyesterday, 	'operator' => "<", 'logic' => "AND" )
						];

					case "past week":
						$currentDate = new \DateTime();
						$currentDate->modify('last saturday');
						$endoflastweek = $currentDate->format('Y-m-d H:i:s');
						
						$currentDate->sub(new \DateInterval('P6D'));
						$startoflastweek = $currentDate->format('Y-m-d H:i:s');

						return
						[
							array( 'value' => $startoflastweek, 'operator' => ">", 'logic' => "AND" ),
							array( 'value' => $endoflastweek, 	'operator' => "<", 'logic' => "AND" )
						];

					case 'past month':
						$currentDate = new \DateTime();
						$currentDate->modify('first day of previous month');
						$currentDate->setTime(0, 0, 0);
						$startoflastmonth = $currentDate->format('Y-m-d H:i:s');
						$currentDate->modify('last day of this month');
						$endoflastmonth = $currentDate->format('Y-m-d H:i:s');

						return
						[
							array( 'value' => $startoflastmonth, 'operator' => ">", 'logic' => "AND" ),
							array( 'value' => $endoflastmonth, 	'operator' => "<", 'logic' => "AND" )
						];

					default:
						return $dateRepresentation;
				}
			};

			if( true === is_array($DATE_VALUES) )
			{
				foreach( $DATE_VALUES as &$dateRepresentation )
				{
					if( true === is_string($dateRepresentation) )
					{
						$dateRepresentation = [ 'logic' => "OR", 'value' => $convertFn($dateRepresentation) ];
					}
					else if( true === is_array($dateRepresentation) )
					{
						$dateRepresentation['value'] = $convertFn($dateRepresentation['value']);
					}
					else
					{
						throw new \Exception("Expecting an array or string", 1);
					}
				}
			}
			else
			{
				$DATE_VALUES = $convertFn($DATE_VALUES);
			}

			return $DATE_VALUES;
		}
	}

?>