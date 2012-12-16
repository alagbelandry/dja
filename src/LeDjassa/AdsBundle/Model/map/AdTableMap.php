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
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 100, null);
        $this->getColumn('title', false)->setPrimaryString(true);
        $this->addColumn('description', 'Description', 'VARCHAR', false, 500, null);
        $this->addColumn('price', 'Price', 'VARCHAR', false, 30, null);
        $this->addColumn('statut', 'Statut', 'TINYINT', false, 2, 0);
        $this->addColumn('user_name', 'UserName', 'VARCHAR', true, 100, null);
        $this->addColumn('user_email', 'UserEmail', 'VARCHAR', true, 100, null);
        $this->addColumn('user_password', 'UserPassword', 'VARCHAR', true, 255, null);
        $this->addColumn('user_salt', 'UserSalt', 'VARCHAR', true, 100, null);
        $this->addColumn('user_phone', 'UserPhone', 'VARCHAR', false, 50, null);
        $this->addColumn('user_ip_adress', 'UserIpAdress', 'VARCHAR', false, 40, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('ad_type_id', 'AdTypeId', 'INTEGER', 'ad_type', 'id', false, 5, null);
        $this->addForeignKey('category_id', 'CategoryId', 'INTEGER', 'category', 'id', false, 6, null);
        $this->addForeignKey('user_type_id', 'UserTypeId', 'INTEGER', 'user_type', 'id', false, 6, null);
        $this->addForeignKey('city_id', 'CityId', 'INTEGER', 'city', 'id', false, 6, null);
        $this->addForeignKey('quarter_id', 'QuarterId', 'INTEGER', 'quarter', 'id', false, 6, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
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
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
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

} // AdTableMap
