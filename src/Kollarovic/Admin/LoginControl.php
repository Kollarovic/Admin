<?php

namespace Kollarovic\Admin;

use Nette\Application\UI\Control;


/**
 * @method LoginControl setTemplateFile(string $templateFile)
 * @method LoginControl setPageTitle(string $pageTitle)
 * @method LoginControl setPageName(string $pageName)
 * @method LoginControl setPageMsg(string $pageMsg)
 * @method LoginControl setUsernameIcon(string $usernameIcon)
 * @method LoginControl setPasswordIcon(string $passwordIcon)
 *
 * @method string getTemplateFile()
 * @method string getPageTitle()
 * @method string getPageName()
 * @method string getPageMsg()
 * @method string getUsernameIcon()
 * @method string getPasswordIcon()
 */
class LoginControl extends Control
{

	/** @var array */
	public $onLoginIn;

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
			$this->onLoginIn($form);
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