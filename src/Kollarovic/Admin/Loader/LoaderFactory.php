<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Loader;

use Nette\Application\UI\Control;


interface LoaderFactory
{
	function createCssLoader(string $name): Control;

	function createJavaScriptLoader(string $name): Control;
}
