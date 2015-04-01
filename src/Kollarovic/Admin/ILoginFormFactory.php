<?php

namespace Kollarovic\Admin;

use Nette\Application\UI\Form;


interface ILoginFormFactory
{

	/**
	 * @return Form
	 */
	function create();

}