<?php

namespace LeDjassa\AdsBundle\Tests\Controller;
use LeDjassa\AdsBundle\Model\Ad;

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
		   $showAdCrawler = $client->click($showAdLink);
		 */

		$showAdLink = $listAdCrawler->filter("ul:first li a");
		$showAdCrawler = $client->click($showAdLink->link());

		//$showAdCrawler = $client->followRedirect();

		// right controller after routing
		$this->assertEquals('LeDjassa\AdsBundle\Controller\AdController::showAction', $client->getRequest()->attributes->get('_controller'));

		// page show ad
		$this->assertEquals(1, $showAdCrawler->filter('h3:contains("Details de l\'annonce")')->count());

		// check it's right ad if match with title ad by exemple by getting most recent ad
	}

	public function testAdd()
	{
		$client = static::createClient();

		$addAdCrawler = $client->request('GET', '/annonces/ajouter');

		// right controller
		$this->assertEquals('LeDjassa\AdsBundle\Controller\AdController::showAction', $client->getRequest()->attributes->get('_controller'));

		// right title
		$this->assertEquals(1, $addAdCrawler->filter('body:contains("Ajouter une annonce")')->count());

		$form = $addAdCrawler->selectButton('submit')->form();

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

		$form['ad[picture_ad][file]'] = __DIR__.'/../../../../../web/bundles/ledjassa/images/photo.jpg'
		//$form['ad[picture_ad][file]']->upload('/path/to/photo.jpg');

		$formAddAdCrawler = $client->submit($form);

		// right controller for add ad
		$this->assertEquals('LeDjassa\AdsBundle\Controller\AdController::showAction', $client->getRequest()->attributes->get('_controller'));

		$addAdSuccessCrawler = $client->followRedirect();

		// title match Todo : get most recent ad and retrieve the title
		$this->assertEquals(1, $addAdSuccessCrawler->filter('body:contains("Nous avons bien reçu votre annonce HTC Evo.")')->count());
	}

	public function testShow()
	{
		$client = static::createClient();
		// get one job active
		$id = 1;
		$title = '';
		$showAdCrawler = $client->request('GET', 'annonces/afficher/'.$id);

		// right controller
		$this->assertEquals('LeDjassa\AdsBundle\Controller\AdController::showAction', $client->getRequest()->attributes->get('_controller'));

		// title
		$this->assertEquals(1, $showAdCrawler->filter('h3:contains("Details de l\'annonce")')->count());

		// title ad
		$this->assertEquals(1, $showAdCrawler->filter('html:contains(.' $title '.)')->count());
	}

	public function testDelete()
	{
		$client = static::createClient();
		// get one ad actived or create
		$id = 1;
		$title = '';
		$password = '';
		$deleteAdCrawler = $client->request('GET', 'annonces/supprimer/'.$id);

		// right controller
		$this->assertEquals('LeDjassa\AdsBundle\Controller\AdController::deleteAction', $client->getRequest()->attributes->get('_controller'));

		// title
		$this->assertEquals(1, $deleteAdCrawler->filter('h3:contains("Suppression de l\'annonce")')->count());

		// title ad
		$this->assertEquals(1, $deleteAdCrawler->filter('html:contains(.' $title '.)')->count());

		$form['ad_delete[user_password]'] = $password;

		$form = $deleteAdCrawler->selectButton('submit')->form();

		$formDeleteAdCrawler = $client->submit($form);

		$formDeleteAdCrawler = $client->followRedirect();

		// title match Todo : get most recent ad and retrieve the title
		$this->assertEquals(1, $formDeleteAdCrawler->filter('body:contains("Merci bien supprimer")')->count());
	}

	/* Get most recent ad and test if match in list ad first // create ad
	public function getMostRecentAd {

	}

	public function createAd() {
		$salt = '';
		$password = '';

		$ad = new Ad();
		$ad
		  ->setTitle('mon titre')
		  ->setDescription('ma description')
		  ->setPrice('12')
		  ->setAdTypeId(1)
		  ->setCategoryId(1)
		  ->setUserTypeId(1)
		  ->setCityId(1)
		  ->setQuarterId('cocody')
		  ->setUserPhone('06 25 51 08 56')
		  -setUserName('landry')
		  ->setUserEmail('as.landry@gmail.com')
		  ->setUserIpAdress('127.0.0.1')
		  ->setUserSalt($salt)
		  ->setUserPassword($password)
		  ->save();
	}
	 */
}