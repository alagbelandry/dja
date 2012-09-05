<?php

namespace LeDjassa\AdsBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'category' table.
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
class CategoryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.LeDjassa.AdsBundle.Model.map.CategoryTableMap';

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
        $this->setName('category');
        $this->setPhpName('Category');
        $this->setClassname('LeDjassa\\AdsBundle\\Model\\Category');
        $this->setPackage('src.LeDjassa.AdsBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, 6, null);
        $this->addColumn('TITLE', 'Title', 'VARCHAR', false, 100, null);
        $this->addColumn('CODE', 'Code', 'VARCHAR', false, 20, null);
        $this->addForeignKey('CATEGORY_TYPE_ID', 'categoryTypeId', 'INTEGER', 'category_type', 'ID', false, 5, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CategoryType', 'LeDjassa\\AdsBundle\\Model\\CategoryType', RelationMap::MANY_TO_ONE, array('category_type_id' => 'id', ), null, null);
        $this->addRelation('Ad', 'LeDjassa\\AdsBundle\\Model\\Ad', RelationMap::ONE_TO_MANY, array('id' => 'category_id', ), null, null, 'Ads');
    } // buildRelations()

} // CategoryTableMap
