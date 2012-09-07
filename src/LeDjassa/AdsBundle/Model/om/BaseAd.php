<?php

namespace LeDjassa\AdsBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use LeDjassa\AdsBundle\Model\Ad;
use LeDjassa\AdsBundle\Model\AdPeer;
use LeDjassa\AdsBundle\Model\AdQuery;
use LeDjassa\AdsBundle\Model\AdType;
use LeDjassa\AdsBundle\Model\AdTypeQuery;
use LeDjassa\AdsBundle\Model\Category;
use LeDjassa\AdsBundle\Model\CategoryQuery;
use LeDjassa\AdsBundle\Model\PictureAd;
use LeDjassa\AdsBundle\Model\PictureAdQuery;
use LeDjassa\AdsBundle\Model\Quarter;
use LeDjassa\AdsBundle\Model\QuarterQuery;
use LeDjassa\AdsBundle\Model\User;
use LeDjassa\AdsBundle\Model\UserQuery;
use LeDjassa\AdsBundle\Model\UserType;
use LeDjassa\AdsBundle\Model\UserTypeQuery;

abstract class BaseAd extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'LeDjassa\\AdsBundle\\Model\\AdPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        AdPeer
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
     * The value for the title field.
     * @var        string
     */
    protected $title;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the price field.
     * @var        string
     */
    protected $price;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * The value for the ad_type_id field.
     * @var        int
     */
    protected $ad_type_id;

    /**
     * The value for the category_id field.
     * @var        int
     */
    protected $category_id;

    /**
     * The value for the user_type_id field.
     * @var        int
     */
    protected $user_type_id;

    /**
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        UserType
     */
    protected $aUserType;

    /**
     * @var        AdType
     */
    protected $aAdType;

    /**
     * @var        Category
     */
    protected $aCategory;

    /**
     * @var        PropelObjectCollection|Quarter[] Collection to store aggregation of Quarter objects.
     */
    protected $collQuarters;
    protected $collQuartersPartial;

    /**
     * @var        PropelObjectCollection|PictureAd[] Collection to store aggregation of PictureAd objects.
     */
    protected $collPictureAds;
    protected $collPictureAdsPartial;

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
    protected $pictureAdsScheduledForDeletion = null;

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
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [description] column value.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [price] column value.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->created_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        } else {
            try {
                $dt = new DateTime($this->updated_at);
            } catch (Exception $x) {
                throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
            }
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        } elseif (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        } else {
            return $dt->format($format);
        }
    }

    /**
     * Get the [ad_type_id] column value.
     *
     * @return int
     */
    public function getAdTypeId()
    {
        return $this->ad_type_id;
    }

    /**
     * Get the [category_id] column value.
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Get the [user_type_id] column value.
     *
     * @return int
     */
    public function getUserTypeId()
    {
        return $this->user_type_id;
    }

    /**
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Ad The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = AdPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return Ad The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[] = AdPeer::TITLE;
        }


        return $this;
    } // setTitle()

    /**
     * Set the value of [description] column.
     *
     * @param string $v new value
     * @return Ad The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[] = AdPeer::DESCRIPTION;
        }


        return $this;
    } // setDescription()

    /**
     * Set the value of [price] column.
     *
     * @param string $v new value
     * @return Ad The current object (for fluent API support)
     */
    public function setPrice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->price !== $v) {
            $this->price = $v;
            $this->modifiedColumns[] = AdPeer::PRICE;
        }


        return $this;
    } // setPrice()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Ad The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = AdPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Ad The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = AdPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [ad_type_id] column.
     *
     * @param int $v new value
     * @return Ad The current object (for fluent API support)
     */
    public function setAdTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->ad_type_id !== $v) {
            $this->ad_type_id = $v;
            $this->modifiedColumns[] = AdPeer::AD_TYPE_ID;
        }

        if ($this->aAdType !== null && $this->aAdType->getId() !== $v) {
            $this->aAdType = null;
        }


        return $this;
    } // setAdTypeId()

    /**
     * Set the value of [category_id] column.
     *
     * @param int $v new value
     * @return Ad The current object (for fluent API support)
     */
    public function setCategoryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->category_id !== $v) {
            $this->category_id = $v;
            $this->modifiedColumns[] = AdPeer::CATEGORY_ID;
        }

        if ($this->aCategory !== null && $this->aCategory->getId() !== $v) {
            $this->aCategory = null;
        }


        return $this;
    } // setCategoryId()

    /**
     * Set the value of [user_type_id] column.
     *
     * @param int $v new value
     * @return Ad The current object (for fluent API support)
     */
    public function setUserTypeId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_type_id !== $v) {
            $this->user_type_id = $v;
            $this->modifiedColumns[] = AdPeer::USER_TYPE_ID;
        }

        if ($this->aUserType !== null && $this->aUserType->getId() !== $v) {
            $this->aUserType = null;
        }


        return $this;
    } // setUserTypeId()

    /**
     * Set the value of [user_id] column.
     *
     * @param int $v new value
     * @return Ad The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = AdPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

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
            $this->title = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->description = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->price = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->created_at = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->updated_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->ad_type_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->category_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->user_type_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->user_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 10; // 10 = AdPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Ad object", $e);
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

        if ($this->aAdType !== null && $this->ad_type_id !== $this->aAdType->getId()) {
            $this->aAdType = null;
        }
        if ($this->aCategory !== null && $this->category_id !== $this->aCategory->getId()) {
            $this->aCategory = null;
        }
        if ($this->aUserType !== null && $this->user_type_id !== $this->aUserType->getId()) {
            $this->aUserType = null;
        }
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
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
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = AdPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->aUserType = null;
            $this->aAdType = null;
            $this->aCategory = null;
            $this->collQuarters = null;

            $this->collPictureAds = null;

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
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = AdQuery::create()
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
            $con = Propel::getConnection(AdPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
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
                AdPeer::addInstanceToPool($this);
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

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->aUserType !== null) {
                if ($this->aUserType->isModified() || $this->aUserType->isNew()) {
                    $affectedRows += $this->aUserType->save($con);
                }
                $this->setUserType($this->aUserType);
            }

            if ($this->aAdType !== null) {
                if ($this->aAdType->isModified() || $this->aAdType->isNew()) {
                    $affectedRows += $this->aAdType->save($con);
                }
                $this->setAdType($this->aAdType);
            }

            if ($this->aCategory !== null) {
                if ($this->aCategory->isModified() || $this->aCategory->isNew()) {
                    $affectedRows += $this->aCategory->save($con);
                }
                $this->setCategory($this->aCategory);
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
                    if (!$referrerFK->isDeleted()) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->pictureAdsScheduledForDeletion !== null) {
                if (!$this->pictureAdsScheduledForDeletion->isEmpty()) {
                    foreach ($this->pictureAdsScheduledForDeletion as $pictureAd) {
                        // need to save related object because we set the relation to null
                        $pictureAd->save($con);
                    }
                    $this->pictureAdsScheduledForDeletion = null;
                }
            }

            if ($this->collPictureAds !== null) {
                foreach ($this->collPictureAds as $referrerFK) {
                    if (!$referrerFK->isDeleted()) {
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

        $this->modifiedColumns[] = AdPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AdPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AdPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`ID`';
        }
        if ($this->isColumnModified(AdPeer::TITLE)) {
            $modifiedColumns[':p' . $index++]  = '`TITLE`';
        }
        if ($this->isColumnModified(AdPeer::DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = '`DESCRIPTION`';
        }
        if ($this->isColumnModified(AdPeer::PRICE)) {
            $modifiedColumns[':p' . $index++]  = '`PRICE`';
        }
        if ($this->isColumnModified(AdPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`CREATED_AT`';
        }
        if ($this->isColumnModified(AdPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`UPDATED_AT`';
        }
        if ($this->isColumnModified(AdPeer::AD_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`AD_TYPE_ID`';
        }
        if ($this->isColumnModified(AdPeer::CATEGORY_ID)) {
            $modifiedColumns[':p' . $index++]  = '`CATEGORY_ID`';
        }
        if ($this->isColumnModified(AdPeer::USER_TYPE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`USER_TYPE_ID`';
        }
        if ($this->isColumnModified(AdPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`USER_ID`';
        }

        $sql = sprintf(
            'INSERT INTO `ad` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`ID`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`TITLE`':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case '`DESCRIPTION`':
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case '`PRICE`':
                        $stmt->bindValue($identifier, $this->price, PDO::PARAM_STR);
                        break;
                    case '`CREATED_AT`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`UPDATED_AT`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                    case '`AD_TYPE_ID`':
                        $stmt->bindValue($identifier, $this->ad_type_id, PDO::PARAM_INT);
                        break;
                    case '`CATEGORY_ID`':
                        $stmt->bindValue($identifier, $this->category_id, PDO::PARAM_INT);
                        break;
                    case '`USER_TYPE_ID`':
                        $stmt->bindValue($identifier, $this->user_type_id, PDO::PARAM_INT);
                        break;
                    case '`USER_ID`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
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
        } else {
            $this->validationFailures = $res;

            return false;
        }
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

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }

            if ($this->aUserType !== null) {
                if (!$this->aUserType->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUserType->getValidationFailures());
                }
            }

            if ($this->aAdType !== null) {
                if (!$this->aAdType->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aAdType->getValidationFailures());
                }
            }

            if ($this->aCategory !== null) {
                if (!$this->aCategory->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCategory->getValidationFailures());
                }
            }


            if (($retval = AdPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collQuarters !== null) {
                    foreach ($this->collQuarters as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collPictureAds !== null) {
                    foreach ($this->collPictureAds as $referrerFK) {
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
        $pos = AdPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getTitle();
                break;
            case 2:
                return $this->getDescription();
                break;
            case 3:
                return $this->getPrice();
                break;
            case 4:
                return $this->getCreatedAt();
                break;
            case 5:
                return $this->getUpdatedAt();
                break;
            case 6:
                return $this->getAdTypeId();
                break;
            case 7:
                return $this->getCategoryId();
                break;
            case 8:
                return $this->getUserTypeId();
                break;
            case 9:
                return $this->getUserId();
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
        if (isset($alreadyDumpedObjects['Ad'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Ad'][$this->getPrimaryKey()] = true;
        $keys = AdPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTitle(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getPrice(),
            $keys[4] => $this->getCreatedAt(),
            $keys[5] => $this->getUpdatedAt(),
            $keys[6] => $this->getAdTypeId(),
            $keys[7] => $this->getCategoryId(),
            $keys[8] => $this->getUserTypeId(),
            $keys[9] => $this->getUserId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUserType) {
                $result['UserType'] = $this->aUserType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAdType) {
                $result['AdType'] = $this->aAdType->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCategory) {
                $result['Category'] = $this->aCategory->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collQuarters) {
                $result['Quarters'] = $this->collQuarters->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPictureAds) {
                $result['PictureAds'] = $this->collPictureAds->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = AdPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setTitle($value);
                break;
            case 2:
                $this->setDescription($value);
                break;
            case 3:
                $this->setPrice($value);
                break;
            case 4:
                $this->setCreatedAt($value);
                break;
            case 5:
                $this->setUpdatedAt($value);
                break;
            case 6:
                $this->setAdTypeId($value);
                break;
            case 7:
                $this->setCategoryId($value);
                break;
            case 8:
                $this->setUserTypeId($value);
                break;
            case 9:
                $this->setUserId($value);
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
        $keys = AdPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTitle($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDescription($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setPrice($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCreatedAt($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setUpdatedAt($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setAdTypeId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCategoryId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setUserTypeId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setUserId($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AdPeer::DATABASE_NAME);

        if ($this->isColumnModified(AdPeer::ID)) $criteria->add(AdPeer::ID, $this->id);
        if ($this->isColumnModified(AdPeer::TITLE)) $criteria->add(AdPeer::TITLE, $this->title);
        if ($this->isColumnModified(AdPeer::DESCRIPTION)) $criteria->add(AdPeer::DESCRIPTION, $this->description);
        if ($this->isColumnModified(AdPeer::PRICE)) $criteria->add(AdPeer::PRICE, $this->price);
        if ($this->isColumnModified(AdPeer::CREATED_AT)) $criteria->add(AdPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(AdPeer::UPDATED_AT)) $criteria->add(AdPeer::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(AdPeer::AD_TYPE_ID)) $criteria->add(AdPeer::AD_TYPE_ID, $this->ad_type_id);
        if ($this->isColumnModified(AdPeer::CATEGORY_ID)) $criteria->add(AdPeer::CATEGORY_ID, $this->category_id);
        if ($this->isColumnModified(AdPeer::USER_TYPE_ID)) $criteria->add(AdPeer::USER_TYPE_ID, $this->user_type_id);
        if ($this->isColumnModified(AdPeer::USER_ID)) $criteria->add(AdPeer::USER_ID, $this->user_id);

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
        $criteria = new Criteria(AdPeer::DATABASE_NAME);
        $criteria->add(AdPeer::ID, $this->id);

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
     * @param object $copyObj An object of Ad (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setPrice($this->getPrice());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setAdTypeId($this->getAdTypeId());
        $copyObj->setCategoryId($this->getCategoryId());
        $copyObj->setUserTypeId($this->getUserTypeId());
        $copyObj->setUserId($this->getUserId());

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

            foreach ($this->getPictureAds() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPictureAd($relObj->copy($deepCopy));
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
     * @return Ad Clone of current object.
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
     * @return AdPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new AdPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param             User $v
     * @return Ad The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addAd($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(PropelPDO $con = null)
    {
        if ($this->aUser === null && ($this->user_id !== null)) {
            $this->aUser = UserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addAds($this);
             */
        }

        return $this->aUser;
    }

    /**
     * Declares an association between this object and a UserType object.
     *
     * @param             UserType $v
     * @return Ad The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUserType(UserType $v = null)
    {
        if ($v === null) {
            $this->setUserTypeId(NULL);
        } else {
            $this->setUserTypeId($v->getId());
        }

        $this->aUserType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the UserType object, it will not be re-added.
        if ($v !== null) {
            $v->addAd($this);
        }


        return $this;
    }


    /**
     * Get the associated UserType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return UserType The associated UserType object.
     * @throws PropelException
     */
    public function getUserType(PropelPDO $con = null)
    {
        if ($this->aUserType === null && ($this->user_type_id !== null)) {
            $this->aUserType = UserTypeQuery::create()->findPk($this->user_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUserType->addAds($this);
             */
        }

        return $this->aUserType;
    }

    /**
     * Declares an association between this object and a AdType object.
     *
     * @param             AdType $v
     * @return Ad The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAdType(AdType $v = null)
    {
        if ($v === null) {
            $this->setAdTypeId(NULL);
        } else {
            $this->setAdTypeId($v->getId());
        }

        $this->aAdType = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the AdType object, it will not be re-added.
        if ($v !== null) {
            $v->addAd($this);
        }


        return $this;
    }


    /**
     * Get the associated AdType object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return AdType The associated AdType object.
     * @throws PropelException
     */
    public function getAdType(PropelPDO $con = null)
    {
        if ($this->aAdType === null && ($this->ad_type_id !== null)) {
            $this->aAdType = AdTypeQuery::create()->findPk($this->ad_type_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAdType->addAds($this);
             */
        }

        return $this->aAdType;
    }

    /**
     * Declares an association between this object and a Category object.
     *
     * @param             Category $v
     * @return Ad The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCategory(Category $v = null)
    {
        if ($v === null) {
            $this->setCategoryId(NULL);
        } else {
            $this->setCategoryId($v->getId());
        }

        $this->aCategory = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Category object, it will not be re-added.
        if ($v !== null) {
            $v->addAd($this);
        }


        return $this;
    }


    /**
     * Get the associated Category object
     *
     * @param PropelPDO $con Optional Connection object.
     * @return Category The associated Category object.
     * @throws PropelException
     */
    public function getCategory(PropelPDO $con = null)
    {
        if ($this->aCategory === null && ($this->category_id !== null)) {
            $this->aCategory = CategoryQuery::create()->findPk($this->category_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCategory->addAds($this);
             */
        }

        return $this->aCategory;
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
        if ('PictureAd' == $relationName) {
            $this->initPictureAds();
        }
    }

    /**
     * Clears out the collQuarters collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addQuarters()
     */
    public function clearQuarters()
    {
        $this->collQuarters = null; // important to set this to null since that means it is uninitialized
        $this->collQuartersPartial = null;
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
     * If this Ad is new, it will return
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
                    ->filterByAd($this)
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
     */
    public function setQuarters(PropelCollection $quarters, PropelPDO $con = null)
    {
        $this->quartersScheduledForDeletion = $this->getQuarters(new Criteria(), $con)->diff($quarters);

        foreach ($this->quartersScheduledForDeletion as $quarterRemoved) {
            $quarterRemoved->setAd(null);
        }

        $this->collQuarters = null;
        foreach ($quarters as $quarter) {
            $this->addQuarter($quarter);
        }

        $this->collQuarters = $quarters;
        $this->collQuartersPartial = false;
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
            } else {
                if($partial && !$criteria) {
                    return count($this->getQuarters());
                }
                $query = QuarterQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByAd($this)
                    ->count($con);
            }
        } else {
            return count($this->collQuarters);
        }
    }

    /**
     * Method called to associate a Quarter object to this object
     * through the Quarter foreign key attribute.
     *
     * @param    Quarter $l Quarter
     * @return Ad The current object (for fluent API support)
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
        $quarter->setAd($this);
    }

    /**
     * @param	Quarter $quarter The quarter object to remove.
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
            $quarter->setAd(null);
        }
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Ad is new, it will return
     * an empty collection; or if this Ad has previously
     * been saved, it will retrieve related Quarters from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Ad.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Quarter[] List of Quarter objects
     */
    public function getQuartersJoinCity($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = QuarterQuery::create(null, $criteria);
        $query->joinWith('City', $join_behavior);

        return $this->getQuarters($query, $con);
    }

    /**
     * Clears out the collPictureAds collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPictureAds()
     */
    public function clearPictureAds()
    {
        $this->collPictureAds = null; // important to set this to null since that means it is uninitialized
        $this->collPictureAdsPartial = null;
    }

    /**
     * reset is the collPictureAds collection loaded partially
     *
     * @return void
     */
    public function resetPartialPictureAds($v = true)
    {
        $this->collPictureAdsPartial = $v;
    }

    /**
     * Initializes the collPictureAds collection.
     *
     * By default this just sets the collPictureAds collection to an empty array (like clearcollPictureAds());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPictureAds($overrideExisting = true)
    {
        if (null !== $this->collPictureAds && !$overrideExisting) {
            return;
        }
        $this->collPictureAds = new PropelObjectCollection();
        $this->collPictureAds->setModel('PictureAd');
    }

    /**
     * Gets an array of PictureAd objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Ad is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|PictureAd[] List of PictureAd objects
     * @throws PropelException
     */
    public function getPictureAds($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collPictureAdsPartial && !$this->isNew();
        if (null === $this->collPictureAds || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPictureAds) {
                // return empty collection
                $this->initPictureAds();
            } else {
                $collPictureAds = PictureAdQuery::create(null, $criteria)
                    ->filterByAd($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collPictureAdsPartial && count($collPictureAds)) {
                      $this->initPictureAds(false);

                      foreach($collPictureAds as $obj) {
                        if (false == $this->collPictureAds->contains($obj)) {
                          $this->collPictureAds->append($obj);
                        }
                      }

                      $this->collPictureAdsPartial = true;
                    }

                    return $collPictureAds;
                }

                if($partial && $this->collPictureAds) {
                    foreach($this->collPictureAds as $obj) {
                        if($obj->isNew()) {
                            $collPictureAds[] = $obj;
                        }
                    }
                }

                $this->collPictureAds = $collPictureAds;
                $this->collPictureAdsPartial = false;
            }
        }

        return $this->collPictureAds;
    }

    /**
     * Sets a collection of PictureAd objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $pictureAds A Propel collection.
     * @param PropelPDO $con Optional connection object
     */
    public function setPictureAds(PropelCollection $pictureAds, PropelPDO $con = null)
    {
        $this->pictureAdsScheduledForDeletion = $this->getPictureAds(new Criteria(), $con)->diff($pictureAds);

        foreach ($this->pictureAdsScheduledForDeletion as $pictureAdRemoved) {
            $pictureAdRemoved->setAd(null);
        }

        $this->collPictureAds = null;
        foreach ($pictureAds as $pictureAd) {
            $this->addPictureAd($pictureAd);
        }

        $this->collPictureAds = $pictureAds;
        $this->collPictureAdsPartial = false;
    }

    /**
     * Returns the number of related PictureAd objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related PictureAd objects.
     * @throws PropelException
     */
    public function countPictureAds(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collPictureAdsPartial && !$this->isNew();
        if (null === $this->collPictureAds || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPictureAds) {
                return 0;
            } else {
                if($partial && !$criteria) {
                    return count($this->getPictureAds());
                }
                $query = PictureAdQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByAd($this)
                    ->count($con);
            }
        } else {
            return count($this->collPictureAds);
        }
    }

    /**
     * Method called to associate a PictureAd object to this object
     * through the PictureAd foreign key attribute.
     *
     * @param    PictureAd $l PictureAd
     * @return Ad The current object (for fluent API support)
     */
    public function addPictureAd(PictureAd $l)
    {
        if ($this->collPictureAds === null) {
            $this->initPictureAds();
            $this->collPictureAdsPartial = true;
        }
        if (!in_array($l, $this->collPictureAds->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPictureAd($l);
        }

        return $this;
    }

    /**
     * @param	PictureAd $pictureAd The pictureAd object to add.
     */
    protected function doAddPictureAd($pictureAd)
    {
        $this->collPictureAds[]= $pictureAd;
        $pictureAd->setAd($this);
    }

    /**
     * @param	PictureAd $pictureAd The pictureAd object to remove.
     */
    public function removePictureAd($pictureAd)
    {
        if ($this->getPictureAds()->contains($pictureAd)) {
            $this->collPictureAds->remove($this->collPictureAds->search($pictureAd));
            if (null === $this->pictureAdsScheduledForDeletion) {
                $this->pictureAdsScheduledForDeletion = clone $this->collPictureAds;
                $this->pictureAdsScheduledForDeletion->clear();
            }
            $this->pictureAdsScheduledForDeletion[]= $pictureAd;
            $pictureAd->setAd(null);
        }
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->title = null;
        $this->description = null;
        $this->price = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->ad_type_id = null;
        $this->category_id = null;
        $this->user_type_id = null;
        $this->user_id = null;
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
            if ($this->collPictureAds) {
                foreach ($this->collPictureAds as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        if ($this->collQuarters instanceof PropelCollection) {
            $this->collQuarters->clearIterator();
        }
        $this->collQuarters = null;
        if ($this->collPictureAds instanceof PropelCollection) {
            $this->collPictureAds->clearIterator();
        }
        $this->collPictureAds = null;
        $this->aUser = null;
        $this->aUserType = null;
        $this->aAdType = null;
        $this->aCategory = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'title' column
     */
    public function __toString()
    {
        return (string) $this->getTitle();
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

}
