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
use LeDjassa\AdsBundle\Model\AdPeer;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Model\AdType;
use LeDjassa\AdsBundle\Model\Category;
use LeDjassa\AdsBundle\Model\PictureAd;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Model\User;
use LeDjassa\AdsBundle\Model\UserType;

/**
 * @method AdQuery orderById($order = Criteria::ASC) Order by the id column
 * @method AdQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method AdQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method AdQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method AdQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method AdQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method AdQuery orderByAdTypeId($order = Criteria::ASC) Order by the ad_type_id column
 * @method AdQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method AdQuery orderByUserTypeId($order = Criteria::ASC) Order by the user_type_id column
 * @method AdQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 *
 * @method AdQuery groupById() Group by the id column
 * @method AdQuery groupByTitle() Group by the title column
 * @method AdQuery groupByDescription() Group by the description column
 * @method AdQuery groupByPrice() Group by the price column
 * @method AdQuery groupByCreatedAt() Group by the created_at column
 * @method AdQuery groupByUpdatedAt() Group by the updated_at column
 * @method AdQuery groupByAdTypeId() Group by the ad_type_id column
 * @method AdQuery groupByCategoryId() Group by the category_id column
 * @method AdQuery groupByUserTypeId() Group by the user_type_id column
 * @method AdQuery groupByUserId() Group by the user_id column
 *
 * @method AdQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method AdQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method AdQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method AdQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method AdQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method AdQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method AdQuery leftJoinUserType($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserType relation
 * @method AdQuery rightJoinUserType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserType relation
 * @method AdQuery innerJoinUserType($relationAlias = null) Adds a INNER JOIN clause to the query using the UserType relation
 *
 * @method AdQuery leftJoinAdType($relationAlias = null) Adds a LEFT JOIN clause to the query using the AdType relation
 * @method AdQuery rightJoinAdType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AdType relation
 * @method AdQuery innerJoinAdType($relationAlias = null) Adds a INNER JOIN clause to the query using the AdType relation
 *
 * @method AdQuery leftJoinCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Category relation
 * @method AdQuery rightJoinCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Category relation
 * @method AdQuery innerJoinCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Category relation
 *
 * @method AdQuery leftJoinQuarter($relationAlias = null) Adds a LEFT JOIN clause to the query using the Quarter relation
 * @method AdQuery rightJoinQuarter($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Quarter relation
 * @method AdQuery innerJoinQuarter($relationAlias = null) Adds a INNER JOIN clause to the query using the Quarter relation
 *
 * @method AdQuery leftJoinPictureAd($relationAlias = null) Adds a LEFT JOIN clause to the query using the PictureAd relation
 * @method AdQuery rightJoinPictureAd($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PictureAd relation
 * @method AdQuery innerJoinPictureAd($relationAlias = null) Adds a INNER JOIN clause to the query using the PictureAd relation
 *
 * @method Ad findOne(PropelPDO $con = null) Return the first Ad matching the query
 * @method Ad findOneOrCreate(PropelPDO $con = null) Return the first Ad matching the query, or a new Ad object populated from the query conditions when no match is found
 *
 * @method Ad findOneByTitle(string $title) Return the first Ad filtered by the title column
 * @method Ad findOneByDescription(string $description) Return the first Ad filtered by the description column
 * @method Ad findOneByPrice(string $price) Return the first Ad filtered by the price column
 * @method Ad findOneByCreatedAt(string $created_at) Return the first Ad filtered by the created_at column
 * @method Ad findOneByUpdatedAt(string $updated_at) Return the first Ad filtered by the updated_at column
 * @method Ad findOneByAdTypeId(int $ad_type_id) Return the first Ad filtered by the ad_type_id column
 * @method Ad findOneByCategoryId(int $category_id) Return the first Ad filtered by the category_id column
 * @method Ad findOneByUserTypeId(int $user_type_id) Return the first Ad filtered by the user_type_id column
 * @method Ad findOneByUserId(int $user_id) Return the first Ad filtered by the user_id column
 *
 * @method array findById(int $id) Return Ad objects filtered by the id column
 * @method array findByTitle(string $title) Return Ad objects filtered by the title column
 * @method array findByDescription(string $description) Return Ad objects filtered by the description column
 * @method array findByPrice(string $price) Return Ad objects filtered by the price column
 * @method array findByCreatedAt(string $created_at) Return Ad objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Ad objects filtered by the updated_at column
 * @method array findByAdTypeId(int $ad_type_id) Return Ad objects filtered by the ad_type_id column
 * @method array findByCategoryId(int $category_id) Return Ad objects filtered by the category_id column
 * @method array findByUserTypeId(int $user_type_id) Return Ad objects filtered by the user_type_id column
 * @method array findByUserId(int $user_id) Return Ad objects filtered by the user_id column
 */
