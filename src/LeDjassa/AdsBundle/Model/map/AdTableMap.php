<?php

namespace LeDjassa\AdsBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'ad' table.
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
class AdTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LeDjassa.AdsBundle.Model.map.AdTableMap';

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
        $this->setName('ad');
        $this->setPhpName('Ad');
        $this->setClassname('LeDjassa\\AdsBundle\\Model\\Ad');
        $this->setPackage('src.LeDjassa.AdsBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', false, 100, null);
        $this->getColumn('TITLE', false)->setPrimaryString(true);
        $this->addColumn('DESCRIPTION', 'Description', 'VARCHAR', false, 500, null);
        $this->addColumn('PRICE', 'Price', 'VARCHAR', false, 30, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('AD_TYPE_ID', 'AdTypeId', 'INTEGER', 'ad_type', 'ID', false, 5, null);
        $this->addForeignKey('CATEGORY_ID', 'CategoryId', 'INTEGER', 'category', 'ID', false, 6, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('AdType', 'LeDjassa\\AdsBundle\\Model\\AdType', RelationMap::MANY_TO_ONE, array('ad_type_id' => 'id', ), null, null);
        $this->addRelation('Category', 'LeDjassa\\AdsBundle\\Model\\Category', RelationMap::MANY_TO_ONE, array('category_id' => 'id', ), null, null);
        $this->addRelation('Quarter', 'LeDjassa\\AdsBundle\\Model\\Quarter', RelationMap::ONE_TO_MANY, array('id' => 'ad_id', ), null, null, 'Quarters');
        $this->addRelation('PictureAd', 'LeDjassa\\AdsBundle\\Model\\PictureAd', RelationMap::ONE_TO_MANY, array('id' => 'ad_id', ), null, null, 'PictureAds');
    } // buildRelations()

} // AdTableMap
