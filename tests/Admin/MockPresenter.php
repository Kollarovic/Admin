<?php

namespace Kollarovic\Admin\Test;

use Kollarovic\Admin\IAdminControlFactory;
use Kollarovic\Admin\ILoginControlFactory;
use Nette\Application\UI\Presenter;
use Nette\Application\UI\InvalidLinkException;
use Nette\Security\Identity;


class MockPresenter extends Presenter
{

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

    /** @var IAdminControlFactory @inject */
    public $adminControlFactory;

    /** @var  ILoginControlFactory @inject */
    public $loginControlFactory;


	public function link($destination, $args = array())
	{
		if (!array_key_exists($destination, $this->linkToUrl)) {
			throw new InvalidLinkException("'$destination'");
		}

		return $this->linkToUrl[$destination];
	}


	public function isLinkCurrent($destination = NULL, $args = array())
	{
		return ($destination == $this->currentDestination);
	}


    protected function createComponentAdminControl()
    {
        $this->user->login(new Identity(1));
        $this->saveGlobalState();
        $adminControl = $this->adminControlFactory->create();
        $adminControl->setUserName('Mario Kollarovic')->setUserImage('images/user.png');
        $adminControl->setProfileUrl('/');
        $adminControl->setSkin('red');
        $adminControl->onLoggedOut[] = function() {
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