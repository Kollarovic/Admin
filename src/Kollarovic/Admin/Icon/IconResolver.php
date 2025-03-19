<?php

namespace Kollarovic\Admin\Icon;

interface IconResolver
{
	function resolveIcon(string $type, ?string $name): string;
}