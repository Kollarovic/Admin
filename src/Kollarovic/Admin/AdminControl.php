<?php

namespace Kollarovic\Admin;

use Kollarovic\Navigation\ItemsFactory;
use Kollarovic\Navigation\NavigationControl;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\User;


/**
 * Class AdminControl
 * @package Kollarovic\Admin
 */
class AdminControl extends Control
{

	/** @var array */
	public $onLoggedOut;

	/** @var array */
	public $onSearch;

	/** @var IItemsFactory */
	private $itemsFactory;

	/** @var ILoaderFactory */
	private $loaderFactory;

	/** @var User */
	private $user;

	/** @var string */
	private $templateFile;

	/** @var string */
	private $pageTitle;

	/** @var string */
	private $skin;

	/** @var string */
	private $adminName;

	/** @var string */
	private $userName;

	/** @var string */
	private $userImage;

	/** @var string */
	private $pageName;

	/** @var string */
	private $content;

	/** @var string */
	private $header;

	/** @var string */
	private $footer;

	/** @var string */
	private $navbar;

	/** @var string */
	private $navigationName;

	/** @var string */
	private $profileUrl;

	/** @var boolean */
	private $ajaxRequest = FALSE;


	function __construct(ItemsFactory $itemsFactory, ILoaderFactory $loaderFactory, User $user)
	{
		$this->itemsFactory = $itemsFactory;
		$this->loaderFactory = $loaderFactory;
		$this->user = $user;
		$this->templateFile = __DIR__ . '/templates/AdminControl.latte';
	}


	public function render(array $options = [])
	{
		$this->template->setFile($this->getTemplateFile());
		$this->template->pageTitle = $this->pageTitle;
		$this->template->skin = $this->skin;
		$this->template->profileUrl = $this->profileUrl;
		$this->template->userName = $this->userName;
		$this->template->userImage = $this->userImage;
		$this->template->adminName = $this->adminName;
		$this->template->pageName = $this->pageName;
		$this->template->content = $this->content;
		$this->template->header = $this->header;
		$this->template->footer = $this->footer;
		$this->template->navbar = $this->navbar;
		$this->template->ajax = $this->ajaxRequest;
		$this->template->rootItem = $this->getRootItem();
		foreach ($options as $key => $value) {
			$this->template->$key = $value;
		}
		$this->template->render();
	}


	public function renderPanel(array $options = [])
	{
		$this['navigation']->renderPanel($options);
	}


	public function handleSignOut()
	{
		$this->user->logout();
		$this->onLoggedOut();
	}


	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		if (!array_key_exists('translate', $template->getLatte()->getFilters())) {
			$template->addFilter('translate', function($str){return $str;});
		}
		return $template;
	}


	protected function createComponentSearchForm()
	{
		$form = new Form();
		$form->addText('key');
		$form->onSuccess[] = function($form) {
			$this->onSearch($form->values->key);
		};
		return $form;
	}


	protected function createComponentCss()
	{
		return $this->loaderFactory->createCssLoader();
	}


	protected function createComponentJs()
	{
		return $this->loaderFactory->createJavaScriptLoader();
	}


	protected function createComponentNavigation()
	{
		return new NavigationControl($this->getRootItem());
	}


	private function getRootItem()
	{
		return $this->itemsFactory->create($this->navigationName);
	}

    public function setTemplateFile($templateFile)
    {
        $this->templateFile = $templateFile;
    }
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }
    public function setSkin($skin)
    {
        $this->skin = $skin;
    }
    public function setAdminName($adminName)
    {
        $this->adminName = $adminName;
    }
    public function setUserName( $userName)
    {
        $this->userName = $userName;
    }
    public function setUserImage( $userImage)
    {
        $this->userImage = $userImage;
    }
    public function setPageName( $pageName)
    {
        $this->pageName = $pageName;
    }
    public function setContent( $content)
    {
        $this->content = $content;
    }
    public function setHeader( $header)
    {
        $this->header = $header;
    }
    public function setFooter( $footer)
    {
        $this->footer = $footer;
    }
    public function setNavbar( $navbar)
    {
        $this->navbar = $navbar;
    }
    public function setNavigationName( $navigationName)
    {
        $this->navigationName = $navigationName;
    }
    public function setProfileUrl( $profileUrl)
    {
        $this->profileUrl = $profileUrl;
    }
    public function setAjaxRequest($ajaxRequest)
    {
        $this->ajaxRequest = $ajaxRequest;
    }


    public function getTemplateFile()
    {
        return $this->templateFile;
    }
    public function getPageTitle()
    {
        return $this->pageTitle;
    }
    public function getSkin()
    {
        return $this->skin;
    }
    public function getAdminName()
    {
        return $this->adminName;
    }
    public function getUserName()
    {
        return $this->userName;
    }
    public function getUserImage()
    {
        return $this->userImage;
    }
    public function getPageName()
    {
        return $this->pageName;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function getHeader()
    {
        return $this->header;
    }
    public function getFooter()
    {
        return $this->footer;
    }
    public function getNavbar()
    {
        return $this->navbar;
    }
    public function getNavigationName()
    {
        return $this->navigationName;
    }
    public function getProfileUrl()
    {
        return $this->profileUrl;
    }
    public function isAjaxRequest()
    {
        return $this->ajaxRequest;
    }
}
