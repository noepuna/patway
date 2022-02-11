<?php

/**
 * ...
 *
 * @category	Core\API
 * @package    
 * @author     	Original Author <solanoreynan@gmail.com>
 * @copyright  
 * @license    
 * @version    	Beta 1.0.0
 * @link       
 * @see
 * @since      	Class available since Beta 1.0.0
 */
namespace Core\API;

use Core\API\PaginationFilter,
	Core\API\PaginationFilters;





Class PaginationFilterFactory
{
    /**
     * ...
     *
     * @var string
     */
    private string $_search_column = "";





	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 *
	 *	...
	 */
	public function __construct( string $CLASS_NAME )
	{
		$this->_search_column = $CLASS_NAME;

		//
		// Check that the class exists before trying to use it
		//
		if( !class_exists($CLASS_NAME) )
		{
		    throw new \Exception("class not found", 1);
		}
	}





	/**
	 *	...
	 *
	 * @access public
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 *
	 * ...
	 */
	public function createFromArray( Array $FILTERS, &$ERROR )
	{
		$result = array_map
		(
			$mapFn = function($filter, $index) use (&$mapFn)
			{
				if( !($filter['value'] ?? null) )
				{
					//return;
				}

				if( $filter['name'] ?? null )
				{
					$arithmetic = $filter['arithmetic_operator'] ?? "=";
					$logic = $filter['logic_operator'] ?? "AND";
					$value = $filter['value'] ?? null;

					try
					{
						$iColumn = new $this->_search_column($filter['name'] ?? "");

						return new PaginationFilter( $iColumn, $value, $arithmetic, $logic );
					}
					catch( \Exception $e )
					{
						return $e->getMessage();
					}
				}
				else if( $logic = $filter['logic_operator'] )
				{
					$compoundFilters = array_map( $mapFn, $filter['value'], array_keys($filter['value']) );

					if( $kFilters = PaginationFilters::createInstance( $filter['logic_operator'] ?? null, $compoundFilters ) )
					{
						return $kFilters;
					}

					return $compoundFilters;
				}
			},

			$FILTERS, array_keys($FILTERS)
		);

		$filterErrorFn = function( $FILTERS, $INDEXES = NULL ) use ( &$filterErrorFn, &$ERROR )
		{
			foreach( $FILTERS as $key => $iFilter )
			{
				$errorIndexes = $INDEXES ? [ ...$INDEXES, $key ] : [ $key ];

				if( is_string($iFilter) )
				{
					$currentDimension = &$ERROR;

					foreach( $errorIndexes as $index )
					{
						$currentDimension = &$currentDimension[$index];
					}
					
					$currentDimension = $iFilter;
					unset($currentDimension);
				}
				else if( is_array($iFilter) )
				{
					$filterErrorFn( $iFilter, $errorIndexes );
				}
			}
		};

		$filterErrorFn( $result );

		return $result;
	}





	/**
	 *	...
	 *
	 * @access public
	 * @static
	 * @param ...
	 * @return ...
	 * @since Method available since Beta 1.0.0
	 *
	 * ...
	 */
	public static function overrideData( Array &$FILTERS, Array $UPDATES )
	{
		array_walk
		(
			$UPDATES,

			function( $filterUpdate ) use ( &$FILTERS )
			{
				$restrictedFilters = array_map( fn($filter) => $filter['name'] ?? null, $FILTERS );

				# check if posted filters are restricted, restricted means a filter is already declared in the default filters
				# override restricted filter with the default filter

 				if( ($filterUpdate['name'] ?? null) && in_array($filterUpdate['name'], $restrictedFilters) )
				{
					foreach( $FILTERS as &$filter )
					{
						if( $filter['name'] === $filterUpdate['name'] )
						{
							$filter = $filterUpdate;
							break;
						}
					}
				}
				else
				{
					$FILTERS[] = $filterUpdate;
				}
			}
		);
	}
}

?>