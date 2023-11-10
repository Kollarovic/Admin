<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Localization\ITranslator;
use Nette\UnexpectedValueException;


class LoginControl extends Control
{
	/** @var array */
	public $onLoggedIn;

	/** @var string */
	private $templateFile;

	/** @var ILoginFormFactory */
	private $loginFormFactory;

	/** @var ILoaderFactory */
	private $loaderFactory;

	/** @var ITranslator|null */
	private $translator;

	/** @var string */
	private $pageTitle;

	/** @var string */
	private $pageName;

	/** @var string */
	private $pageMsg;

	/** @var string */
	private $usernameIcon;

	/** @var string */
	private $passwordIcon;


	public function __construct(
		ILoginFormFactory $loginFormFactory,
		ILoaderFactory $loaderFactory,
		ITranslator $translator = null
	) {
		$this->loginFormFactory = $loginFormFactory;
		$this->loaderFactory = $loaderFactory;
		$this->translator = $translator;
		$this->templateFile = __DIR__ . '/templates/LoginControl.latte';
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


	public function getPageName(): string
	{
		return $this->pageName;
	}


	public function setPageName(string $pageName): self
	{
		$this->pageName = $pageName;
		return $this;
	}


	public function getPageMsg(): string
	{
		return $this->pageMsg;
	}


	public function setPageMsg(string $pageMsg): self
	{
		$this->pageMsg = $pageMsg;
		return $this;
	}


	public function getUsernameIcon(): string
	{
		return $this->usernameIcon;
	}


	public function setUsernameIcon(string $usernameIcon): self
	{
		$this->usernameIcon = $usernameIcon;
		return $this;
	}


	public function getPasswordIcon(): string
	{
		return $this->passwordIcon;
	}


	public function setPasswordIcon(string $passwordIcon): self
	{
		$this->passwordIcon = $passwordIcon;
		return $this;
	}
}
