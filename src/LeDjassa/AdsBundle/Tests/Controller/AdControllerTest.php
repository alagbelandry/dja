<?php

namespace LeDjassa\AdsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdControllerTest extends WebTestCase
{

	public function testIndex()
	{
		$client = static::createClient();

		$listAdCrawler = $client->request('GET', '/annonces/');

		// right controller
		$this->assertEquals('LeDjassa\AdsBundle\Controller\AdController::indexAction', $client->getRequest()->attributes->get('_controller'));
		$this->assertTrue(200 === $client->getResponse()->getStatusCode());

		// right title
		$this->assertEquals(1, $listAdCrawler->filter('h3:contains("Toutes les annonces")')->count());

		// some ad
		$this->assertTrue($listAdCrawler->filter('ul')->count() > 0);

		// todo : test max ad on home page?

		// link show ad
		// other way to get link
		/* $showAdLink = $listAdCrawler->selectLink("Consulter l'annonce")->first()->link();
		   $crawlerShowAd = $client->click($showAdLink);
		 */

		$showAdLink = $listAdCrawler->filter("ul:first li a");
		$crawlerShowAd = $client->click($showAdLink->link());

		//$crawlerShowAd = $client->followRedirect();

		// right controller after routing
		$this->assertEquals('LeDjassa\AdsBundle\Controller\AdController::showAction', $client->getRequest()->attributes->get('_controller'));

		// page show ad
		$this->assertEquals(1, $crawlerShowAd->filter('h3:contains("Details de l\'annonce")')->count());

		// check it's right ad by getting most recent ad
	}

	/* Get most recent ad and test if match in list ad first
	public function getMostRecentAd {

	}
	 */

	public function testAdd()
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