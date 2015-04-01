<?php

namespace Kollarovic\Admin;


interface ILoginControlFactory
{

	/**
	 * @return LoginControl
	 */
	function create();

}