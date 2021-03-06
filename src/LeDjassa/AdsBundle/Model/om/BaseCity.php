<?php

namespace LeDjassa\AdsBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Model\Area;
use LeDjassa\AdsBundle\Model\AreaQuery;
use LeDjassa\AdsBundle\Model\City;
use LeDjassa\AdsBundle\Model\CityPeer;
use LeDjassa\AdsBundle\Model\CityQuery;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Model\QuarterQuery;

abstract class BaseCity extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'LeDjassa\\AdsBundle\\Model\\CityPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CityPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the area_id field.
     * @var        int
     */
    protected $area_id;

    /**
     * The value for the slug field.
     * @var        string
     */
    protected $slug;

    /**
     * @var        Area
     */
    protected $aArea;

    /**
     * @var        PropelObjectCollection|Quarter[] Collection to store aggregation of Quarter objects.
     */
    protected $collQuarters;
    protected $collQuartersPartial;

    /**
     * @var        PropelObjectCollection|Ad[] Collection to store aggregation of Ad objects.
     */
    protected $collAds;
    protected $collAdsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $quartersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $adsScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get the [area_id] column value.
     *
     * @return int
     */
    public function getAreaId()
    {
        return $this->area_id;
    }

    /**
     * Get the [slug] column value.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return City The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CityPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return City The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = CityPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [code] column.
     *
     * @param string $v new value
     * @return City The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = CityPeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [area_id] column.
     *
     * @param int $v new value
     * @return City The current object (for fluent API support)
     */
    public function setAreaId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->area_id !== $v) {
            $this->area_id = $v;
            $this->modifiedColumns[] = CityPeer::AREA_ID;
        }

        if ($this->aArea !== null && $this->aArea->getId() !== $v) {
            $this->aArea = null;
        }


        return $this;
    } // setAreaId()

    /**
     * Set the value of [slug] column.
     *
     * @param string $v new value
     * @return City The current object (for fluent API support)
     */
    public function setSlug($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->slug !== $v) {
            $this->slug = $v;
            $this->modifiedColumns[] = CityPeer::SLUG;
        }


        return $this;
    } // setSlug()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->code = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->area_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->slug = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 5; // 5 = CityPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating City object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aArea !== null && $this->area_id !== $this->aArea->getId()) {
            $this->aArea = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CityPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aArea = null;
            $this->collQuarters = null;

            $this->collAds = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CityQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sluggable behavior

            if ($this->isColumnModified(CityPeer::SLUG) && $this->getSlug()) {
                $this->setSlug($this->makeSlugUnique($this->getSlug()));
            } else {
                $this->setSlug($this->createSlug());
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CityPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aArea !== null) {
                if ($this->aArea->isModified() || $this->aArea->isNew()) {
                    $affectedRows += $this->aArea->save($con);
                }
                $this->setArea($this->aArea);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->quartersScheduledForDeletion !== null) {
                if (!$this->quartersScheduledForDeletion->isEmpty()) {
                    foreach ($this->quartersScheduledForDeletion as $quarter) {
                        // need to save related object because we set the relation to null
                        $quarter->save($con);
                    }
                    $this->quartersScheduledForDeletion = null;
                }
            }

            if ($this->collQuarters !== null) {
                foreach ($this->collQuarters as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->adsScheduledForDeletion !== null) {
                if (!$this->adsScheduledForDeletion->isEmpty()) {
                    foreach ($this->adsScheduledForDeletion as $ad) {
                        // need to save related object because we set the relation to null
                        $ad->save($con);
                    }
                    $this->adsScheduledForDeletion = null;
                }
            }

            if ($this->collAds !== null) {
                foreach ($this->collAds as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = CityPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CityPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CityPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CityPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CityPeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(CityPeer::AREA_ID)) {
            $modifiedColumns[':p' . $index++]  = '`area_id`';
        }
        if ($this->isColumnModified(CityPeer::SLUG)) {
            $modifiedColumns[':p' . $index++]  = '`slug`';
        }

        $sql = sprintf(
            'INSERT INTO `city` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`code`':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case '`area_id`':
                        $stmt->bindValue($identifier, $this->area_id, PDO::PARAM_INT);
                        break;
                    case '`slug`':
                        $stmt->bindValue($identifier, $this->slug, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aArea !== null) {
                if (!$this->aArea->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aArea->getValidationFailures());
                }
            }


            if (($retval = CityPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collQuarters !== null) {
                    foreach ($this->collQuarters as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collAds !== null) {
                    foreach ($this->collAds as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CityPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getCode();
                break;
            case 3:
                return $this->getAreaId();
                break;
            case 4:
                return $this->getSlug();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['City'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['City'][$this->getPrimaryKey()] = true;
        $keys = CityPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getCode(),
            $keys[3] => $this->getAreaId(),
            $keys[4] => $this->getSlug(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aArea) {
                $result['Area'] = $this->aArea->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collQuarters) {
                $result['Quarters'] = $this->collQuarters->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAds) {
                $result['Ads'] = $this->collAds->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CityPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setCode($value);
                break;
            case 3:
                $this->setAreaId($value);
                break;
            case 4:
                $this->setSlug($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = CityPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCode($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setAreaId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setSlug($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CityPeer::DATABASE_NAME);

        if ($this->isColumnModified(CityPeer::ID)) $criteria->add(CityPeer::ID, $this->id);
        if ($this->isColumnModified(CityPeer::NAME)) $criteria->add(CityPeer::NAME, $this->name);
        if ($this->isColumnModified(CityPeer::CODE)) $criteria->add(CityPeer::CODE, $this->code);
        if ($this->isColumnModified(CityPeer::AREA_ID)) $criteria->add(CityPeer::AREA_ID, $this->area_id);
        if ($this->isColumnModified(CityPeer::SLUG)) $criteria->add(CityPeer::SLUG, $this->slug);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(CityPeer::DATABASE_NAME);
        $criteria->add(CityPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of City (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setCode($this->getCode());
        $copyObj->setAreaId($this->getAreaId());
        $copyObj->setSlug($this->getSlug());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getQuarters() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addQuarter($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAds() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAd($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return City Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return CityPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CityPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Area object.
     *
     * @param             Area $v
     * @return City The current object (for fluent API support)
     * @throws PropelException
     */
    public function setArea(Area $v = null)
    {
        if ($v === null) {
            $this->setAreaId(NULL);
        } else {
            $this->setAreaId($v->getId());
        }

        $this->aArea = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Area object, it will not be re-added.
        if ($v !== null) {
            $v->addCity($this);
        }


        return $this;
    }


    /**
     * Get the associated Area object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Area The associated Area object.
     * @throws PropelException
     */
    public function getArea(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aArea === null && ($this->area_id !== null) && $doQuery) {
            $this->aArea = AreaQuery::create()->findPk($this->area_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aArea->addCities($this);
             */
        }

        return $this->aArea;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Quarter' == $relationName) {
            $this->initQuarters();
        }
        if ('Ad' == $relationName) {
            $this->initAds();
        }
    }

    /**
     * Clears out the collQuarters collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return City The current object (for fluent API support)
     * @see        addQuarters()
     */
    public function clearQuarters()
    {
        $this->collQuarters = null; // important to set this to null since that means it is uninitialized
        $this->collQuartersPartial = null;

        return $this;
    }

    /**
     * reset is the collQuarters collection loaded partially
     *
     * @return void
     */
    public function resetPartialQuarters($v = true)
    {
        $this->collQuartersPartial = $v;
    }

    /**
     * Initializes the collQuarters collection.
     *
     * By default this just sets the collQuarters collection to an empty array (like clearcollQuarters());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initQuarters($overrideExisting = true)
    {
        if (null !== $this->collQuarters && !$overrideExisting) {
            return;
        }
        $this->collQuarters = new PropelObjectCollection();
        $this->collQuarters->setModel('Quarter');
    }

    /**
     * Gets an array of Quarter objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this City is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Quarter[] List of Quarter objects
     * @throws PropelException
     */
    public function getQuarters($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collQuartersPartial && !$this->isNew();
        if (null === $this->collQuarters || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collQuarters) {
                // return empty collection
                $this->initQuarters();
            } else {
                $collQuarters = QuarterQuery::create(null, $criteria)
                    ->filterByCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collQuartersPartial && count($collQuarters)) {
                      $this->initQuarters(false);

                      foreach($collQuarters as $obj) {
                        if (false == $this->collQuarters->contains($obj)) {
                          $this->collQuarters->append($obj);
                        }
                      }

                      $this->collQuartersPartial = true;
                    }

                    return $collQuarters;
                }

                if($partial && $this->collQuarters) {
                    foreach($this->collQuarters as $obj) {
                        if($obj->isNew()) {
                            $collQuarters[] = $obj;
                        }
                    }
                }

                $this->collQuarters = $collQuarters;
                $this->collQuartersPartial = false;
            }
        }

        return $this->collQuarters;
    }

    /**
     * Sets a collection of Quarter objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $quarters A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return City The current object (for fluent API support)
     */
    public function setQuarters(PropelCollection $quarters, PropelPDO $con = null)
    {
        $quartersToDelete = $this->getQuarters(new Criteria(), $con)->diff($quarters);

        $this->quartersScheduledForDeletion = unserialize(serialize($quartersToDelete));

        foreach ($quartersToDelete as $quarterRemoved) {
            $quarterRemoved->setCity(null);
        }

        $this->collQuarters = null;
        foreach ($quarters as $quarter) {
            $this->addQuarter($quarter);
        }

        $this->collQuarters = $quarters;
        $this->collQuartersPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Quarter objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Quarter objects.
     * @throws PropelException
     */
    public function countQuarters(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collQuartersPartial && !$this->isNew();
        if (null === $this->collQuarters || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collQuarters) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getQuarters());
            }
            $query = QuarterQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCity($this)
                ->count($con);
        }

        return count($this->collQuarters);
    }

    /**
     * Method called to associate a Quarter object to this object
     * through the Quarter foreign key attribute.
     *
     * @param    Quarter $l Quarter
     * @return City The current object (for fluent API support)
     */
    public function addQuarter(Quarter $l)
    {
        if ($this->collQuarters === null) {
            $this->initQuarters();
            $this->collQuartersPartial = true;
        }
        if (!in_array($l, $this->collQuarters->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddQuarter($l);
        }

        return $this;
    }

    /**
     * @param	Quarter $quarter The quarter object to add.
     */
    protected function doAddQuarter($quarter)
    {
        $this->collQuarters[]= $quarter;
        $quarter->setCity($this);
    }

    /**
     * @param	Quarter $quarter The quarter object to remove.
     * @return City The current object (for fluent API support)
     */
    public function removeQuarter($quarter)
    {
        if ($this->getQuarters()->contains($quarter)) {
            $this->collQuarters->remove($this->collQuarters->search($quarter));
            if (null === $this->quartersScheduledForDeletion) {
                $this->quartersScheduledForDeletion = clone $this->collQuarters;
                $this->quartersScheduledForDeletion->clear();
            }
            $this->quartersScheduledForDeletion[]= $quarter;
            $quarter->setCity(null);
        }

        return $this;
    }

    /**
     * Clears out the collAds collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return City The current object (for fluent API support)
     * @see        addAds()
     */
    public function clearAds()
    {
        $this->collAds = null; // important to set this to null since that means it is uninitialized
        $this->collAdsPartial = null;

        return $this;
    }

    /**
     * reset is the collAds collection loaded partially
     *
     * @return void
     */
    public function resetPartialAds($v = true)
    {
        $this->collAdsPartial = $v;
    }

    /**
     * Initializes the collAds collection.
     *
     * By default this just sets the collAds collection to an empty array (like clearcollAds());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAds($overrideExisting = true)
    {
        if (null !== $this->collAds && !$overrideExisting) {
            return;
        }
        $this->collAds = new PropelObjectCollection();
        $this->collAds->setModel('Ad');
    }

    /**
     * Gets an array of Ad objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this City is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Ad[] List of Ad objects
     * @throws PropelException
     */
    public function getAds($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAdsPartial && !$this->isNew();
        if (null === $this->collAds || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAds) {
                // return empty collection
                $this->initAds();
            } else {
                $collAds = AdQuery::create(null, $criteria)
                    ->filterByCity($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAdsPartial && count($collAds)) {
                      $this->initAds(false);

                      foreach($collAds as $obj) {
                        if (false == $this->collAds->contains($obj)) {
                          $this->collAds->append($obj);
                        }
                      }

                      $this->collAdsPartial = true;
                    }

                    return $collAds;
                }

                if($partial && $this->collAds) {
                    foreach($this->collAds as $obj) {
                        if($obj->isNew()) {
                            $collAds[] = $obj;
                        }
                    }
                }

                $this->collAds = $collAds;
                $this->collAdsPartial = false;
            }
        }

        return $this->collAds;
    }

    /**
     * Sets a collection of Ad objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $ads A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return City The current object (for fluent API support)
     */
    public function setAds(PropelCollection $ads, PropelPDO $con = null)
    {
        $adsToDelete = $this->getAds(new Criteria(), $con)->diff($ads);

        $this->adsScheduledForDeletion = unserialize(serialize($adsToDelete));

        foreach ($adsToDelete as $adRemoved) {
            $adRemoved->setCity(null);
        }

        $this->collAds = null;
        foreach ($ads as $ad) {
            $this->addAd($ad);
        }

        $this->collAds = $ads;
        $this->collAdsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Ad objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Ad objects.
     * @throws PropelException
     */
    public function countAds(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAdsPartial && !$this->isNew();
        if (null === $this->collAds || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAds) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getAds());
            }
            $query = AdQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCity($this)
                ->count($con);
        }

        return count($this->collAds);
    }

    /**
     * Method called to associate a Ad object to this object
     * through the Ad foreign key attribute.
     *
     * @param    Ad $l Ad
     * @return City The current object (for fluent API support)
     */
    public function addAd(Ad $l)
    {
        if ($this->collAds === null) {
            $this->initAds();
            $this->collAdsPartial = true;
        }
        if (!in_array($l, $this->collAds->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAd($l);
        }

        return $this;
    }

    /**
     * @param	Ad $ad The ad object to add.
     */
    protected function doAddAd($ad)
    {
        $this->collAds[]= $ad;
        $ad->setCity($this);
    }

    /**
     * @param	Ad $ad The ad object to remove.
     * @return City The current object (for fluent API support)
     */
    public function removeAd($ad)
    {
        if ($this->getAds()->contains($ad)) {
            $this->collAds->remove($this->collAds->search($ad));
            if (null === $this->adsScheduledForDeletion) {
                $this->adsScheduledForDeletion = clone $this->collAds;
                $this->adsScheduledForDeletion->clear();
            }
            $this->adsScheduledForDeletion[]= $ad;
            $ad->setCity(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related Ads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Ad[] List of Ad objects
     */
    public function getAdsJoinUserType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AdQuery::create(null, $criteria);
        $query->joinWith('UserType', $join_behavior);

        return $this->getAds($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related Ads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Ad[] List of Ad objects
     */
    public function getAdsJoinAdType($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AdQuery::create(null, $criteria);
        $query->joinWith('AdType', $join_behavior);

        return $this->getAds($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related Ads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Ad[] List of Ad objects
     */
    public function getAdsJoinCategory($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AdQuery::create(null, $criteria);
        $query->joinWith('Category', $join_behavior);

        return $this->getAds($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this City is new, it will return
     * an empty collection; or if this City has previously
     * been saved, it will retrieve related Ads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in City.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Ad[] List of Ad objects
     */
    public function getAdsJoinQuarter($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AdQuery::create(null, $criteria);
        $query->joinWith('Quarter', $join_behavior);

        return $this->getAds($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->code = null;
        $this->area_id = null;
        $this->slug = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collQuarters) {
                foreach ($this->collQuarters as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAds) {
                foreach ($this->collAds as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collQuarters instanceof PropelCollection) {
            $this->collQuarters->clearIterator();
        }
        $this->collQuarters = null;
        if ($this->collAds instanceof PropelCollection) {
            $this->collAds->clearIterator();
        }
        $this->collAds = null;
        $this->aArea = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'name' column
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // sluggable behavior

    /**
     * Create a unique slug based on the object
     *
     * @return string The object slug
     */
    protected function createSlug()
    {
        $slug = $this->createRawSlug();
        $slug = $this->limitSlugSize($slug);
        $slug = $this->makeSlugUnique($slug);

        return $slug;
    }

    /**
     * Create the slug from the appropriate columns
     *
     * @return string
     */
    protected function createRawSlug()
    {
        return $this->cleanupSlugPart($this->__toString());
    }

    /**
     * Cleanup a string to make a slug of it
     * Removes special characters, replaces blanks with a separator, and trim it
     *
     * @param     string $slug        the text to slugify
     * @param     string $replacement the separator used by slug
     * @return    string               the slugified text
     */
    protected static function cleanupSlugPart($slug, $replacement = '-')
    {
        // transliterate
        if (function_exists('iconv')) {
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        }

        // lowercase
        if (function_exists('mb_strtolower')) {
            $slug = mb_strtolower($slug);
        } else {
            $slug = strtolower($slug);
        }

        // remove accents resulting from OSX's iconv
        $slug = str_replace(array('\'', '`', '^'), '', $slug);

        // replace non letter or digits with separator
        $slug = preg_replace('/\W+/', $replacement, $slug);

        // trim
        $slug = trim($slug, $replacement);

        if (empty($slug)) {
            return 'n-a';
        }

        return $slug;
    }


    /**
     * Make sure the slug is short enough to accomodate the column size
     *
     * @param    string $slug                   the slug to check
     * @param    int    $incrementReservedSpace the number of characters to keep empty
     *
     * @return string                            the truncated slug
     */
    protected static function limitSlugSize($slug, $incrementReservedSpace = 3)
    {
        // check length, as suffix could put it over maximum
        if (strlen($slug) > (255 - $incrementReservedSpace)) {
            $slug = substr($slug, 0, 255 - $incrementReservedSpace);
        }

        return $slug;
    }


    /**
     * Get the slug, ensuring its uniqueness
     *
     * @param    string $slug            the slug to check
     * @param    string $separator       the separator used by slug
     * @param    int    $alreadyExists   false for the first try, true for the second, and take the high count + 1
     * @return   string                   the unique slug
     */
    protected function makeSlugUnique($slug, $separator = '-', $alreadyExists = false)
    {
        if (!$alreadyExists) {
            $slug2 = $slug;
        } else {
            $slug2 = $slug . $separator;

            $count = CityQuery::create()
                ->filterBySlug($this->getSlug())
                ->filterByPrimaryKey($this->getPrimaryKey())
            ->count();

            if (1 == $count) {
                return $this->getSlug();
            }
        }

        $query = CityQuery::create('q')
            ->where('q.Slug ' . ($alreadyExists ? 'REGEXP' : '=') . ' ?', $alreadyExists ? '^' . $slug2 . '[0-9]+$' : $slug2)
            ->prune($this)
        ;

        if (!$alreadyExists) {
            $count = $query->count();
            if ($count > 0) {
                return $this->makeSlugUnique($slug, $separator, true);
            }

            return $slug2;
        }

        // Already exists
        $object = $query
            ->addDescendingOrderByColumn('LENGTH(slug)')
            ->addDescendingOrderByColumn('slug')
        ->findOne();

        // First duplicate slug
        if (null == $object) {
            return $slug2 . '1';
        }

        return $slug2 . (substr($object->getSlug(), strlen($slug) + 1) + 1);
    }

}
