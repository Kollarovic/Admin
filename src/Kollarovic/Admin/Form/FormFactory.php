<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Form;

use Nette\Application\UI\Form;


interface FormFactory
{
	public function create(): Form;
}
