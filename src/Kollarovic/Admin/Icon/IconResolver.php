<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Icon;

interface IconResolver
{
	function resolveIcon(string $type, ?string $name): string;
}