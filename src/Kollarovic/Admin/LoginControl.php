<?php

namespace Kollarovic\Admin;

use Nette\Application\UI\Control;


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


	function __construct(ILoginFormFactory $loginFormFactory, ILoaderFactory $loaderFactory)
	{
		$this->loginFormFactory = $loginFormFactory;
		$this->loaderFactory = $loaderFactory;
		$this->templateFile = __DIR__ . '/templates/LoginControl.latte';
	}


	public function getPageTitle()
	{
		return $this->pageTitle;
	}


	public function setPageTitle($pageTitle)
	{
		$this->pageTitle = $pageTitle;
		return $this;
	}


	public function getPageName()
	{
		return $this->pageName;
	}


	public function setPageName($pageName)
	{
		$this->pageName = $pageName;
		return $this;
	}


	public function getPageMsg()
	{
		return $this->pageMsg;
	}


	public function setPageMsg($pageMsg)
	{
		$this->pageMsg = $pageMsg;
		return $this;
	}


	public function getUsernameIcon()
	{
		return $this->usernameIcon;
	}


	public function setUsernameIcon($usernameIcon)
	{
		$this->usernameIcon = $usernameIcon;
		return $this;
	}


	public function getPasswordIcon()
	{
		return $this->passwordIcon;
	}


	public function setPasswordIcon($passwordIcon)
	{
		$this->passwordIcon = $passwordIcon;
		return $this;
	}


	public function getTemplateFile()
	{
		return $this->templateFile;
	}


	public function setTemplateFile($templateFile)
	{
		$this->templateFile = $templateFile;
		return $this;
	}


	public function render(array $options = [])
	{
		$this->template->setFile($this->getTemplateFile());
		$this->template->pageTitle = $this->pageTitle;
		$this->template->pageName = $this->pageName;
		$this->template->pageMsg = $this->pageMsg;
		$this->template->usernameIcon = $this->usernameIcon;
		$this->template->passwordIcon = $this->passwordIcon;
		foreach ($options as $key => $value) {
			$this->template->$key = $value;
		}
		$this->template->render();
	}


	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		if (!array_key_exists('translate', $template->getLatte()->getFilters())) {
			$template->addFilter('translate', function($str){return $str;});
		}
		return $template;
	}


	protected function createComponentForm()
	{
		$form = $this->loginFormFactory->create();
		$form->onSuccess[] = function($form) {
			$this->onLoggedIn($form);
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

}