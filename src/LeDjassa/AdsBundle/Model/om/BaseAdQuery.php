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
use LeDjassa\AdsBundle\Model\City;
use LeDjassa\AdsBundle\Model\InterestedUser;
use LeDjassa\AdsBundle\Model\PictureAd;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Model\UserType;

/**
 * @method AdQuery orderById($order = Criteria::ASC) Order by the id column
 * @method AdQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method AdQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method AdQuery orderByPrice($order = Criteria::ASC) Order by the price column
 * @method AdQuery orderByStatut($order = Criteria::ASC) Order by the statut column
 * @method AdQuery orderByUserName($order = Criteria::ASC) Order by the user_name column
 * @method AdQuery orderByUserEmail($order = Criteria::ASC) Order by the user_email column
 * @method AdQuery orderByUserPassword($order = Criteria::ASC) Order by the user_password column
 * @method AdQuery orderByUserSalt($order = Criteria::ASC) Order by the user_salt column
 * @method AdQuery orderByUserPhone($order = Criteria::ASC) Order by the user_phone column
 * @method AdQuery orderByUserIpAdress($order = Criteria::ASC) Order by the user_ip_adress column
 * @method AdQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method AdQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method AdQuery orderByAdTypeId($order = Criteria::ASC) Order by the ad_type_id column
 * @method AdQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method AdQuery orderByUserTypeId($order = Criteria::ASC) Order by the user_type_id column
 * @method AdQuery orderByCityId($order = Criteria::ASC) Order by the city_id column
 * @method AdQuery orderByQuarterId($order = Criteria::ASC) Order by the quarter_id column
 * @method AdQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 *
 * @method AdQuery groupById() Group by the id column
 * @method AdQuery groupByTitle() Group by the title column
 * @method AdQuery groupByDescription() Group by the description column
 * @method AdQuery groupByPrice() Group by the price column
 * @method AdQuery groupByStatut() Group by the statut column
 * @method AdQuery groupByUserName() Group by the user_name column
 * @method AdQuery groupByUserEmail() Group by the user_email column
 * @method AdQuery groupByUserPassword() Group by the user_password column
 * @method AdQuery groupByUserSalt() Group by the user_salt column
 * @method AdQuery groupByUserPhone() Group by the user_phone column
 * @method AdQuery groupByUserIpAdress() Group by the user_ip_adress column
 * @method AdQuery groupByCreatedAt() Group by the created_at column
 * @method AdQuery groupByUpdatedAt() Group by the updated_at column
 * @method AdQuery groupByAdTypeId() Group by the ad_type_id column
 * @method AdQuery groupByCategoryId() Group by the category_id column
 * @method AdQuery groupByUserTypeId() Group by the user_type_id column
 * @method AdQuery groupByCityId() Group by the city_id column
 * @method AdQuery groupByQuarterId() Group by the quarter_id column
 * @method AdQuery groupBySlug() Group by the slug column
 *
 * @method AdQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method AdQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method AdQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method AdQuery leftJoinCity($relationAlias = null) Adds a LEFT JOIN clause to the query using the City relation
 * @method AdQuery rightJoinCity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the City relation
 * @method AdQuery innerJoinCity($relationAlias = null) Adds a INNER JOIN clause to the query using the City relation
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
 * @method AdQuery leftJoinInterestedUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the InterestedUser relation
 * @method AdQuery rightJoinInterestedUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the InterestedUser relation
 * @method AdQuery innerJoinInterestedUser($relationAlias = null) Adds a INNER JOIN clause to the query using the InterestedUser relation
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
 * @method Ad findOneByStatut(int $statut) Return the first Ad filtered by the statut column
 * @method Ad findOneByUserName(string $user_name) Return the first Ad filtered by the user_name column
 * @method Ad findOneByUserEmail(string $user_email) Return the first Ad filtered by the user_email column
 * @method Ad findOneByUserPassword(string $user_password) Return the first Ad filtered by the user_password column
 * @method Ad findOneByUserSalt(string $user_salt) Return the first Ad filtered by the user_salt column
 * @method Ad findOneByUserPhone(string $user_phone) Return the first Ad filtered by the user_phone column
 * @method Ad findOneByUserIpAdress(string $user_ip_adress) Return the first Ad filtered by the user_ip_adress column
 * @method Ad findOneByCreatedAt(string $created_at) Return the first Ad filtered by the created_at column
 * @method Ad findOneByUpdatedAt(string $updated_at) Return the first Ad filtered by the updated_at column
 * @method Ad findOneByAdTypeId(int $ad_type_id) Return the first Ad filtered by the ad_type_id column
 * @method Ad findOneByCategoryId(int $category_id) Return the first Ad filtered by the category_id column
 * @method Ad findOneByUserTypeId(int $user_type_id) Return the first Ad filtered by the user_type_id column
 * @method Ad findOneByCityId(int $city_id) Return the first Ad filtered by the city_id column
 * @method Ad findOneByQuarterId(int $quarter_id) Return the first Ad filtered by the quarter_id column
 * @method Ad findOneBySlug(string $slug) Return the first Ad filtered by the slug column
 *
 * @method array findById(int $id) Return Ad objects filtered by the id column
 * @method array findByTitle(string $title) Return Ad objects filtered by the title column
 * @method array findByDescription(string $description) Return Ad objects filtered by the description column
 * @method array findByPrice(string $price) Return Ad objects filtered by the price column
 * @method array findByStatut(int $statut) Return Ad objects filtered by the statut column
 * @method array findByUserName(string $user_name) Return Ad objects filtered by the user_name column
 * @method array findByUserEmail(string $user_email) Return Ad objects filtered by the user_email column
 * @method array findByUserPassword(string $user_password) Return Ad objects filtered by the user_password column
 * @method array findByUserSalt(string $user_salt) Return Ad objects filtered by the user_salt column
 * @method array findByUserPhone(string $user_phone) Return Ad objects filtered by the user_phone column
 * @method array findByUserIpAdress(string $user_ip_adress) Return Ad objects filtered by the user_ip_adress column
 * @method array findByCreatedAt(string $created_at) Return Ad objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Ad objects filtered by the updated_at column
 * @method array findByAdTypeId(int $ad_type_id) Return Ad objects filtered by the ad_type_id column
 * @method array findByCategoryId(int $category_id) Return Ad objects filtered by the category_id column
 * @method array findByUserTypeId(int $user_type_id) Return Ad objects filtered by the user_type_id column
 * @method array findByCityId(int $city_id) Return Ad objects filtered by the city_id column
 * @method array findByQuarterId(int $quarter_id) Return Ad objects filtered by the quarter_id column
 * @method array findBySlug(string $slug) Return Ad objects filtered by the slug column
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
        $sql = 'SELECT `id`, `title`, `description`, `price`, `statut`, `user_name`, `user_email`, `user_password`, `user_salt`, `user_phone`, `user_ip_adress`, `created_at`, `updated_at`, `ad_type_id`, `category_id`, `user_type_id`, `city_id`, `quarter_id`, `slug` FROM `ad` WHERE `id` = :p0';
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
     * Filter the query on the statut column
     *
     * Example usage:
     * <code>
     * $query->filterByStatut(1234); // WHERE statut = 1234
     * $query->filterByStatut(array(12, 34)); // WHERE statut IN (12, 34)
     * $query->filterByStatut(array('min' => 12)); // WHERE statut > 12
     * </code>
     *
     * @param     mixed $statut The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByStatut($statut = null, $comparison = null)
    {
        if (is_array($statut)) {
            $useMinMax = false;
            if (isset($statut['min'])) {
                $this->addUsingAlias(AdPeer::STATUT, $statut['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($statut['max'])) {
                $this->addUsingAlias(AdPeer::STATUT, $statut['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdPeer::STATUT, $statut, $comparison);
    }

    /**
     * Filter the query on the user_name column
     *
     * Example usage:
     * <code>
     * $query->filterByUserName('fooValue');   // WHERE user_name = 'fooValue'
     * $query->filterByUserName('%fooValue%'); // WHERE user_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $userName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByUserName($userName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $userName)) {
                $userName = str_replace('*', '%', $userName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::USER_NAME, $userName, $comparison);
    }

    /**
     * Filter the query on the user_email column
     *
     * Example usage:
     * <code>
     * $query->filterByUserEmail('fooValue');   // WHERE user_email = 'fooValue'
     * $query->filterByUserEmail('%fooValue%'); // WHERE user_email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $userEmail The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByUserEmail($userEmail = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userEmail)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $userEmail)) {
                $userEmail = str_replace('*', '%', $userEmail);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::USER_EMAIL, $userEmail, $comparison);
    }

    /**
     * Filter the query on the user_password column
     *
     * Example usage:
     * <code>
     * $query->filterByUserPassword('fooValue');   // WHERE user_password = 'fooValue'
     * $query->filterByUserPassword('%fooValue%'); // WHERE user_password LIKE '%fooValue%'
     * </code>
     *
     * @param     string $userPassword The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByUserPassword($userPassword = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userPassword)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $userPassword)) {
                $userPassword = str_replace('*', '%', $userPassword);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::USER_PASSWORD, $userPassword, $comparison);
    }

    /**
     * Filter the query on the user_salt column
     *
     * Example usage:
     * <code>
     * $query->filterByUserSalt('fooValue');   // WHERE user_salt = 'fooValue'
     * $query->filterByUserSalt('%fooValue%'); // WHERE user_salt LIKE '%fooValue%'
     * </code>
     *
     * @param     string $userSalt The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByUserSalt($userSalt = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userSalt)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $userSalt)) {
                $userSalt = str_replace('*', '%', $userSalt);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::USER_SALT, $userSalt, $comparison);
    }

    /**
     * Filter the query on the user_phone column
     *
     * Example usage:
     * <code>
     * $query->filterByUserPhone('fooValue');   // WHERE user_phone = 'fooValue'
     * $query->filterByUserPhone('%fooValue%'); // WHERE user_phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $userPhone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByUserPhone($userPhone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userPhone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $userPhone)) {
                $userPhone = str_replace('*', '%', $userPhone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::USER_PHONE, $userPhone, $comparison);
    }

    /**
     * Filter the query on the user_ip_adress column
     *
     * Example usage:
     * <code>
     * $query->filterByUserIpAdress('fooValue');   // WHERE user_ip_adress = 'fooValue'
     * $query->filterByUserIpAdress('%fooValue%'); // WHERE user_ip_adress LIKE '%fooValue%'
     * </code>
     *
     * @param     string $userIpAdress The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByUserIpAdress($userIpAdress = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($userIpAdress)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $userIpAdress)) {
                $userIpAdress = str_replace('*', '%', $userIpAdress);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::USER_IP_ADRESS, $userIpAdress, $comparison);
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
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByCityId($cityId = null, $comparison = null)
    {
        if (is_array($cityId)) {
            $useMinMax = false;
            if (isset($cityId['min'])) {
                $this->addUsingAlias(AdPeer::CITY_ID, $cityId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cityId['max'])) {
                $this->addUsingAlias(AdPeer::CITY_ID, $cityId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdPeer::CITY_ID, $cityId, $comparison);
    }

    /**
     * Filter the query on the quarter_id column
     *
     * Example usage:
     * <code>
     * $query->filterByQuarterId(1234); // WHERE quarter_id = 1234
     * $query->filterByQuarterId(array(12, 34)); // WHERE quarter_id IN (12, 34)
     * $query->filterByQuarterId(array('min' => 12)); // WHERE quarter_id > 12
     * </code>
     *
     * @see       filterByQuarter()
     *
     * @param     mixed $quarterId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterByQuarterId($quarterId = null, $comparison = null)
    {
        if (is_array($quarterId)) {
            $useMinMax = false;
            if (isset($quarterId['min'])) {
                $this->addUsingAlias(AdPeer::QUARTER_ID, $quarterId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($quarterId['max'])) {
                $this->addUsingAlias(AdPeer::QUARTER_ID, $quarterId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AdPeer::QUARTER_ID, $quarterId, $comparison);
    }

    /**
     * Filter the query on the slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE slug = 'fooValue'
     * $query->filterBySlug('%fooValue%'); // WHERE slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $slug)) {
                $slug = str_replace('*', '%', $slug);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(AdPeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query by a related City object
     *
     * @param   City|PropelObjectCollection $city The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AdQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByCity($city, $comparison = null)
    {
        if ($city instanceof City) {
            return $this
                ->addUsingAlias(AdPeer::CITY_ID, $city->getId(), $comparison);
        } elseif ($city instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AdPeer::CITY_ID, $city->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return AdQuery The current query, for fluid interface
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
     * @param   Quarter|PropelObjectCollection $quarter The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AdQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByQuarter($quarter, $comparison = null)
    {
        if ($quarter instanceof Quarter) {
            return $this
                ->addUsingAlias(AdPeer::QUARTER_ID, $quarter->getId(), $comparison);
        } elseif ($quarter instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AdPeer::QUARTER_ID, $quarter->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * Filter the query by a related InterestedUser object
     *
     * @param   InterestedUser|PropelObjectCollection $interestedUser  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   AdQuery The current query, for fluid interface
     * @throws   PropelException - if the provided filter is invalid.
     */
    public function filterByInterestedUser($interestedUser, $comparison = null)
    {
        if ($interestedUser instanceof InterestedUser) {
            return $this
                ->addUsingAlias(AdPeer::ID, $interestedUser->getAdId(), $comparison);
        } elseif ($interestedUser instanceof PropelObjectCollection) {
            return $this
                ->useInterestedUserQuery()
                ->filterByPrimaryKeys($interestedUser->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByInterestedUser() only accepts arguments of type InterestedUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the InterestedUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AdQuery The current query, for fluid interface
     */
    public function joinInterestedUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('InterestedUser');

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
            $this->addJoinObject($join, 'InterestedUser');
        }

        return $this;
    }

    /**
     * Use the InterestedUser relation InterestedUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \LeDjassa\AdsBundle\Model\InterestedUserQuery A secondary query class using the current class as primary query
     */
    public function useInterestedUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinInterestedUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'InterestedUser', '\LeDjassa\AdsBundle\Model\InterestedUserQuery');
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

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     AdQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(AdPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     AdQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(AdPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     AdQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(AdPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     AdQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(AdPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     AdQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(AdPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     AdQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(AdPeer::CREATED_AT);
    }
    // sluggable behavior

    /**
     * Find one object based on its slug
     *
     * @param     string $slug The value to use as filter.
     * @param     PropelPDO $con The optional connection object
     *
     * @return    Ad the result, formatted by the current formatter
     */
    public function findOneBySlug($slug, $con = null)
    {
        return $this->filterBySlug($slug)->findOne($con);
    }

}
