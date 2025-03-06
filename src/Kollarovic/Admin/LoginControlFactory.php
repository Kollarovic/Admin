<?php

declare(strict_types=1);

namespace Kollarovic\Admin;


interface LoginControlFactory
{
	public function create(): LoginControl;
}
