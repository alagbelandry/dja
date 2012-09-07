<?php

namespace LeDjassa\AdsBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\City;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Model\QuarterPeer;
use LeDjassa\AdsBundle\Model\QuarterQuery;

/**
 * @method QuarterQuery orderById($order = Criteria::ASC) Order by the id column
 * @method QuarterQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method QuarterQuery orderByCityId($order = Criteria::ASC) Order by the city_id column
 * @method QuarterQuery orderByAdId($order = Criteria::ASC) Order by the ad_id column
 *
 * @method QuarterQuery groupById() Group by the id column
 * @method QuarterQuery groupByName() Group by the name column
 * @method QuarterQuery groupByCityId() Group by the city_id column
 * @method QuarterQuery groupByAdId() Group by the ad_id column
 *
 * @method QuarterQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method QuarterQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method QuarterQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method QuarterQuery leftJoinCity($relationAlias = null) Adds a LEFT JOIN clause to the query using the City relation
 * @method QuarterQuery rightJoinCity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the City relation
 * @method QuarterQuery innerJoinCity($relationAlias = null) Adds a INNER JOIN clause to the query using the City relation
 *
 * @method QuarterQuery leftJoinAd($relationAlias = null) Adds a LEFT JOIN clause to the query using the Ad relation
 * @method QuarterQuery rightJoinAd($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Ad relation
 * @method QuarterQuery innerJoinAd($relationAlias = null) Adds a INNER JOIN clause to the query using the Ad relation
 *
 * @method Quarter findOne(PropelPDO $con = null) Return the first Quarter matching the query
 * @method Quarter findOneOrCreate(PropelPDO $con = null) Return the first Quarter matching the query, or a new Quarter object populated from the query conditions when no match is found
 *
 * @method Quarter findOneByName(string $name) Return the first Quarter filtered by the name column
 * @method Quarter findOneByCityId(int $city_id) Return the first Quarter filtered by the city_id column
 * @method Quarter findOneByAdId(int $ad_id) Return the first Quarter filtered by the ad_id column
 *
 * @method array findById(int $id) Return Quarter objects filtered by the id column
 * @method array findByName(string $name) Return Quarter objects filtered by the name column
 * @method array findByCityId(int $city_id) Return Quarter objects filtered by the city_id column
 * @method array findByAdId(int $ad_id) Return Quarter objects filtered by the ad_id column
 */
abstract class BaseQuarterQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseQuarterQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'LeDjassa\\AdsBundle\\Model\\Quarter', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new QuarterQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     QuarterQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return QuarterQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof QuarterQuery) {
            return $criteria;
        }
        $query = new QuarterQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Quarter|Quarter[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = QuarterPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(QuarterPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   Quarter A model object, or null if the key is not found
     * @throws   PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return   Quarter A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `NAME`, `CITY_ID`, `AD_ID` FROM `quarter` WHERE `ID` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Quarter();
            $obj->hydrate($row);
            QuarterPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Quarter|Quarter[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Quarter[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return QuarterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(QuarterPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return QuarterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(QuarterPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuarterQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(QuarterPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuarterQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(QuarterPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the city_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCityId(1234); // WHERE city_id = 1234
     * $query->filterByCityId(array(12, 34)); // WHERE city_id IN (12, 34)
     * $query->filterByCityId(array('min' => 12)); // WHERE city_id > 12
     * </code>
     *
     * @see       filterByCity()
     *
     * @param     mixed $cityId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuarterQuery The current query, for fluid interface
     */
    public function filterByCityId($cityId = null, $comparison = null)
    {
        if (is_array($cityId)) {
            $useMinMax = false;
            if (isset($cityId['min'])) {
                $this->addUsingAlias(QuarterPeer::CITY_ID, $cityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cityId['max'])) {
                $this->addUsingAlias(QuarterPeer::CITY_ID, $cityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuarterPeer::CITY_ID, $cityId, $comparison);
    }

    /**
     * Filter the query on the ad_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAdId(1234); // WHERE ad_id = 1234
     * $query->filterByAdId(array(12, 34)); // WHERE ad_id IN (12, 34)
     * $query->filterByAdId(array('min' => 12)); // WHERE ad_id > 12
     * </code>
     *
     * @see       filterByAd()
     *
     * @param     mixed $adId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return QuarterQuery The current query, for fluid interface
     */
    public function filterByAdId($adId = null, $comparison = null)
    {
        if (is_array($adId)) {
            $useMinMax = false;
            if (isset($adId['min'])) {
                $this->addUsingAlias(QuarterPeer::AD_ID, $adId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($adId['max'])) {
                $this->addUsingAlias(QuarterPeer::AD_ID, $adId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(QuarterPeer::AD_ID, $adId, $comparison);
    }

    /**
     * Filter the query by a related City object
     *
     * @param   City|PropelObjectCollection $city The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuarterQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByCity($city, $comparison = null)
    {
        if ($city instanceof City) {
            return $this
                ->addUsingAlias(QuarterPeer::CITY_ID, $city->getId(), $comparison);
        } elseif ($city instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QuarterPeer::CITY_ID, $city->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCity() only accepts arguments of type City or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the City relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return QuarterQuery The current query, for fluid interface
     */
    public function joinCity($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('City');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'City');
        }

        return $this;
    }

    /**
     * Use the City relation City object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LeDjassa\AdsBundle\Model\CityQuery A secondary query class using the current class as primary query
     */
    public function useCityQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCity($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'City', '\LeDjassa\AdsBundle\Model\CityQuery');
    }

    /**
     * Filter the query by a related Ad object
     *
     * @param   Ad|PropelObjectCollection $ad The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   QuarterQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByAd($ad, $comparison = null)
    {
        if ($ad instanceof Ad) {
            return $this
                ->addUsingAlias(QuarterPeer::AD_ID, $ad->getId(), $comparison);
        } elseif ($ad instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(QuarterPeer::AD_ID, $ad->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAd() only accepts arguments of type Ad or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Ad relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return QuarterQuery The current query, for fluid interface
     */
    public function joinAd($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Ad');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Ad');
        }

        return $this;
    }

    /**
     * Use the Ad relation Ad object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LeDjassa\AdsBundle\Model\AdQuery A secondary query class using the current class as primary query
     */
    public function useAdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAd($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Ad', '\LeDjassa\AdsBundle\Model\AdQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Quarter $quarter Object to remove from the list of results
     *
     * @return QuarterQuery The current query, for fluid interface
     */
    public function prune($quarter = null)
    {
        if ($quarter) {
            $this->addUsingAlias(QuarterPeer::ID, $quarter->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