abstract class BaseAdQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseAdQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'LeDjassa\\AdsBundle\\Model\\Ad', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new AdQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     AdQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return AdQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AdQuery) {
            return $criteria;
        }
        $query = new AdQuery();
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
     * @return   Ad|Ad[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AdPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return   Ad A model object, or null if the key is not found
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
     * @return   Ad A model object, or null if the key is not found
     * @throws   PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `ID`, `TITLE`, `DESCRIPTION`, `PRICE`, `CREATED_AT`, `UPDATED_AT`, `AD_TYPE_ID`, `CATEGORY_ID`, `USER_TYPE_ID`, `USER_ID` FROM `ad` WHERE `ID` = :p0';
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
            $obj = new Ad();
            $obj->hydrate($row);
            AdPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Ad|Ad[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Ad[]|mixed the list of results, formatted by the current formatter
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
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AdPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AdPeer::ID, $keys, Criteria::IN);
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
     * @return AdQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id) && null === $comparison) {
            $comparison = Criteria::IN;
        }

        return $this->addUsingAlias(AdPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the price column
     *
     * Example usage:
     * <code>
     * $query->filterByPrice('fooValue');   // WHERE price = 'fooValue'
     * $query->filterByPrice('%fooValue%'); // WHERE price LIKE '%fooValue%'
     * </code>
     *
     * @param     string $price The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByPrice($price = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($price)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $price)) {
                $price = str_replace('*', '%', $price);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::PRICE, $price, $comparison);
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
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(AdPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(AdPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(AdPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(AdPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the ad_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAdTypeId(1234); // WHERE ad_type_id = 1234
     * $query->filterByAdTypeId(array(12, 34)); // WHERE ad_type_id IN (12, 34)
     * $query->filterByAdTypeId(array('min' => 12)); // WHERE ad_type_id > 12
     * </code>
     *
     * @see       filterByAdType()
     *
     * @param     mixed $adTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByAdTypeId($adTypeId = null, $comparison = null)
    {
        if (is_array($adTypeId)) {
            $useMinMax = false;
            if (isset($adTypeId['min'])) {
                $this->addUsingAlias(AdPeer::AD_TYPE_ID, $adTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($adTypeId['max'])) {
                $this->addUsingAlias(AdPeer::AD_TYPE_ID, $adTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdPeer::AD_TYPE_ID, $adTypeId, $comparison);
    }

    /**
     * Filter the query on the category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryId(1234); // WHERE category_id = 1234
     * $query->filterByCategoryId(array(12, 34)); // WHERE category_id IN (12, 34)
     * $query->filterByCategoryId(array('min' => 12)); // WHERE category_id > 12
     * </code>
     *
     * @see       filterByCategory()
     *
     * @param     mixed $categoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByCategoryId($categoryId = null, $comparison = null)
    {
        if (is_array($categoryId)) {
            $useMinMax = false;
            if (isset($categoryId['min'])) {
                $this->addUsingAlias(AdPeer::CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(AdPeer::CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdPeer::CATEGORY_ID, $categoryId, $comparison);
    }

    /**
     * Filter the query on the user_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserTypeId(1234); // WHERE user_type_id = 1234
     * $query->filterByUserTypeId(array(12, 34)); // WHERE user_type_id IN (12, 34)
     * $query->filterByUserTypeId(array('min' => 12)); // WHERE user_type_id > 12
     * </code>
     *
     * @see       filterByUserType()
     *
     * @param     mixed $userTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByUserTypeId($userTypeId = null, $comparison = null)
    {
        if (is_array($userTypeId)) {
            $useMinMax = false;
            if (isset($userTypeId['min'])) {
                $this->addUsingAlias(AdPeer::USER_TYPE_ID, $userTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userTypeId['max'])) {
                $this->addUsingAlias(AdPeer::USER_TYPE_ID, $userTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdPeer::USER_TYPE_ID, $userTypeId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(AdPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(AdPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdPeer::USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AdQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(AdPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AdPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LeDjassa\AdsBundle\Model\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\LeDjassa\AdsBundle\Model\UserQuery');
    }

    /**
     * Filter the query by a related UserType object
     *
     * @param   UserType|PropelObjectCollection $userType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AdQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByUserType($userType, $comparison = null)
    {
        if ($userType instanceof UserType) {
            return $this
                ->addUsingAlias(AdPeer::USER_TYPE_ID, $userType->getId(), $comparison);
        } elseif ($userType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AdPeer::USER_TYPE_ID, $userType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUserType() only accepts arguments of type UserType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function joinUserType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserType');

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
            $this->addJoinObject($join, 'UserType');
        }

        return $this;
    }

    /**
     * Use the UserType relation UserType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LeDjassa\AdsBundle\Model\UserTypeQuery A secondary query class using the current class as primary query
     */
    public function useUserTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUserType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserType', '\LeDjassa\AdsBundle\Model\UserTypeQuery');
    }

    /**
     * Filter the query by a related AdType object
     *
     * @param   AdType|PropelObjectCollection $adType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AdQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByAdType($adType, $comparison = null)
    {
        if ($adType instanceof AdType) {
            return $this
                ->addUsingAlias(AdPeer::AD_TYPE_ID, $adType->getId(), $comparison);
        } elseif ($adType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AdPeer::AD_TYPE_ID, $adType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAdType() only accepts arguments of type AdType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AdType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function joinAdType($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AdType');

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
            $this->addJoinObject($join, 'AdType');
        }

        return $this;
    }

    /**
     * Use the AdType relation AdType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LeDjassa\AdsBundle\Model\AdTypeQuery A secondary query class using the current class as primary query
     */
    public function useAdTypeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAdType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AdType', '\LeDjassa\AdsBundle\Model\AdTypeQuery');
    }

    /**
     * Filter the query by a related Category object
     *
     * @param   Category|PropelObjectCollection $category The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AdQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByCategory($category, $comparison = null)
    {
        if ($category instanceof Category) {
            return $this
                ->addUsingAlias(AdPeer::CATEGORY_ID, $category->getId(), $comparison);
        } elseif ($category instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AdPeer::CATEGORY_ID, $category->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCategory() only accepts arguments of type Category or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Category relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function joinCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Category');

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
            $this->addJoinObject($join, 'Category');
        }

        return $this;
    }

    /**
     * Use the Category relation Category object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LeDjassa\AdsBundle\Model\CategoryQuery A secondary query class using the current class as primary query
     */
    public function useCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Category', '\LeDjassa\AdsBundle\Model\CategoryQuery');
    }

    /**
     * Filter the query by a related Quarter object
     *
     * @param   Quarter|PropelObjectCollection $quarter  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AdQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuarter($quarter, $comparison = null)
    {
        if ($quarter instanceof Quarter) {
            return $this
                ->addUsingAlias(AdPeer::ID, $quarter->getAdId(), $comparison);
        } elseif ($quarter instanceof PropelObjectCollection) {
            return $this
                ->useQuarterQuery()
                ->filterByPrimaryKeys($quarter->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByQuarter() only accepts arguments of type Quarter or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Quarter relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function joinQuarter($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Quarter');

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
            $this->addJoinObject($join, 'Quarter');
        }

        return $this;
    }

    /**
     * Use the Quarter relation Quarter object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LeDjassa\AdsBundle\Model\QuarterQuery A secondary query class using the current class as primary query
     */
    public function useQuarterQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinQuarter($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Quarter', '\LeDjassa\AdsBundle\Model\QuarterQuery');
    }

    /**
     * Filter the query by a related PictureAd object
     *
     * @param   PictureAd|PropelObjectCollection $pictureAd  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AdQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByPictureAd($pictureAd, $comparison = null)
    {
        if ($pictureAd instanceof PictureAd) {
            return $this
                ->addUsingAlias(AdPeer::ID, $pictureAd->getAdId(), $comparison);
        } elseif ($pictureAd instanceof PropelObjectCollection) {
            return $this
                ->usePictureAdQuery()
                ->filterByPrimaryKeys($pictureAd->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPictureAd() only accepts arguments of type PictureAd or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PictureAd relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function joinPictureAd($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PictureAd');

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
            $this->addJoinObject($join, 'PictureAd');
        }

        return $this;
    }

    /**
     * Use the PictureAd relation PictureAd object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LeDjassa\AdsBundle\Model\PictureAdQuery A secondary query class using the current class as primary query
     */
    public function usePictureAdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPictureAd($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PictureAd', '\LeDjassa\AdsBundle\Model\PictureAdQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Ad $ad Object to remove from the list of results
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function prune($ad = null)
    {
        if ($ad) {
            $this->addUsingAlias(AdPeer::ID, $ad->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
