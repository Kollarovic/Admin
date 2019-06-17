<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Application\UI\Control;


interface ILoaderFactory
{
	function createCssLoader(): Control;

	function createJavaScriptLoader(): Control;
}
