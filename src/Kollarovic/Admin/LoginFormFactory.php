<?php

namespace Kollarovic\Admin;

use Nette\Object;
use Nette\Security\User;
use Nette\Security\AuthenticationException;
use Kollarovic\Admin\Form\IBaseFormFactory;
use Nette\Application\UI\Form;


class LoginFormFactory extends Object implements ILoginFormFactory
{

	/** @var User */
	private $user;

	/** @var string */
	private $username;

	/** @var IBaseFormFactory */
	private $baseFormFactory;


	function __construct(User $user, $username = 'email', IBaseFormFactory $baseFormFactory)
	{
		$this->user = $user;
		$this->username = $username;
		$this->baseFormFactory = $baseFormFactory;
	}


	public function create()
	{
		$form = $this->baseFormFactory->create();

		if ($this->username == 'email') {
			$form->addText('username', 'Email')
				->setAttribute('placeholder', 'Email')
				->setRequired('Please enter your email.')
				->addRule(Form::EMAIL, 'Please enter a valid email address.');
		} else {
			$form->addText('username', 'Username')
				->setAttribute('placeholder', 'Username')
				->setRequired('Please enter your username.');
		}

		$form->addPassword('password', 'Password')
			->setAttribute('placeholder', 'Password')
			->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Remember Me');
		$form->addSubmit('submit', 'Sign In');
		$form->onSuccess[] = $this->process;
		return $form;
	}


	public function process(Form $form)
	{
		$values = $form->values;
		try {
			if ($values->remember) {
				$this->user->setExpiration('14 days', FALSE);
			} else {
				$this->user->setExpiration(0, TRUE);
			}			
			$this->user->login($values->username, $values->password);

		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}

}