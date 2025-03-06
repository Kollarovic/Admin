<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Kollarovic\Navigation\Item;
use Latte\Runtime\Html;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Security\User;


class AdminTemplate extends Template
{
	public Presenter $presenter;
	public User $user;
	public string $basePath;
	public string $adminName;
	public string $adminShortName;
	public ?string $pageName;
	public ?string $pageTitle;
	public string $skin;
	public string $userName;
	public ?string $userImage;
	public ?string $profileUrl;
	public bool $ajax;
	public Item $rootItem;
	public bool $showSearchForm;
	public bool $sidebarCollapse;
	public null|string|Html $content;
	public null|string|Html $header;
	public null|string|Html $footer;
	public null|string|Html $navbar;
}