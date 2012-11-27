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
        $this->addColumn('TITLE', 'Title', 'VARCHAR', true, 100, null);
        $this->getColumn('TITLE', false)->setPrimaryString(true);
        $this->addColumn('DESCRIPTION', 'Description', 'VARCHAR', false, 500, null);
        $this->addColumn('PRICE', 'Price', 'VARCHAR', false, 30, null);
        $this->addColumn('STATUT', 'Statut', 'TINYINT', false, 2, 0);
        $this->addColumn('USER_NAME', 'UserName', 'VARCHAR', true, 100, null);
        $this->addColumn('USER_EMAIL', 'UserEmail', 'VARCHAR', true, 100, null);
        $this->addColumn('USER_PASSWORD', 'UserPassword', 'VARCHAR', true, 255, null);
        $this->addColumn('USER_SALT', 'UserSalt', 'VARCHAR', true, 100, null);
        $this->addColumn('USER_PHONE', 'UserPhone', 'VARCHAR', false, 50, null);
        $this->addColumn('USER_IP_ADRESS', 'UserIpAdress', 'VARCHAR', false, 40, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('AD_TYPE_ID', 'AdTypeId', 'INTEGER', 'ad_type', 'ID', false, 5, null);
        $this->addForeignKey('CATEGORY_ID', 'CategoryId', 'INTEGER', 'category', 'ID', false, 6, null);
        $this->addForeignKey('USER_TYPE_ID', 'UserTypeId', 'INTEGER', 'user_type', 'ID', false, 6, null);
        $this->addForeignKey('CITY_ID', 'CityId', 'INTEGER', 'city', 'ID', false, 6, null);
        $this->addForeignKey('QUARTER_ID', 'QuarterId', 'INTEGER', 'quarter', 'ID', false, 6, null);
        $this->addColumn('SLUG', 'Slug', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('City', 'LeDjassa\\AdsBundle\\Model\\City', RelationMap::MANY_TO_ONE, array('city_id' => 'id', ), null, null);
        $this->addRelation('UserType', 'LeDjassa\\AdsBundle\\Model\\UserType', RelationMap::MANY_TO_ONE, array('user_type_id' => 'id', ), null, null);
        $this->addRelation('AdType', 'LeDjassa\\AdsBundle\\Model\\AdType', RelationMap::MANY_TO_ONE, array('ad_type_id' => 'id', ), null, null);
        $this->addRelation('Category', 'LeDjassa\\AdsBundle\\Model\\Category', RelationMap::MANY_TO_ONE, array('category_id' => 'id', ), null, null);
        $this->addRelation('Quarter', 'LeDjassa\\AdsBundle\\Model\\Quarter', RelationMap::MANY_TO_ONE, array('quarter_id' => 'id', ), null, null);
        $this->addRelation('InterestedUser', 'LeDjassa\\AdsBundle\\Model\\InterestedUser', RelationMap::ONE_TO_MANY, array('id' => 'ad_id', ), null, null, 'InterestedUsers');
        $this->addRelation('PictureAd', 'LeDjassa\\AdsBundle\\Model\\PictureAd', RelationMap::ONE_TO_MANY, array('id' => 'ad_id', ), null, null, 'PictureAds');
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
            'sluggable' => array('slug_column' => 'slug', 'slug_pattern' => '', 'replace_pattern' => '/\W+/', 'replacement' => '-', 'separator' => '-', 'permanent' => 'false', 'scope_column' => '', ),
        );
    } // getBehaviors()

} // AdTableMap
