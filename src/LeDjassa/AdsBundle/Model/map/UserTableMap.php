<?php

namespace LeDjassa\AdsBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'user' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.LeDjassa.AdsBundle.Model.map
 */
class UserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LeDjassa.AdsBundle.Model.map.UserTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('user');
        $this->setPhpName('User');
        $this->setClassname('LeDjassa\\AdsBundle\\Model\\User');
        $this->setPackage('src.LeDjassa.AdsBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 5, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', false, 100, null);
        $this->getColumn('NAME', false)->setPrimaryString(true);
        $this->addColumn('EMAIL', 'Email', 'VARCHAR', false, 100, null);
        $this->addColumn('PHONE', 'Phone', 'VARCHAR', false, 100, null);
        $this->addColumn('IP_ADRESS', 'IpAdress', 'VARCHAR', false, 20, null);
        $this->addForeignKey('USER_TYPE_ID', 'UserTypeId', 'INTEGER', 'user_type', 'ID', false, 5, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('UserType', 'LeDjassa\\AdsBundle\\Model\\UserType', RelationMap::MANY_TO_ONE, array('user_type_id' => 'id', ), null, null);
    } // buildRelations()

} // UserTableMap
