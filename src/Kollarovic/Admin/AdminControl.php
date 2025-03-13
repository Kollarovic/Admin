<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Kollarovic\Admin\Loader\LoaderFactory;
use Kollarovic\Admin\Translator\FallbackTranslator;
use Kollarovic\Navigation\BaseControl;
use Kollarovic\Navigation\BreadcrumbControl;
use Kollarovic\Navigation\Item;
use Kollarovic\Navigation\ItemsFactory;
use Kollarovic\Navigation\MenuControl;
use Kollarovic\Navigation\PanelControl;
use Kollarovic\Navigation\TitleControl;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;
use Nette\Security\User;
use ReflectionClass;


/**
 * @property-read AdminTemplate $template
 */
class AdminControl extends Control
{
	/** @var array<callable(self): void> */
	public array $onLoggedOut = [];

	/** @var array<callable(self): void> */
	public array $onSearch = [];

	private TemplateType $templateType = TemplateType::AdminLte2;
	private ?string $templateFile = null;
	private ?string $pageTitle = null;
	private string $skin = 'red';
	private string $adminName = 'Admin';
	private string $adminShortName = 'A';
	private string $userName = 'Admin';
	private ?string $userImage = null;
	private ?string $pageName = null;
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
		private LoaderFactory $loaderFactory,
		private User $user,
		private Translator $translator = new FallbackTranslator()
	) {
	}


	/********************************************************************************
	 *                                    Render                                    *
	 ********************************************************************************/


	public function getTemplateType(): TemplateType
	{
		return $this->templateType;
	}


	public function setTemplateType(TemplateType $templateType): static
	{
		$this->templateType = $templateType;
		return $this;
	}


	public function getTemplateFile(): string
	{
		return $this->templateFile ?? $this->buildTemplateFilePath('AdminControl');
	}


	public function setTemplateFile(string $templateFile): static
	{
		$this->templateFile = $templateFile;
		return $this;
	}


	/**
	 * @param array<string, mixed> $options
	 */
	public function render(array $options = []): void
	{
		$this->template->setTranslator($this->translator ?? new FallbackTranslator());
		$this->template->setFile($this->getTemplateFile());
		$this->template->pageTitle = $this->pageTitle;
		$this->template->skin = $this->skin;
		$this->template->profileUrl = $this->profileUrl;
		$this->template->userName = $this->userName;
		$this->template->userImage = $this->userImage;
		$this->template->adminName = $this->adminName;
		$this->template->adminShortName = $this->adminShortName;
		$this->template->pageName = $this->pageName;
		$this->template->content = $this->content;
		$this->template->header = $this->header;
		$this->template->footer = $this->footer;
		$this->template->navbar = $this->navbar;
		$this->template->ajax = $this->ajaxRequest;
		$this->template->rootItem = $this->getRootItem();
		$this->template->showSearchForm = (bool) $this->onSearch;
		$this->template->sidebarCollapse = $this->sidebarCollapse;

		foreach ($options as $key => $value) {
			$this->template->$key = $value;
		}
		$this->template->render();
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
		return $this->loaderFactory->createCssLoader($this->templateType->value);
	}


	protected function createComponentJs(): Control
	{
		return $this->loaderFactory->createJavaScriptLoader($this->templateType->value,);
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


	public function getPageTitle(): ?string
	{
		return $this->pageTitle;
	}


	public function setPageTitle(?string $pageTitle): static
	{
		$this->pageTitle = $pageTitle;
		return $this;
	}


	public function getSkin(): string
	{
		return $this->skin;
	}


	public function setSkin(string $skin): static
	{
		$this->skin = $skin;
		return $this;
	}


	public function getAdminName(): string
	{
		return $this->adminName;
	}


	public function setAdminName(string $adminName): static
	{
		$this->adminName = $adminName;
		return $this;
	}


	public function getAdminShortName(): string
	{
		return $this->adminShortName;
	}


	public function setAdminShortName(string $adminShortName): static
	{
		$this->adminShortName = $adminShortName;
		return $this;
	}


	public function getUserName(): string
	{
		return $this->userName;
	}


	public function setUserName(string $userName): static
	{
		$this->userName = $userName;
		return $this;
	}


	public function getUserImage(): ?string
	{
		return $this->userImage;
	}


	public function setUserImage(string $userImage): static
	{
		$this->userImage = $userImage;
		return $this;
	}


	public function getPageName(): ?string
	{
		return $this->pageName;
	}


	public function setPageName(?string $pageName): static
	{
		$this->pageName = $pageName;
		return $this;
	}


	public function getContent(): ?string
	{
		return $this->content;
	}


	public function setContent(string $content): static
	{
		$this->content = $content;
		return $this;
	}


	public function getHeader(): ?string
	{
		return $this->header;
	}


	public function setHeader(string $header): static
	{
		$this->header = $header;
		return $this;
	}


	public function getFooter(): ?string
	{
		return $this->footer;
	}


	public function setFooter(string $footer): static
	{
		$this->footer = $footer;
		return $this;
	}


	public function getNavbar(): ?string
	{
		return $this->navbar;
	}


	public function setNavbar(string $navbar): static
	{
		$this->navbar = $navbar;
		return $this;
	}


	public function getNavigationName(): string
	{
		return $this->navigationName;
	}


	public function setNavigationName(string $navigationName): static
	{
		$this->navigationName = $navigationName;
		return $this;
	}


	public function getSidebarCollapse(): bool
	{
		return $this->sidebarCollapse;
	}


	public function setSidebarCollapse(bool $sidebarCollapse): static
	{
		$this->sidebarCollapse = $sidebarCollapse;
		return $this;
	}


	public function getProfileUrl(): ?string
	{
		return $this->profileUrl;
	}


	public function setProfileUrl(string $profileUrl): static
	{
		$this->profileUrl = $profileUrl;
		return $this;
	}


	public function isAjaxRequest(): bool
	{
		return $this->ajaxRequest;
	}


	public function setAjaxRequest(bool $ajaxRequest): static
	{
		$this->ajaxRequest = $ajaxRequest;
		return $this;
	}


	private function buildTemplateFilePath(string $componentName): string
	{
		return __DIR__ . "/templates/{$this->templateType->value}/{$componentName}.latte";
	}

}
