<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Bridges\ApplicationLatte\Template;


class LoginTemplate extends Template
{
	public string $pageName;
	public string $pageTitle;
	public string $pageMsg;
	public string $basePath;
}