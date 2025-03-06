<?php

declare(strict_types=1);

namespace Kollarovic\Admin;


interface AdminControlFactory
{
	function create(): AdminControl;
}
