<?php

namespace LeDjassa\AdsBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'interested_user' table.
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
class InterestedUserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LeDjassa.AdsBundle.Model.map.InterestedUserTableMap';

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
        $this->setName('interested_user');
        $this->setPhpName('InterestedUser');
        $this->setClassname('LeDjassa\\AdsBundle\\Model\\InterestedUser');
        $this->setPackage('src.LeDjassa.AdsBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 6, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', false, 100, null);
        $this->getColumn('NAME', false)->setPrimaryString(true);
        $this->addColumn('EMAIL', 'Email', 'VARCHAR', false, 100, null);
        $this->addColumn('PHONE', 'Phone', 'VARCHAR', false, 100, null);
        $this->addColumn('MESSAGE', 'Message', 'VARCHAR', false, 500, null);
        $this->addColumn('IP_ADRESS', 'IpAdress', 'VARCHAR', false, 20, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('AD_ID', 'AdId', 'INTEGER', 'ad', 'ID', false, 5, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Ad', 'LeDjassa\\AdsBundle\\Model\\Ad', RelationMap::MANY_TO_ONE, array('ad_id' => 'id', ), null, null);
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_updated_at' => 'false', ),
        );
    } // getBehaviors()

} // InterestedUserTableMap
