<?php


/**
 * Base class that represents a query for the 'tw_plugin_module_i18n' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.5.1 on:
 *
 * Fri Jun  4 01:21:50 2010
 *
 * @method     twPluginModuleI18nQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     twPluginModuleI18nQuery orderByCulture($order = Criteria::ASC) Order by the culture column
 * @method     twPluginModuleI18nQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     twPluginModuleI18nQuery orderByDescription($order = Criteria::ASC) Order by the description column
 *
 * @method     twPluginModuleI18nQuery groupById() Group by the id column
 * @method     twPluginModuleI18nQuery groupByCulture() Group by the culture column
 * @method     twPluginModuleI18nQuery groupByName() Group by the name column
 * @method     twPluginModuleI18nQuery groupByDescription() Group by the description column
 *
 * @method     twPluginModuleI18nQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     twPluginModuleI18nQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     twPluginModuleI18nQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     twPluginModuleI18nQuery leftJointwPluginModule($relationAlias = '') Adds a LEFT JOIN clause to the query using the twPluginModule relation
 * @method     twPluginModuleI18nQuery rightJointwPluginModule($relationAlias = '') Adds a RIGHT JOIN clause to the query using the twPluginModule relation
 * @method     twPluginModuleI18nQuery innerJointwPluginModule($relationAlias = '') Adds a INNER JOIN clause to the query using the twPluginModule relation
 *
 * @method     twPluginModuleI18n findOne(PropelPDO $con = null) Return the first twPluginModuleI18n matching the query
 * @method     twPluginModuleI18n findOneById(int $id) Return the first twPluginModuleI18n filtered by the id column
 * @method     twPluginModuleI18n findOneByCulture(string $culture) Return the first twPluginModuleI18n filtered by the culture column
 * @method     twPluginModuleI18n findOneByName(string $name) Return the first twPluginModuleI18n filtered by the name column
 * @method     twPluginModuleI18n findOneByDescription(string $description) Return the first twPluginModuleI18n filtered by the description column
 *
 * @method     array findById(int $id) Return twPluginModuleI18n objects filtered by the id column
 * @method     array findByCulture(string $culture) Return twPluginModuleI18n objects filtered by the culture column
 * @method     array findByName(string $name) Return twPluginModuleI18n objects filtered by the name column
 * @method     array findByDescription(string $description) Return twPluginModuleI18n objects filtered by the description column
 *
 * @package    propel.generator.plugins.twCorePlugin.lib.model.om
 */
abstract class BasetwPluginModuleI18nQuery extends ModelCriteria
{

	/**
	 * Initializes internal state of BasetwPluginModuleI18nQuery object.
	 *
	 * @param     string $dbName The dabase name
	 * @param     string $modelName The phpName of a model, e.g. 'Book'
	 * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
	 */
	public function __construct($dbName = 'propel', $modelName = 'twPluginModuleI18n', $modelAlias = null)
	{
		parent::__construct($dbName, $modelName, $modelAlias);
	}

	/**
	 * Returns a new twPluginModuleI18nQuery object.
	 *
	 * @param     string $modelAlias The alias of a model in the query
	 * @param     Criteria $criteria Optional Criteria to build the query from
	 *
	 * @return    twPluginModuleI18nQuery
	 */
	public static function create($modelAlias = null, $criteria = null)
	{
		if ($criteria instanceof twPluginModuleI18nQuery) {
			return $criteria;
		}
		$query = new twPluginModuleI18nQuery();
		if (null !== $modelAlias) {
			$query->setModelAlias($modelAlias);
		}
		if ($criteria instanceof Criteria) {
			$query->mergeWith($criteria);
		}
		return $query;
	}

