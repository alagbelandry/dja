<?php

namespace LeDjassa\AdsBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'city' table.
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
class CityTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LeDjassa.AdsBundle.Model.map.CityTableMap';

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
        $this->setName('city');
        $this->setPhpName('City');
        $this->setClassname('LeDjassa\\AdsBundle\\Model\\City');
        $this->setPackage('src.LeDjassa.AdsBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, 6, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('code', 'Code', 'VARCHAR', false, 20, null);
        $this->addForeignKey('area_id', 'AreaId', 'INTEGER', 'area', 'id', true, 5, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Area', 'LeDjassa\\AdsBundle\\Model\\Area', RelationMap::MANY_TO_ONE, array('area_id' => 'id', ), null, null);
        $this->addRelation('Quarter', 'LeDjassa\\AdsBundle\\Model\\Quarter', RelationMap::ONE_TO_MANY, array('id' => 'city_id', ), null, null, 'Quarters');
        $this->addRelation('Ad', 'LeDjassa\\AdsBundle\\Model\\Ad', RelationMap::ONE_TO_MANY, array('id' => 'city_id', ), null, null, 'Ads');
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
            'sluggable' =>  array (
  'slug_column' => 'slug',
  'slug_pattern' => '',
  'replace_pattern' => '/\\W+/',
  'replacement' => '-',
  'separator' => '-',
  'permanent' => 'false',
  'scope_column' => '',
),
        );
    } // getBehaviors()

} // CityTableMap
