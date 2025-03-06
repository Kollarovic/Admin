<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Form;

use Nette\Application\UI\Form;


interface LoginFormFactory
{
	public function create(): Form;
}
