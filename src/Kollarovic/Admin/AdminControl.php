<?php

namespace Kollarovic\Admin;

use Kollarovic\Navigation\ItemsFactory;
use Kollarovic\Navigation\NavigationControl;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\User;


/**
 * @method AdminControl setTemplateFile(string $templateFile)
 * @method AdminControl setPageTitle(string $pageTitle)
 * @method AdminControl setSkin(string $skin)
 * @method AdminControl setAdminName(string $adminName)
 * @method AdminControl setUserName(string $userName)
 * @method AdminControl setUserImage(string $userImage)
 * @method AdminControl setPageName(string $pageName)
 * @method AdminControl setContent(string $content)
 * @method AdminControl setHeader(string $header)
 * @method AdminControl setFooter(string $footer)
 * @method AdminControl setNavbar(string $navbar)
 * @method AdminControl setNavigationName(string $navigationName)
 * @method AdminControl setProfileUrl(string $profileUrl)
 *
 * @method string getTemplateFile()
 * @method string getPageTitle()
 * @method string getSkin()
 * @method string getAdminName()
 * @method string getUserName()
 * @method string getUserImage()
 * @method string getPageName()
 * @method string getContent()
 * @method string getHeader()
 * @method string getFooter()
 * @method string getNavbar()
 * @method string getNavigationName()
 * @method string getProfileUrl()
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

}
