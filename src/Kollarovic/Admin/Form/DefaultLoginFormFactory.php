<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Form;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Nette\Utils\ArrayHash;


class DefaultLoginFormFactory implements LoginFormFactory
{
	public function __construct(
		private User        $user,
		private FormFactory $baseFormFactory,
		private bool        $useEmail
	) {
	}


	public function create(): Form
	{
		$form = $this->baseFormFactory->create();

		if ($this->useEmail) {
			$form->addEmail('username', 'Email')
				->setHtmlAttribute('placeholder', 'Email')
				->setRequired('Please enter your email.');
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
		$form->onSuccess[] = function (Form $form, ArrayHash $values): void {
			$this->process($form, $values);
		};
		return $form;
	}


	private function process(Form $form, ArrayHash $values): void
	{
		try {
			$this->user->setExpiration($values->remember ? '14 days' : null);
			$this->user->login($values->username, $values->password);
		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}
}
