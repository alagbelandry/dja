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
use LeDjassa\AdsBundle\Model\InterestedUser;
use LeDjassa\AdsBundle\Model\InterestedUserPeer;
use LeDjassa\AdsBundle\Model\InterestedUserQuery;

/**
 * @method InterestedUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method InterestedUserQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method InterestedUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method InterestedUserQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method InterestedUserQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method InterestedUserQuery orderByIpAdress($order = Criteria::ASC) Order by the ip_adress column
 * @method InterestedUserQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method InterestedUserQuery orderByAdId($order = Criteria::ASC) Order by the ad_id column
 * @method InterestedUserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method InterestedUserQuery groupById() Group by the id column
 * @method InterestedUserQuery groupByName() Group by the name column
 * @method InterestedUserQuery groupByEmail() Group by the email column
 * @method InterestedUserQuery groupByPhone() Group by the phone column
 * @method InterestedUserQuery groupByMessage() Group by the message column
 * @method InterestedUserQuery groupByIpAdress() Group by the ip_adress column
 * @method InterestedUserQuery groupByCreatedAt() Group by the created_at column
 * @method InterestedUserQuery groupByAdId() Group by the ad_id column
 * @method InterestedUserQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method InterestedUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method InterestedUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method InterestedUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method InterestedUserQuery leftJoinAd($relationAlias = null) Adds a LEFT JOIN clause to the query using the Ad relation
 * @method InterestedUserQuery rightJoinAd($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Ad relation
 * @method InterestedUserQuery innerJoinAd($relationAlias = null) Adds a INNER JOIN clause to the query using the Ad relation
 *
 * @method InterestedUser findOne(PropelPDO $con = null) Return the first InterestedUser matching the query
 * @method InterestedUser findOneOrCreate(PropelPDO $con = null) Return the first InterestedUser matching the query, or a new InterestedUser object populated from the query conditions when no match is found
 *
 * @method InterestedUser findOneByName(string $name) Return the first InterestedUser filtered by the name column
 * @method InterestedUser findOneByEmail(string $email) Return the first InterestedUser filtered by the email column
 * @method InterestedUser findOneByPhone(string $phone) Return the first InterestedUser filtered by the phone column
 * @method InterestedUser findOneByMessage(string $message) Return the first InterestedUser filtered by the message column
 * @method InterestedUser findOneByIpAdress(string $ip_adress) Return the first InterestedUser filtered by the ip_adress column
 * @method InterestedUser findOneByCreatedAt(string $created_at) Return the first InterestedUser filtered by the created_at column
 * @method InterestedUser findOneByAdId(int $ad_id) Return the first InterestedUser filtered by the ad_id column
 * @method InterestedUser findOneByUpdatedAt(string $updated_at) Return the first InterestedUser filtered by the updated_at column
 *
 * @method array findById(int $id) Return InterestedUser objects filtered by the id column
 * @method array findByName(string $name) Return InterestedUser objects filtered by the name column
 * @method array findByEmail(string $email) Return InterestedUser objects filtered by the email column
 * @method array findByPhone(string $phone) Return InterestedUser objects filtered by the phone column
 * @method array findByMessage(string $message) Return InterestedUser objects filtered by the message column
 * @method array findByIpAdress(string $ip_adress) Return InterestedUser objects filtered by the ip_adress column
 * @method array findByCreatedAt(string $created_at) Return InterestedUser objects filtered by the created_at column
 * @method array findByAdId(int $ad_id) Return InterestedUser objects filtered by the ad_id column
 * @method array findByUpdatedAt(string $updated_at) Return InterestedUser objects filtered by the updated_at column
 */
abstract class BaseInterestedUserQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseInterestedUserQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'LeDjassa\\AdsBundle\\Model\\InterestedUser', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new InterestedUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     InterestedUserQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return InterestedUserQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof InterestedUserQuery) {
            return $criteria;
        }
        $query = new InterestedUserQuery();
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
     * @return   InterestedUser|InterestedUser[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = InterestedUserPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(InterestedUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   InterestedUser A model object, or null if the key is not found
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
     * @return   InterestedUser A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `NAME`, `EMAIL`, `PHONE`, `MESSAGE`, `IP_ADRESS`, `CREATED_AT`, `AD_ID`, `UPDATED_AT` FROM `interested_user` WHERE `ID` = :p0';
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
            $obj = new InterestedUser();
            $obj->hydrate($row);
            InterestedUserPeer::addInstanceToPool($obj, (string) $key);
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
     * @return InterestedUser|InterestedUser[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|InterestedUser[]|mixed the list of results, formatted by the current formatter
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
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(InterestedUserPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(InterestedUserPeer::ID, $keys, Criteria::IN);
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
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(InterestedUserPeer::ID, $id, $comparison);
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
     * @return InterestedUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(InterestedUserPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(InterestedUserPeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(InterestedUserPeer::PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%'); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $message)) {
                $message = str_replace('*', '%', $message);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(InterestedUserPeer::MESSAGE, $message, $comparison);
    }

    /**
     * Filter the query on the ip_adress column
     *
     * Example usage:
     * <code>
     * $query->filterByIpAdress('fooValue');   // WHERE ip_adress = 'fooValue'
     * $query->filterByIpAdress('%fooValue%'); // WHERE ip_adress LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ipAdress The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterByIpAdress($ipAdress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ipAdress)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ipAdress)) {
                $ipAdress = str_replace('*', '%', $ipAdress);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(InterestedUserPeer::IP_ADRESS, $ipAdress, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(InterestedUserPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(InterestedUserPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InterestedUserPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterByAdId($adId = null, $comparison = null)
    {
        if (is_array($adId)) {
            $useMinMax = false;
            if (isset($adId['min'])) {
                $this->addUsingAlias(InterestedUserPeer::AD_ID, $adId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($adId['max'])) {
                $this->addUsingAlias(InterestedUserPeer::AD_ID, $adId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InterestedUserPeer::AD_ID, $adId, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(InterestedUserPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(InterestedUserPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(InterestedUserPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related Ad object
     *
     * @param   Ad|PropelObjectCollection $ad The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   InterestedUserQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByAd($ad, $comparison = null)
    {
        if ($ad instanceof Ad) {
            return $this
                ->addUsingAlias(InterestedUserPeer::AD_ID, $ad->getId(), $comparison);
        } elseif ($ad instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(InterestedUserPeer::AD_ID, $ad->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return InterestedUserQuery The current query, for fluid interface
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
     * @param   InterestedUser $interestedUser Object to remove from the list of results
     *
     * @return InterestedUserQuery The current query, for fluid interface
     */
    public function prune($interestedUser = null)
    {
        if ($interestedUser) {
            $this->addUsingAlias(InterestedUserPeer::ID, $interestedUser->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     InterestedUserQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(InterestedUserPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     InterestedUserQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(InterestedUserPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     InterestedUserQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(InterestedUserPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     InterestedUserQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(InterestedUserPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     InterestedUserQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(InterestedUserPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     InterestedUserQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(InterestedUserPeer::CREATED_AT);
    }
}
