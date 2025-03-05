<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Localization\Translator;
use Nette\UnexpectedValueException;


class LoginControl extends Control
{
	/** @var array<callable(self): void> */
	public array $onLoggedIn = [];
	private string $templateType = 'AdminLte2';
	private ?string $templateFile = null;
	private string $pageTitle = 'Login - Admin';
	private ?string $pageName = 'Admin';
	private ?string $pageMsg = null;
	private ?string $usernameIcon = null;
	private ?string $passwordIcon = null;


	public function __construct(
		private ILoginFormFactory $loginFormFactory,
		private ILoaderFactory $loaderFactory,
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
		$template = $this->getTemplate();

		if (!$template instanceof Template) {
			throw new UnexpectedValueException();
		}

		$template->setTranslator($this->translator ? $this->translator : new FallbackTranslator());
		$template->setFile($this->getTemplateFile());
		$template->pageTitle = $this->pageTitle;
		$template->pageName = $this->pageName;
		$template->pageMsg = $this->pageMsg;
		$template->usernameIcon = $this->usernameIcon;
		$template->passwordIcon = $this->passwordIcon;

		foreach ($options as $key => $value) {
			$template->$key = $value;
		}
		$template->render();
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


	public function setPageTitle(string $pageTitle): self
	{
		$this->pageTitle = $pageTitle;
		return $this;
	}


	public function getPageName(): ?string
	{
		return $this->pageName;
	}


	public function setPageName(string $pageName): self
	{
		$this->pageName = $pageName;
		return $this;
	}


	public function getPageMsg(): ?string
	{
		return $this->pageMsg;
	}


	public function setPageMsg(string $pageMsg): self
	{
		$this->pageMsg = $pageMsg;
		return $this;
	}


	public function getUsernameIcon(): ?string
	{
		return $this->usernameIcon;
	}


	public function setUsernameIcon(string $usernameIcon): self
	{
		$this->usernameIcon = $usernameIcon;
		return $this;
	}


	public function getPasswordIcon(): ?string
	{
		return $this->passwordIcon;
	}


	public function setPasswordIcon(string $passwordIcon): self
	{
		$this->passwordIcon = $passwordIcon;
		return $this;
	}
}
