<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Kollarovic\Navigation\Item;
use Kollarovic\Navigation\ItemsFactory;
use Kollarovic\Navigation\NavigationControl;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Localization\ITranslator;
use Nette\Security\User;
use Nette\UnexpectedValueException;


class AdminControl extends Control
{
	/** @var array */
	public $onLoggedOut;

	/** @var array */
	public $onSearch;

	/** @var ItemsFactory */
	private $itemsFactory;

	/** @var ILoaderFactory */
	private $loaderFactory;

	/** @var ITranslator|null */
	private $translator;

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
	private $adminShortName;

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

	/** @var bool */
	private $sidebarCollapse = false;

	/** @var string */
	private $profileUrl;

	/** @var bool */
	private $ajaxRequest = false;


	public function __construct(ItemsFactory $itemsFactory, ILoaderFactory $loaderFactory, User $user, ITranslator $translator = null)
	{
		$this->itemsFactory = $itemsFactory;
		$this->loaderFactory = $loaderFactory;
		$this->user = $user;
		$this->translator = $translator;
		$this->templateFile = __DIR__ . '/templates/AdminControl.latte';
	}


	/********************************************************************************
	 *                                    Render                                    *
	 ********************************************************************************/


	public function getTemplateFile(): string
	{
		return $this->templateFile;
	}


	public function setTemplateFile(string $templateFile): self
	{
		$this->templateFile = $templateFile;
		return $this;
	}


	public function render(array $options = []): void
	{
		$template = $this->getTemplate();

		if (!$template instanceof Template) {
			throw new UnexpectedValueException();
		}

		if ($this->translator) {
			$template->setTranslator($this->translator);
		} else {
			$template->addFilter('translate', function ($str) {return $str;});
		}

		$template->setFile($this->getTemplateFile());
		$template->pageTitle = $this->pageTitle;
		$template->skin = $this->skin;
		$template->profileUrl = $this->profileUrl;
		$template->userName = $this->userName;
		$template->userImage = $this->userImage;
		$template->adminName = $this->adminName;
		$template->adminShortName = $this->adminShortName;
		$template->pageName = $this->pageName;
		$template->content = $this->content;
		$template->header = $this->header;
		$template->footer = $this->footer;
		$template->navbar = $this->navbar;
		$template->ajax = $this->ajaxRequest;
		$template->rootItem = $this->getRootItem();
		$template->showSearchForm = (bool) $this->onSearch;
		$template->sidebarCollapse = $this->sidebarCollapse;

		foreach ($options as $key => $value) {
			$template->$key = $value;
		}
		$template->render();
	}


	public function renderPanel(array $options = []): void
	{
		$this['navigation']->renderPanel($options);
	}


	/********************************************************************************
	 *                                   Signals                                    *
	 ********************************************************************************/


	public function handleSignOut(): void
	{
		$this->user->logout();
		$this->onLoggedOut();
	}


	/********************************************************************************
	 *                                  Components                                  *
	 ********************************************************************************/


	protected function createComponentSearchForm(): Form
	{
		$form = new Form();
		$form->addText('key');
		$form->onSuccess[] = function (\Nette\Forms\Form $form): void {
			$this->onSearch($form->values->key);
		};
		return $form;
	}


	protected function createComponentCss(): Control
	{
		return $this->loaderFactory->createCssLoader();
	}


	protected function createComponentJs(): Control
	{
		return $this->loaderFactory->createJavaScriptLoader();
	}


	protected function createComponentNavigation(): NavigationControl
	{
		$navigation = new NavigationControl($this->getRootItem(), $this->translator);
		return $navigation;
	}


	/********************************************************************************
	 *                              Getters and Setters                             *
	 ********************************************************************************/


	public function getRootItem(): Item
	{
		return $this->itemsFactory->create($this->navigationName);
	}


	public function getPageTitle(): string
	{
		return $this->pageTitle;
	}


	public function setPageTitle(string $pageTitle): self
	{
		$this->pageTitle = $pageTitle;
		return $this;
	}


	public function getSkin(): string
	{
		return $this->skin;
	}


	public function setSkin(string $skin): self
	{
		$this->skin = $skin;
		return $this;
	}


	public function getAdminName(): string
	{
		return $this->adminName;
	}


	public function setAdminName(string $adminName): self
	{
		$this->adminName = $adminName;
		return $this;
	}


	public function getAdminShortName(): string
	{
		return $this->adminShortName;
	}


	public function setAdminShortName(string $adminShortName): self
	{
		$this->adminShortName = $adminShortName;
		return $this;
	}


	public function getUserName(): string
	{
		return $this->userName;
	}


	public function setUserName(string $userName): self
	{
		$this->userName = $userName;
		return $this;
	}


	public function getUserImage(): string
	{
		return $this->userImage;
	}


	public function setUserImage(string $userImage): self
	{
		$this->userImage = $userImage;
		return $this;
	}


	public function getPageName(): string
	{
		return $this->pageName;
	}


	public function setPageName(string $pageName): self
	{
		$this->pageName = $pageName;
		return $this;
	}


	public function getContent()
	{
		return $this->content;
	}


	public function setContent(string $content): self
	{
		$this->content = $content;
		return $this;
	}


	public function getHeader(): string
	{
		return $this->header;
	}


	public function setHeader(string $header): self
	{
		$this->header = $header;
		return $this;
	}


	public function getFooter(): string
	{
		return $this->footer;
	}


	public function setFooter(string $footer): self
	{
		$this->footer = $footer;
		return $this;
	}


	public function getNavbar(): string
	{
		return $this->navbar;
	}


	public function setNavbar(string $navbar): self
	{
		$this->navbar = $navbar;
		return $this;
	}


	public function getNavigationName(): string
	{
		return $this->navigationName;
	}


	public function setNavigationName(string $navigationName): self
	{
		$this->navigationName = $navigationName;
		return $this;
	}


	public function getSidebarCollapse(): bool
	{
		return $this->sidebarCollapse;
	}


	public function setSidebarCollapse(bool $sidebarCollapse): self
	{
		$this->sidebarCollapse = $sidebarCollapse;
		return $this;
	}


	public function getProfileUrl(): string
	{
		return $this->profileUrl;
	}


	public function setProfileUrl(string $profileUrl): self
	{
		$this->profileUrl = $profileUrl;
		return $this;
	}


	public function isAjaxRequest(): bool
	{
		return $this->ajaxRequest;
	}


	public function setAjaxRequest(bool $ajaxRequest): self
	{
		$this->ajaxRequest = $ajaxRequest;
		return $this;
	}
}
