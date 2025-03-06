<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Kollarovic\Admin\Loader\ILoaderFactory;
use Kollarovic\Navigation\BaseControl;
use Kollarovic\Navigation\BreadcrumbControl;
use Kollarovic\Navigation\Item;
use Kollarovic\Navigation\ItemsFactory;
use Kollarovic\Navigation\MenuControl;
use Kollarovic\Navigation\PanelControl;
use Kollarovic\Navigation\TitleControl;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Localization\Translator;
use Nette\Security\User;
use Nette\UnexpectedValueException;
use ReflectionClass;


class AdminControl extends Control
{
	/** @var array<callable(self): void> */
	public array $onLoggedOut = [];

	/** @var array<callable(self): void> */
	public array $onSearch = [];

	private string $templateType = 'AdminLte2';
	private ?string $templateFile = null;
	private string $pageTitle = 'Admin';
	private string $skin = 'red';
	private string $adminName = 'Admin';
	private string $adminShortName = 'A';
	private string $userName = 'Admin';
	private ?string $userImage = null;
	private string $pageName = 'Admin';
	private ?string $content = null;
	private ?string $header = null;
	private ?string $footer = null;
	private ?string $navbar = null;
	private string $navigationName = 'admin';
	private bool $sidebarCollapse = false;
	private ?string $profileUrl = null;
	private bool $ajaxRequest = false;


	public function __construct(
		private ItemsFactory $itemsFactory,
		private ILoaderFactory $loaderFactory,
		private User $user,
		private ?Translator $translator = null
	) {
	}


	/********************************************************************************
	 *                                    Render                                    *
	 ********************************************************************************/


	public function getTemplateType(): string
	{
		return $this->templateType;
	}


	public function setTemplateType(string $templateType): self
	{
		$this->templateType = $templateType;
		return $this;
	}


	public function getTemplateFile(): string
	{
		return $this->templateFile ?? $this->buildTemplateFilePath('AdminControl');
	}


	public function setTemplateFile(string $templateFile): self
	{
		$this->templateFile = $templateFile;
		return $this;
	}


	/**
	 * @param array<string, mixed> $options
	 */
	public function render(array $options = []): void
	{
		$template = $this->getTemplate();

		if (!$template instanceof Template) {
			throw new UnexpectedValueException();
		}

		$template->setTranslator($this->translator ? $this->translator : new FallbackTranslator());
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


	/**
	 * @param array<string, mixed> $options
	 */
	public function renderPanel(array $options = []): void
	{
		$this['panel']->render($options);
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


	protected function createComponentMenu(): BaseControl
	{
		return $this->initializeTemplate(new MenuControl($this->getRootItem(), $this->translator));
	}


	protected function createComponentBreadcrumb(): BaseControl
	{
		return $this->initializeTemplate(new BreadcrumbControl($this->getRootItem(), $this->translator));
	}


	protected function createComponentPanel(): BaseControl
	{
		return $this->initializeTemplate(new PanelControl($this->getRootItem(), $this->translator));
	}


	protected function createComponentTitle(): BaseControl
	{
		return $this->initializeTemplate(new TitleControl($this->getRootItem(), $this->translator));
	}


	private function initializeTemplate(BaseControl $baseControl): BaseControl
	{
		$reflection = new ReflectionClass($baseControl);
		$templateFile = $this->buildTemplateFilePath($reflection->getShortName());
		if (is_file($templateFile)) {
			$baseControl->setTemplateFile($templateFile);
		}
		return $baseControl;
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


	public function getUserImage(): ?string
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


	public function getContent(): ?string
	{
		return $this->content;
	}


	public function setContent(string $content): self
	{
		$this->content = $content;
		return $this;
	}


	public function getHeader(): ?string
	{
		return $this->header;
	}


	public function setHeader(string $header): self
	{
		$this->header = $header;
		return $this;
	}


	public function getFooter(): ?string
	{
		return $this->footer;
	}


	public function setFooter(string $footer): self
	{
		$this->footer = $footer;
		return $this;
	}


	public function getNavbar(): ?string
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


	public function getProfileUrl(): ?string
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


	private function buildTemplateFilePath(string $componentName): string
	{
		return __DIR__ . "/templates/{$this->templateType}/{$componentName}.latte";
	}

}
