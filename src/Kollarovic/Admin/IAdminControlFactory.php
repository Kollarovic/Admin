<?php

namespace Kollarovic\Admin;


interface IAdminControlFactory
{

	/**
	 * @return AdminControl
	 */
	function create();

}