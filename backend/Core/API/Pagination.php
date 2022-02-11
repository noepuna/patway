<?php

/**
 * pagination class for api response
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

use App\IConfig;
use Core\API\IPaginationColumn;
use Core\API\IPaginationFilter;
use Core\API\PaginationFilters;
use Core\API\PaginationIndex;
use Core\API\PaginationFilter;
use Core\API\PaginationMeta;
use Core\Datatype\Undefined;





Class Pagination implements \Core\API\IPagination
{
	use \Core\Util\TUtilOps;

    /**
     * ...
     *
     * @var App\IConfig
     */
	private $_config;

    /**
     * ...
     *
     * @var Array
     */
	private $_prop =
	[
		'offset' => 0,
		'page_token' => null,
		'index_column' => null,
		'order_by' => null,
		'filter' => null,
		'query_result' => []
	];





	/**
	 *	...
	 *
	 *	@access public
	 *	@param ...
	 *	@return ...
	 *	@throws \Exception Invalid token.
	 *	@throws \Exception Unsupported cipher method.
	 *	@throws \Exception Insufficient Metadata.
	 *	@since Method available since Beta 1.0.0
	 * 	@
	 *
	 *	Requires a valid cipher.
	 *	Requires a valid metadata.
	 *		Possible metadatas
	 *			(1) token based - break down the token. then use the retrieved values to create an array based metadata.
	 *			(2) array based
	 */
	public function __construct( Iconfig $CONFIG, PaginationMeta $META )
	{
		if( false === in_array($this->_cipher, openssl_get_cipher_methods()) )
		{
			throw new \Exception("Cipher not found", 1);
		}

		if( true === $META->require("pagetoken") )
		{
			$pagetoken = $this->_decryptPaginationToken($META->pagetoken);
			$pagetoken = PaginationMeta::searchAndRemove($pagetoken, null);

			if( $pagetoken )
			{
				$META = $META::createInstance( $CONFIG, $pagetoken);

				if( false === is_a($META, PaginationMeta::t_UtilOps_classWithBackslash()) )
				{
					throw new \Exception("pagetoken metadata is invalid", 1);
				}
			}
			else
			{
				throw new \Exception("pagetoken authentication failed", 1);
			}
		}

		if( true === $META->require("index_column") )
		{
			$this->_prop['index_column'] = $META->index_column;
		}

		$properties = [ "limit", "offset", "filter", "group_by", "order_by" ];

		if( true === $META->require("limit", /*"group_by"*/) )
		{
			$this->_config = $CONFIG;

			foreach( $properties as $key => $property )
			{
				$value = $META->$property;

				if( $value instanceof Undefined )
				{
					continue;
				}

				$this->_prop[$property] = $value;
			}

			//
			// the first column given in 'group_by' meta property will be the index column
			//
			//list( $this->_prop['index_column'] ) = $this->getGroupBy();
		}
		else
		{
			throw new \Exception("Insufficient Metadata", 1);
		}
	}





	/**
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 *	...
	 *	...
	 *	...
	 */
	public static function createInstance( IConfig $CONFIG, PaginationMeta $META )
	{
		try
		{
			return new self($CONFIG, $META);
		}
		catch( \Exception $e)
		{
			return false;
		}
	}





	/**
	 *	...
	 *
	 *	@access public
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 */	
	public function getLimit() : int
	{
		return $this->_prop['limit'];
	}





	/**
	 *	...
	 *
	 *	@access public
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 */	
	public function getOffset() : int
	{
		return $this->_prop['offset'];
	}





	/**
	 *	...
	 *
	 *	@access public
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 */	
	public function getIndexColumn() :? PaginationIndex
	{
		return $this->_prop['index_column'] ?? null;
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
     */ 
    public function getOrderBy() :? Array
    {
    	return $this->_prop['order_by'] ?? null;
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
     */ 
    public function getGroupBy() :? Array
    {
    	return $this->_prop['group_by'] ?? null;
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
     */ 
    public function getFilter() :? Array
    {
    	return $this->_prop['filter'];
    }





	/**
	 *	...
	 *
	 *	@access public
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 */	
	public function getResult() : Array
	{
		$this->_runQuery();

		$data = $this->_prop['query_result'];

		if( count($data) > $this->getLimit() )
		{
			array_pop($data);
		}

		return
		[
			'data' => $data,
			'pagetoken' => $this->_createPaginationToken($this->_prop['query_result'])
		];
	}





	private function _createFilterQueryComponents( $FILTER, Array &$PARAMS, int &$COUNT )
	{
		$filterSQL = "";
		$paramIndex = "p";

		if( $FILTER instanceof IPaginationFilter )
		{
			$fColumnName = $FILTER->getProperty()->getNameAlias();
			$fValue = $FILTER->getValue();
			$fArithmetic = $FILTER->getArithmeticOperator();
			$fLogic = $FILTER->getLogicOperator();

			//transform values using match search
			$matchArithmetic = [ PaginationFilter::_ARITHMETIC_CONTAINS , PaginationFilter::_ARITHMETIC_STARTS_WITH ];

			if( in_array($fArithmetic, $matchArithmetic) )
			{
				switch( $fArithmetic )
				{
					default:
						var_dump(55);

					case PaginationFilter::_ARITHMETIC_CONTAINS:
						$fValue = "%{$fValue}%";
					break;

					case PaginationFilter::_ARITHMETIC_STARTS_WITH:
						$fValue = "{$fValue}%";
					break;
				}

				$fArithmetic = "LIKE";
			}


			// value can be scalar or null
			if( true === is_null($fValue) )
			{
				if( "<>" === $fArithmetic )
				{
					$filterSQL .= " {$fLogic} {$fColumnName} IS NOT NULL";
				}
				else
				{
					$filterSQL .= " {$fLogic} {$fColumnName} IS NULL";
				}
			}
			else if( true === is_scalar($fValue) )
			{
				$paramKey =	$paramIndex . $COUNT++;
				$PARAMS[$paramKey] = $fValue;
				$filterSQL .= " {$fLogic} {$fColumnName} {$fArithmetic} :{$paramKey}";
			}
			else
			{
				$inClause = "";

				foreach( $fValue as $value )
				{
					$paramKey = $paramIndex . $COUNT++;
					$PARAMS[$paramKey] = $value;
					$inClause .= ":{$paramKey},";
				}

				$filterSQL .= " {$fLogic} {$fColumnName} IN(" . substr($inClause, 0, -1) . ")";
			}

			return $filterSQL;
		}

		foreach( $FILTER as $key => $iFilter )
		{
			$filterSQL .= $this->_createFilterQueryComponents($iFilter, $PARAMS, $COUNT);
		}

		if( $FILTER instanceof PaginationFilters )
		{
			$filterSQL = preg_replace('/^(\s+(AND)|(OR))?/', '', $filterSQL);
			$filterSQL = " " . $FILTER->getLogicOperator() . ' (' . $filterSQL . ') ';
		}

		return $filterSQL;
	}





    /**
     * ...
     *
     * @var string
     */
    protected string $_initial_sql = "";

	/**
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 */
	private function _runQuery()
	{
		$iDb = $this->_config->getDbAdapter();

		//
		//
		//
		$param =
		[
			'limit' => $this->getLimit() + 1,
			'offset' => $this->getOffset()
		];

		$querySTRFrag = [ 'where' => null, 'group_by' => null, 'order_by' => null ];

		//
		// set the start of the search by <= "indexValue"
		//
		if( $indexColumn = $this->getIndexColumn() )
		{
			$iIndexProperty = $indexColumn->getProperty();
			$indexOrderOperator = $indexColumn->getOrder() === $indexColumn::_DESC ? "<=" : ">=";

			$querySTRFrag['order_by'] .= "ORDER BY\040" . $iIndexProperty->getNameAlias() . "\040" . $indexColumn->getOrder();

			if( $indexColumn->getValue() )
			{
				$querySTRFrag['where'] .= "WHERE\040" . $iIndexProperty->getNameAlias() . $indexOrderOperator . $indexColumn->getValue();
			}
		}

		//build the GroupBy SQL statement
		//$groupByQryFragments = array();

		//
		// build the group by SQL segment
		//
		if( $kGroupBy = $this->getGroupBy() )
		{
			$groupByMapFn = fn($iColumn) => $iColumn->getNameAlias();
			$querySTRFrag['group_by'] = "GROUP BY\040" . implode( ", ", array_map($groupByMapFn, $kGroupBy) );
		}

		//
		// build the OrderBy SQL statement
		//
		if( $kOrderBy = $this->getOrderBy() )
		{
			$querySTRFrag['order_by'] = $querySTRFrag['order_by'] ? $querySTRFrag['order_by'] . ",\040" : "ORDER BY\040";

			$orderByMapFn = fn($iOrderBy) => $iOrderBy->getProperty()->getNameAlias() . " " . $iOrderBy->getValue();
			$querySTRFrag['order_by'] .= implode(", ", array_map($orderByMapFn, $kOrderBy));
		}

		//
		// build the Where SQL segment
		//
		if( $filterList =  $this->getFilter() )
		{
			$querySTRFrag['where'] = $querySTRFrag['where'] ? $querySTRFrag['where'] . "\040" : "WHERE 1";

			$count = 0;
			$whereQryFragments = [];

			$querySTRFrag['where'] .= $this->_createFilterQueryComponents($filterList, $param, $count);
		}

		$querySTR = "{$this->_initial_sql} {$querySTRFrag['where']} {$querySTRFrag['group_by']} {$querySTRFrag['order_by']} LIMIT :limit OFFSET :offset";

		//echo "<p style='color:#580808;'>" . $querySTR . "</p> <pre style='color:#580808;'>" . print_r($param, 1) . "</pre>";
		$iDb->query($querySTR, $param);

		$this->_prop = 
		[
			'query_result' => $iDb->fetch()

		] + $this->_prop;
	}





    /**
     * ...
     *
     * @var string
     */
	private $_key = "1f8ed5489bee647c4d82f232204de038";

    /**
     * ...
     *
     * @var string
     */
	private $_cipher = "aes-128-gcm";

    /**
     * ...
     *
     * @var string
     */
	private $_initializationVector = "0";

	/**
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 *	...
	 *	...
	 *	...
	 */
	private function _createPaginationToken( Array &$DATA )
	{
		$prop = $this->_prop;

		/*
		 * Get the pagination cursor using the index column.
		 * Get the index value in the last item result as a cursor for our search
		 * if none then set the cursor to null.
		 */
		$cursor = null;
		$iIndexColumn = $this->getIndexColumn();
		$indexName = $iIndexColumn->getProperty()->getName();

		//$indexValue = $this->_prop['index_column']->getNameAlias();
		//$indexColumn = $this->_prop['index_column']->getName();

		if( count($DATA) > 0 )
		{
			/*
			 * remove the additional data used to determine the possible extra data.
			 */
			if( count($DATA) > $this->getLimit() )
			{
				array_pop($DATA);
			}

			$endKey  = count($DATA) - 1;
			$cursor  = $DATA[ $endKey ][ $indexName ];

			/*
			 * Calculate the result offset.
			 * If index value has not changed, result offset must be added to the previous offset
			 * to track the next results.
			 */
			$offset	= 0;

			if( $iIndexColumn->getValue() === (string)$cursor )
			{
				$offset = $prop['offset'] + $this->getLimit();
			}
			else
			{
				foreach( $DATA as $entry )
				{
					if( $entry[$indexName] === $cursor )
					{
						$offset += 1;
					}
				}
			}
		}
		else
		{
			/*
			 * Use the same last metadata if no records found.
			 */
			$cursor = $iIndexColumn->getValue();
			$offset = $prop['offset'];
		}

		/*
		 * 
		 */
		$tokenData =
		[
			'indexValue' => $cursor,
			'indexColumn' => $indexName,
			'index_column' => PaginationIndex::createInstance( $iIndexColumn->getProperty(), $cursor, $iIndexColumn->getOrder() ),
			'limit' => $this->getLimit(),
			'offset' => $offset,
			'filter' => $this->getFilter(),
			'group_by' => $this->getGroupBy(),
			'order_by' => $this->getOrderBy()
		];

		/*
		 * do not include if the value is instance of \Core\Datatype\Undefined
		 */
		$metaEntries = ["filter", "order", "group_by"];

		foreach( $metaEntries as $key => $entry )
		{
			if( $prop->$entry ?? null  instanceof Undefined )
			{
				continue;
			}

			$tokenData[$entry] = $prop[$entry] ?? null;
		}

		$serializeArray = serialize($tokenData);
		$ciphertext = openssl_encrypt($serializeArray, $this->_cipher, $this->_key, $options=0, $this->_initializationVector, $authenticationTag, null, 2);

		return base64_encode($authenticationTag . $ciphertext);
	}





	/**
	 *	...
	 *
	 *	@access private
	 *	@param ...
	 *	@return ...
	 *	@since Method available since Beta 1.0.0
	 *
	 *	...
	 *	...
	 *	...
	 *	...
	 */
	private function _decryptPaginationToken( string $TOKEN )
	{
		$TOKEN = base64_decode($TOKEN);
		$text = openssl_decrypt( substr($TOKEN, 2), $this->_cipher, $this->_key, $options=0, $this->_initializationVector, substr($TOKEN, 0, 2) );
		return unserialize($text);
	}
}

?>