	/**
	 * Find object by primary key
	 * <code>
	 * $obj = $c->findPk(array(12, 34), $con);
	 * </code>
	 * @param     array[$id, $culture] $key Primary key to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    twPluginModuleI18n|array|mixed the result, formatted by the current formatter
	 */
	public function findPk($key, $con = null)
	{
		if ((null !== ($obj = twPluginModuleI18nPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && $this->getFormatter()->isObjectFormatter()) {
			// the object is alredy in the instance pool
			return $obj;
		} else {
			// the object has not been requested yet, or the formatter is not an object formatter
			$criteria = $this->isKeepQuery() ? clone $this : $this;
			$stmt = $criteria
				->filterByPrimaryKey($key)
				->getSelectStatement($con);
			return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
		}
	}

	/**
	 * Find objects by primary key
	 * <code>
	 * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
	 * </code>
	 * @param     array $keys Primary keys to use for the query
	 * @param     PropelPDO $con an optional connection object
	 *
	 * @return    PropelObjectCollection|array|mixed the list of results, formatted by the current formatter
	 */
	public function findPks($keys, $con = null)
	{	
		$criteria = $this->isKeepQuery() ? clone $this : $this;
		return $this
			->filterByPrimaryKeys($keys)
			->find($con);
	}

	/**
	 * Filter the query by primary key
	 *
	 * @param     mixed $key Primary key to use for the query
	 *
	 * @return    twPluginModuleI18nQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKey($key)
	{
		$this->addUsingAlias(twPluginModuleI18nPeer::ID, $key[0], Criteria::EQUAL);
		$this->addUsingAlias(twPluginModuleI18nPeer::CULTURE, $key[1], Criteria::EQUAL);
		
		return $this;
	}

	/**
	 * Filter the query by a list of primary keys
	 *
	 * @param     array $keys The list of primary key to use for the query
	 *
	 * @return    twPluginModuleI18nQuery The current query, for fluid interface
	 */
	public function filterByPrimaryKeys($keys)
	{
		foreach ($keys as $key) {
			$cton0 = $this->getNewCriterion(twPluginModuleI18nPeer::ID, $key[0], Criteria::EQUAL);
			$cton1 = $this->getNewCriterion(twPluginModuleI18nPeer::CULTURE, $key[1], Criteria::EQUAL);
			$cton0->addAnd($cton1);
			$this->addOr($cton0);
		}
		
		return $this;
	}

	/**
	 * Filter the query on the id column
	 * 
	 * @param     int|array $id The value to use as filter.
	 *            Accepts an associative array('min' => $minValue, 'max' => $maxValue)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    twPluginModuleI18nQuery The current query, for fluid interface
	 */
	public function filterById($id = null, $comparison = null)
	{
		if (is_array($id) && null === $comparison) {
			$comparison = Criteria::IN;
		}
		return $this->addUsingAlias(twPluginModuleI18nPeer::ID, $id, $comparison);
	}

	/**
	 * Filter the query on the culture column
	 * 
	 * @param     string $culture The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    twPluginModuleI18nQuery The current query, for fluid interface
	 */
	public function filterByCulture($culture = null, $comparison = null)
	{
		if (is_array($culture)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $culture)) {
			$culture = str_replace('*', '%', $culture);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(twPluginModuleI18nPeer::CULTURE, $culture, $comparison);
	}

	/**
	 * Filter the query on the name column
	 * 
	 * @param     string $name The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    twPluginModuleI18nQuery The current query, for fluid interface
	 */
	public function filterByName($name = null, $comparison = null)
	{
		if (is_array($name)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $name)) {
			$name = str_replace('*', '%', $name);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(twPluginModuleI18nPeer::NAME, $name, $comparison);
	}

	/**
	 * Filter the query on the description column
	 * 
	 * @param     string $description The value to use as filter.
	 *            Accepts wildcards (* and % trigger a LIKE)
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    twPluginModuleI18nQuery The current query, for fluid interface
	 */
	public function filterByDescription($description = null, $comparison = null)
	{
		if (is_array($description)) {
			if (null === $comparison) {
				$comparison = Criteria::IN;
			}
		} elseif (preg_match('/[\%\*]/', $description)) {
			$description = str_replace('*', '%', $description);
			if (null === $comparison) {
				$comparison = Criteria::LIKE;
			}
		}
		return $this->addUsingAlias(twPluginModuleI18nPeer::DESCRIPTION, $description, $comparison);
	}

	/**
	 * Filter the query by a related twPluginModule object
	 *
	 * @param     twPluginModule $twPluginModule  the related object to use as filter
	 * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
	 *
	 * @return    twPluginModuleI18nQuery The current query, for fluid interface
	 */
	public function filterBytwPluginModule($twPluginModule, $comparison = null)
	{
		return $this
			->addUsingAlias(twPluginModuleI18nPeer::ID, $twPluginModule->getId(), $comparison);
	}

	/**
	 * Adds a JOIN clause to the query using the twPluginModule relation
	 * 
	 * @param     string $relationAlias optional alias for the relation
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    twPluginModuleI18nQuery The current query, for fluid interface
	 */
	public function jointwPluginModule($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		$tableMap = $this->getTableMap();
		$relationMap = $tableMap->getRelation('twPluginModule');
		
		// create a ModelJoin object for this join
		$join = new ModelJoin();
		$join->setJoinType($joinType);
		$join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
		if ($previousJoin = $this->getPreviousJoin()) {
			$join->setPreviousJoin($previousJoin);
		}
		
		// add the ModelJoin to the current object
		if($relationAlias) {
			$this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
			$this->addJoinObject($join, $relationAlias);
		} else {
			$this->addJoinObject($join, 'twPluginModule');
		}
		
		return $this;
	}

	/**
	 * Use the twPluginModule relation twPluginModule object
	 *
	 * @see       useQuery()
	 * 
	 * @param     string $relationAlias optional alias for the relation,
	 *                                   to be used as main alias in the secondary query
	 * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
	 *
	 * @return    twPluginModuleQuery A secondary query class using the current class as primary query
	 */
	public function usetwPluginModuleQuery($relationAlias = '', $joinType = Criteria::INNER_JOIN)
	{
		return $this
			->jointwPluginModule($relationAlias, $joinType)
			->useQuery($relationAlias ? $relationAlias : 'twPluginModule', 'twPluginModuleQuery');
	}

	/**
	 * Exclude object from result
	 *
	 * @param     twPluginModuleI18n $twPluginModuleI18n Object to remove from the list of results
	 *
	 * @return    twPluginModuleI18nQuery The current query, for fluid interface
	 */
	public function prune($twPluginModuleI18n = null)
	{
		if ($twPluginModuleI18n) {
			$this->addCond('pruneCond0', $this->getAliasedColName(twPluginModuleI18nPeer::ID), $twPluginModuleI18n->getId(), Criteria::NOT_EQUAL);
			$this->addCond('pruneCond1', $this->getAliasedColName(twPluginModuleI18nPeer::CULTURE), $twPluginModuleI18n->getCulture(), Criteria::NOT_EQUAL);
			$this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
	  }
	  
		return $this;
	}

} // BasetwPluginModuleI18nQuery