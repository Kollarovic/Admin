<?php

namespace Kollarovic\Admin\Form;

use Nette\Application\UI\Form;


interface IBaseFormFactory
{

	/**
	 * @return Form
	 */
	public function create();

}
