<?php

declare(strict_types=1);

namespace Kollarovic\Admin;


interface ILoginControlFactory
{
	public function create(): LoginControl;
}
