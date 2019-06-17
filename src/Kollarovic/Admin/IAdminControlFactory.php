<?php

declare(strict_types=1);

namespace Kollarovic\Admin;


interface IAdminControlFactory
{
	function create(): AdminControl;
}
