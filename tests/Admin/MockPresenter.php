<?php
declare(strict_types=1);

namespace Kollarovic\Admin\Test;

use Kollarovic\Admin\AdminControlFactory;
use Kollarovic\Admin\LoginControlFactory;
use Nette\Application\UI\InvalidLinkException;
use Nette\Application\UI\Presenter;
use Nette\Security\Identity;


class MockPresenter extends Presenter
{

	/** @var AdminControlFactory @inject */
	public $adminControlFactory;

	/** @var  LoginControlFactory @inject */
	public $loginControlFactory;

	private $currentDestination = 'Setting:web';

	private $linkToUrl = [
		'Homepage:default' => '/',
		'//Homepage:default' => 'http://example.com/',
		'Page:default' => '/page/default',
		'Page:list' => '/page/list',
		'this' => '/setting/web',
		'page' => '/setting/page',
		'Setting:default' => '/setting/default',
		'Setting:base' => '/setting/base',
		'Setting:advanced' => '/setting/advanced',
		'Setting:web' => '/setting/web',
		'Setting:mail' => '/setting/mail',
	];


	public function link(string $destination, $args = []): string
	{
		if (!array_key_exists($destination, $this->linkToUrl)) {
			throw new InvalidLinkException("'$destination'");
		}

		return $this->linkToUrl[$destination];
	}


	public function isLinkCurrent(string $destination = null, $args = []): bool
	{
		return $destination == $this->currentDestination;
	}


	public function actionDefault()
	{
		$this->terminate();
	}


	protected function createComponentAdminControl()
	{
		$this->user->login(new Identity(1));

		$this->saveGlobalState();
		$adminControl = $this->adminControlFactory->create();
		$adminControl->setUserName('Mario Kollarovic')->setUserImage('images/user.png');
		$adminControl->setProfileUrl('/');
		$adminControl->setSkin('red');
		$adminControl->onLoggedOut[] = function () {
			$this->redirect('Sign:in');
		};
		return $adminControl;
	}


	protected function createComponentLoginControl()
	{
		$loginControl = $this->loginControlFactory->create();
		return $loginControl;
	}
}
