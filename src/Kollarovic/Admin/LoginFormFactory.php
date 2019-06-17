<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Kollarovic\Admin\Form\IBaseFormFactory;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;


class LoginFormFactory implements ILoginFormFactory
{
	/** @var User */
	private $user;

	/** @var bool */
	private $useEmail;

	/** @var IBaseFormFactory */
	private $baseFormFactory;


	public function __construct(User $user, IBaseFormFactory $baseFormFactory, bool $useEmail)
	{
		$this->user = $user;
		$this->baseFormFactory = $baseFormFactory;
		$this->useEmail = $useEmail;
	}


	public function create(): Form
	{
		$form = $this->baseFormFactory->create();

		if ($this->useEmail) {
			$form->addText('username', 'Email')
				->setHtmlAttribute('placeholder', 'Email')
				->setRequired('Please enter your email.')
				->addRule(Form::EMAIL, 'Please enter a valid email address.');
		} else {
			$form->addText('username', 'Username')
				->setHtmlAttribute('placeholder', 'Username')
				->setRequired('Please enter your username.');
		}

		$form->addPassword('password', 'Password')
			->setHtmlAttribute('placeholder', 'Password')
			->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Remember Me');
		$form->addSubmit('submit', 'Sign In');
		$form->onSuccess[] = [$this, 'process'];
		return $form;
	}


	public function process(Nette\Forms\Form $form): void
	{
		$values = $form->values;
		try {
			if ($values->remember) {
				$this->user->setExpiration('14 days');
			} else {
				$this->user->setExpiration(null);
			}
			$this->user->login($values->username, $values->password);

		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}
}
