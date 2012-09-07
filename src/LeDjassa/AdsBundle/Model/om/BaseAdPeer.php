<?php

namespace LeDjassa\AdsBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\AdPeer;
use LeDjassa\AdsBundle\Model\AdTypePeer;
use LeDjassa\AdsBundle\Model\CategoryPeer;
use LeDjassa\AdsBundle\Model\UserPeer;
use LeDjassa\AdsBundle\Model\UserTypePeer;
use LeDjassa\AdsBundle\Model\map\AdTableMap;

abstract class BaseAdPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'ad';

    /** the related Propel class for this table */
    const OM_CLASS = 'LeDjassa\\AdsBundle\\Model\\Ad';

    /** the related TableMap class for this table */
    const TM_CLASS = 'AdTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 10;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 10;

    /** the column name for the ID field */
    const ID = 'ad.ID';

    /** the column name for the TITLE field */
    const TITLE = 'ad.TITLE';

    /** the column name for the DESCRIPTION field */
    const DESCRIPTION = 'ad.DESCRIPTION';

    /** the column name for the PRICE field */
    const PRICE = 'ad.PRICE';

    /** the column name for the CREATED_AT field */
    const CREATED_AT = 'ad.CREATED_AT';

    /** the column name for the UPDATED_AT field */
    const UPDATED_AT = 'ad.UPDATED_AT';

    /** the column name for the AD_TYPE_ID field */
    const AD_TYPE_ID = 'ad.AD_TYPE_ID';

    /** the column name for the CATEGORY_ID field */
    const CATEGORY_ID = 'ad.CATEGORY_ID';

    /** the column name for the USER_TYPE_ID field */
    const USER_TYPE_ID = 'ad.USER_TYPE_ID';

    /** the column name for the USER_ID field */
    const USER_ID = 'ad.USER_ID';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of Ad objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Ad[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. AdPeer::$fieldNames[AdPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Title', 'Description', 'Price', 'CreatedAt', 'UpdatedAt', 'AdTypeId', 'CategoryId', 'UserTypeId', 'UserId', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'title', 'description', 'price', 'createdAt', 'updatedAt', 'adTypeId', 'categoryId', 'userTypeId', 'userId', ),
        BasePeer::TYPE_COLNAME => array (AdPeer::ID, AdPeer::TITLE, AdPeer::DESCRIPTION, AdPeer::PRICE, AdPeer::CREATED_AT, AdPeer::UPDATED_AT, AdPeer::AD_TYPE_ID, AdPeer::CATEGORY_ID, AdPeer::USER_TYPE_ID, AdPeer::USER_ID, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'TITLE', 'DESCRIPTION', 'PRICE', 'CREATED_AT', 'UPDATED_AT', 'AD_TYPE_ID', 'CATEGORY_ID', 'USER_TYPE_ID', 'USER_ID', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'title', 'description', 'price', 'created_at', 'updated_at', 'ad_type_id', 'category_id', 'user_type_id', 'user_id', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. AdPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Title' => 1, 'Description' => 2, 'Price' => 3, 'CreatedAt' => 4, 'UpdatedAt' => 5, 'AdTypeId' => 6, 'CategoryId' => 7, 'UserTypeId' => 8, 'UserId' => 9, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'title' => 1, 'description' => 2, 'price' => 3, 'createdAt' => 4, 'updatedAt' => 5, 'adTypeId' => 6, 'categoryId' => 7, 'userTypeId' => 8, 'userId' => 9, ),
        BasePeer::TYPE_COLNAME => array (AdPeer::ID => 0, AdPeer::TITLE => 1, AdPeer::DESCRIPTION => 2, AdPeer::PRICE => 3, AdPeer::CREATED_AT => 4, AdPeer::UPDATED_AT => 5, AdPeer::AD_TYPE_ID => 6, AdPeer::CATEGORY_ID => 7, AdPeer::USER_TYPE_ID => 8, AdPeer::USER_ID => 9, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'TITLE' => 1, 'DESCRIPTION' => 2, 'PRICE' => 3, 'CREATED_AT' => 4, 'UPDATED_AT' => 5, 'AD_TYPE_ID' => 6, 'CATEGORY_ID' => 7, 'USER_TYPE_ID' => 8, 'USER_ID' => 9, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'title' => 1, 'description' => 2, 'price' => 3, 'created_at' => 4, 'updated_at' => 5, 'ad_type_id' => 6, 'category_id' => 7, 'user_type_id' => 8, 'user_id' => 9, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = AdPeer::getFieldNames($toType);
        $key = isset(AdPeer::$fieldKeys[$fromType][$name]) ? AdPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(AdPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, AdPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return AdPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. AdPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(AdPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(AdPeer::ID);
            $criteria->addSelectColumn(AdPeer::TITLE);
            $criteria->addSelectColumn(AdPeer::DESCRIPTION);
            $criteria->addSelectColumn(AdPeer::PRICE);
            $criteria->addSelectColumn(AdPeer::CREATED_AT);
            $criteria->addSelectColumn(AdPeer::UPDATED_AT);
            $criteria->addSelectColumn(AdPeer::AD_TYPE_ID);
            $criteria->addSelectColumn(AdPeer::CATEGORY_ID);
            $criteria->addSelectColumn(AdPeer::USER_TYPE_ID);
            $criteria->addSelectColumn(AdPeer::USER_ID);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.TITLE');
            $criteria->addSelectColumn($alias . '.DESCRIPTION');
            $criteria->addSelectColumn($alias . '.PRICE');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
            $criteria->addSelectColumn($alias . '.AD_TYPE_ID');
            $criteria->addSelectColumn($alias . '.CATEGORY_ID');
            $criteria->addSelectColumn($alias . '.USER_TYPE_ID');
            $criteria->addSelectColumn($alias . '.USER_ID');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(AdPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return                 Ad
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = AdPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return AdPeer::populateObjects(AdPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement durirectly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            AdPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param      Ad $obj A Ad object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            AdPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A Ad object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Ad) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Ad object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(AdPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   Ad Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(AdPeer::$instances[$key])) {
                return AdPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool()
    {
        AdPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to ad
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = AdPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = AdPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AdPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (Ad object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = AdPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = AdPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + AdPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AdPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            AdPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related UserType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinUserType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related AdType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAdType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Category table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCategory(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of Ad objects pre-filled with their User objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Ad objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AdPeer::DATABASE_NAME);
        }

        AdPeer::addSelectColumns($criteria);
        $startcol = AdPeer::NUM_HYDRATE_COLUMNS;
        UserPeer::addSelectColumns($criteria);

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AdPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = AdPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AdPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Ad) to $obj2 (User)
                $obj2->addAd($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Ad objects pre-filled with their UserType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Ad objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinUserType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AdPeer::DATABASE_NAME);
        }

        AdPeer::addSelectColumns($criteria);
        $startcol = AdPeer::NUM_HYDRATE_COLUMNS;
        UserTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AdPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = AdPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AdPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = UserTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = UserTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    UserTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Ad) to $obj2 (UserType)
                $obj2->addAd($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Ad objects pre-filled with their AdType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Ad objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAdType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AdPeer::DATABASE_NAME);
        }

        AdPeer::addSelectColumns($criteria);
        $startcol = AdPeer::NUM_HYDRATE_COLUMNS;
        AdTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AdPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = AdPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AdPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = AdTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = AdTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = AdTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    AdTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Ad) to $obj2 (AdType)
                $obj2->addAd($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Ad objects pre-filled with their Category objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Ad objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCategory(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AdPeer::DATABASE_NAME);
        }

        AdPeer::addSelectColumns($criteria);
        $startcol = AdPeer::NUM_HYDRATE_COLUMNS;
        CategoryPeer::addSelectColumns($criteria);

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AdPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = AdPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AdPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CategoryPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CategoryPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CategoryPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CategoryPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Ad) to $obj2 (Category)
                $obj2->addAd($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of Ad objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Ad objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AdPeer::DATABASE_NAME);
        }

        AdPeer::addSelectColumns($criteria);
        $startcol2 = AdPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + UserTypePeer::NUM_HYDRATE_COLUMNS;

        AdTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AdTypePeer::NUM_HYDRATE_COLUMNS;

        CategoryPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + CategoryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AdPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AdPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AdPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined User rows

            $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = UserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Ad) to the collection in $obj2 (User)
                $obj2->addAd($obj1);
            } // if joined row not null

            // Add objects for joined UserType rows

            $key3 = UserTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = UserTypePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = UserTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    UserTypePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Ad) to the collection in $obj3 (UserType)
                $obj3->addAd($obj1);
            } // if joined row not null

            // Add objects for joined AdType rows

            $key4 = AdTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = AdTypePeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = AdTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AdTypePeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Ad) to the collection in $obj4 (AdType)
                $obj4->addAd($obj1);
            } // if joined row not null

            // Add objects for joined Category rows

            $key5 = CategoryPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = CategoryPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = CategoryPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    CategoryPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Ad) to the collection in $obj5 (Category)
                $obj5->addAd($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related User table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related UserType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptUserType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related AdType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptAdType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Category table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCategory(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AdPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AdPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of Ad objects pre-filled with all related objects except User.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Ad objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AdPeer::DATABASE_NAME);
        }

        AdPeer::addSelectColumns($criteria);
        $startcol2 = AdPeer::NUM_HYDRATE_COLUMNS;

        UserTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserTypePeer::NUM_HYDRATE_COLUMNS;

        AdTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AdTypePeer::NUM_HYDRATE_COLUMNS;

        CategoryPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + CategoryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AdPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AdPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AdPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined UserType rows

                $key2 = UserTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserTypePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserTypePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Ad) to the collection in $obj2 (UserType)
                $obj2->addAd($obj1);

            } // if joined row is not null

                // Add objects for joined AdType rows

                $key3 = AdTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = AdTypePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = AdTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    AdTypePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Ad) to the collection in $obj3 (AdType)
                $obj3->addAd($obj1);

            } // if joined row is not null

                // Add objects for joined Category rows

                $key4 = CategoryPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = CategoryPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = CategoryPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    CategoryPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Ad) to the collection in $obj4 (Category)
                $obj4->addAd($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Ad objects pre-filled with all related objects except UserType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Ad objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptUserType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AdPeer::DATABASE_NAME);
        }

        AdPeer::addSelectColumns($criteria);
        $startcol2 = AdPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        AdTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AdTypePeer::NUM_HYDRATE_COLUMNS;

        CategoryPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + CategoryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AdPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AdPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AdPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Ad) to the collection in $obj2 (User)
                $obj2->addAd($obj1);

            } // if joined row is not null

                // Add objects for joined AdType rows

                $key3 = AdTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = AdTypePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = AdTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    AdTypePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Ad) to the collection in $obj3 (AdType)
                $obj3->addAd($obj1);

            } // if joined row is not null

                // Add objects for joined Category rows

                $key4 = CategoryPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = CategoryPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = CategoryPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    CategoryPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Ad) to the collection in $obj4 (Category)
                $obj4->addAd($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Ad objects pre-filled with all related objects except AdType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Ad objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptAdType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AdPeer::DATABASE_NAME);
        }

        AdPeer::addSelectColumns($criteria);
        $startcol2 = AdPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + UserTypePeer::NUM_HYDRATE_COLUMNS;

        CategoryPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + CategoryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::CATEGORY_ID, CategoryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AdPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AdPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AdPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Ad) to the collection in $obj2 (User)
                $obj2->addAd($obj1);

            } // if joined row is not null

                // Add objects for joined UserType rows

                $key3 = UserTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = UserTypePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = UserTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    UserTypePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Ad) to the collection in $obj3 (UserType)
                $obj3->addAd($obj1);

            } // if joined row is not null

                // Add objects for joined Category rows

                $key4 = CategoryPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = CategoryPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = CategoryPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    CategoryPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Ad) to the collection in $obj4 (Category)
                $obj4->addAd($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Ad objects pre-filled with all related objects except Category.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Ad objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCategory(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AdPeer::DATABASE_NAME);
        }

        AdPeer::addSelectColumns($criteria);
        $startcol2 = AdPeer::NUM_HYDRATE_COLUMNS;

        UserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + UserPeer::NUM_HYDRATE_COLUMNS;

        UserTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + UserTypePeer::NUM_HYDRATE_COLUMNS;

        AdTypePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AdTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AdPeer::USER_ID, UserPeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::USER_TYPE_ID, UserTypePeer::ID, $join_behavior);

        $criteria->addJoin(AdPeer::AD_TYPE_ID, AdTypePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AdPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AdPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AdPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AdPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined User rows

                $key2 = UserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = UserPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = UserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    UserPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Ad) to the collection in $obj2 (User)
                $obj2->addAd($obj1);

            } // if joined row is not null

                // Add objects for joined UserType rows

                $key3 = UserTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = UserTypePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = UserTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    UserTypePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Ad) to the collection in $obj3 (UserType)
                $obj3->addAd($obj1);

            } // if joined row is not null

                // Add objects for joined AdType rows

                $key4 = AdTypePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = AdTypePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = AdTypePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AdTypePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Ad) to the collection in $obj4 (AdType)
                $obj4->addAd($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(AdPeer::DATABASE_NAME)->getTable(AdPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseAdPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseAdPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new AdTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass()
    {
        return AdPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Ad or Criteria object.
     *
     * @param      mixed $values Criteria or Ad object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Ad object
        }

        if ($criteria->containsKey(AdPeer::ID) && $criteria->keyContainsValue(AdPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AdPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a Ad or Criteria object.
     *
     * @param      mixed $values Criteria or Ad object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(AdPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(AdPeer::ID);
            $value = $criteria->remove(AdPeer::ID);
            if ($value) {
                $selectCriteria->add(AdPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(AdPeer::TABLE_NAME);
            }

        } else { // $values is Ad object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the ad table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(AdPeer::TABLE_NAME, $con, AdPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AdPeer::clearInstancePool();
            AdPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Ad or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Ad object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            AdPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Ad) { // it's a model object
            // invalidate the cache for this single object
            AdPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AdPeer::DATABASE_NAME);
            $criteria->add(AdPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                AdPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(AdPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            AdPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Ad object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      Ad $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(AdPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(AdPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(AdPeer::DATABASE_NAME, AdPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Ad
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = AdPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(AdPeer::DATABASE_NAME);
        $criteria->add(AdPeer::ID, $pk);

        $v = AdPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Ad[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(AdPeer::DATABASE_NAME);
            $criteria->add(AdPeer::ID, $pks, Criteria::IN);
            $objs = AdPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseAdPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseAdPeer::buildTableMap();

