<?php

namespace Kollarovic\Admin;

use WebLoader\Nette\CssLoader;
use WebLoader\Nette\JavaScriptLoader;


interface ILoaderFactory
{

	/**
	 * @return CssLoader
	 */
	function createCssLoader();


	/**
	 * @return JavaScriptLoader
	 */
	function createJavaScriptLoader();

}