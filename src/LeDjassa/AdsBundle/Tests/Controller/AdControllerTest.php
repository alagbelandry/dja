<?php

namespace LeDjassa\AdsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdControllerTest extends WebTestCase
{
	public function testIndexAction()
	{
		$client = static::createClient();

		$crawlerListAd = $client->request('GET', 'http://dev.ledjassa.com/app_dev.php');
		var_dump($crawlerListAd);die;
		// page list ad
		$this->assertEquals(1, $crawlerListAd->filter('h3:contains("Toutes les annonces")')->count());

		// at least one ad
		$this->assertGreaterThan(0, $crawlerListAd->filter('<ul>')->count());

		// link show ad
		$linkShowAd = $crawlerListAd->selectLink('Consulter l\'annonce')->first()->link();
		$crawlerShowAd = $linkShowAd->click($linkShowAd);

		$crawlerShowAd = $client->followRedirect();

		$this->assertEquals(1, $crawlerShowAd->filter('body:contains("Details de l\'annonce")')->count());
	}

	public function testAddAction()
	{
		$client = static::createClient();

		$crawlerAddAd = $client->request('GET', '/annonces/ajouter');

		// page add ad
		$this->assertEquals(1, $crawlerAddAd->filter('body:contains("Ajouter une annonce")')->count());

		$form = $crawlerAddAd->selectButton('submit')->form();

		// set form info
		$form['ad[title]'] = 'HTC Evo';
		$form['ad[description]'] = 'Un beau HTC Evo 3D';
		$form['ad[price]'] = 1000;

		$form['ad[user_name]'] = 'Alagbe Sedomon Landry';
		$form['ad[user_email]'] = 'as.landry@gmail.com';
		$form['ad[user_phone]'] = '06 25 51 08 56';
		$form['ad[user_password]'] = 'passer';

		// Select an option or a radio
		$form['ad[category]']->select('Voiture');
		$form['ad[user_type]']->select('Particulier');
		$form['ad[ad_type]']->select('Offre');

		$form['ad[city][area]']->select('Lagunes');
		$form['ad[city][name]'] = 'Abidjan';
		$form['ad[city][quarter]'] = 'cocody';

		$form['ad[picture_ad][file]']->upload('/path/to/photo.jpg');

		$crawlerFormAddAd = $client->submit($form);

		$crawlerFormAddAd = $client->followRedirect();

		// title match
		$this->assertEquals(1, $crawlerFormAddAd->filter('body:contains("Nous avons bien reçu votre annonce HTC Evo.")')->count());
	}
}