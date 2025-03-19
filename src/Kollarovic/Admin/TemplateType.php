<?php

declare(strict_types=1);

namespace Kollarovic\Admin;


enum TemplateType: string
{
	public const DEFAULT = self::AdminLte2;
	case AdminLte2 = 'AdminLte2';
	case AdminLte3 = 'AdminLte3';
}
