<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Loader;

use Nette\Application\UI\Control;
use Nette\Utils\Html;


class CssControl extends Control
{
	/**
	 * @param array<string, string> $files
	 */
	public function __construct(private array $files)
	{
	}


	public function render(): void
	{
		foreach ($this->files as $file) {
			echo Html::el('link')
				->rel('stylesheet')
				->href($file);
		}
	}
}
