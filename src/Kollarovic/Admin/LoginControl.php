<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Kollarovic\Admin\Form\LoginFormFactory;
use Kollarovic\Admin\Loader\LoaderFactory;
use Kollarovic\Admin\Translator\FallbackTranslator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;


/**
 * @property-read LoginTemplate $template
 */
class LoginControl extends Control
{
	/** @var array<callable(self): void> */
	public array $onLoggedIn = [];
	private string $templateType = 'AdminLte2';
	private ?string $templateFile = null;
	private string $pageTitle = 'Login - Admin';
	private string $pageName = 'Admin';
	private ?string $pageMsg = null;


	public function __construct(
		private LoginFormFactory $loginFormFactory,
		private LoaderFactory    $loaderFactory,
		private ?Translator      $translator = null
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
		return $this->templateFile ?? __DIR__ . "/templates/{$this->templateType}/LoginControl.latte";
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
		$this->template->setTranslator($this->translator ?? new FallbackTranslator());
		$this->template->setFile($this->getTemplateFile());
		$this->template->pageTitle = $this->pageTitle;
		$this->template->pageName = $this->pageName;
		$this->template->pageMsg = $this->pageMsg;

		foreach ($options as $key => $value) {
			$this->template->$key = $value;
		}
		$this->template->render();
	}


	/********************************************************************************
	 *                                  Components                                  *
	 ********************************************************************************/


	protected function createComponentForm(): Form
	{
		$form = $this->loginFormFactory->create();
		$form->onSuccess[] = function (\Nette\Forms\Form $form): void {
			$this->onLoggedIn($form);
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


	/********************************************************************************
	 *                              Getters and Setters                             *
	 ********************************************************************************/


	public function getPageTitle(): string
	{
		return $this->pageTitle;
	}


	public function setPageTitle(string $pageTitle): static
	{
		$this->pageTitle = $pageTitle;
		return $this;
	}


	public function getPageName(): string
	{
		return $this->pageName;
	}


	public function setPageName(string $pageName): static
	{
		$this->pageName = $pageName;
		return $this;
	}


	public function getPageMsg(): ?string
	{
		return $this->pageMsg;
	}


	public function setPageMsg(?string $pageMsg): static
	{
		$this->pageMsg = $pageMsg;
		return $this;
	}

}