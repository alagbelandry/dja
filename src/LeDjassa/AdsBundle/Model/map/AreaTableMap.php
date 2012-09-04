<?php

namespace LeDjassa\AdsBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'area' table.
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
class AreaTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LeDjassa.AdsBundle.Model.map.AreaTableMap';

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
        $this->setName('area');
        $this->setPhpName('Area');
        $this->setClassname('LeDjassa\\AdsBundle\\Model\\Area');
        $this->setPackage('src.LeDjassa.AdsBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 5, null);
        $this->addColumn('NAME', 'Name', 'VARCHAR', false, 100, null);
        $this->getColumn('NAME', false)->setPrimaryString(true);
        $this->addColumn('CODE', 'Code', 'VARCHAR', false, 20, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('City', 'LeDjassa\\AdsBundle\\Model\\City', RelationMap::ONE_TO_MANY, array('id' => 'area_id', ), null, null, 'Cities');
    } // buildRelations()

} // AreaTableMap
