<?php

namespace Kollarovic\Admin\Icon;


class DefaultIconResolver implements IconResolver
{
	private const DEFAULT_ICON_NAME = 'default';

	/** @var array<string, array<string, string>> */
	private array $icons = [];


	public function addIcon(string $type, string $name, string $icon): static
	{
		$this->icons[$type][$name] = $icon;
		return $this;
	}


	public function resolveIcon(string $type, ?string $name): string
	{
		$name = $name ?? self::DEFAULT_ICON_NAME;
		return $this->icons[$type][$name] ?? $name;
	}
}
