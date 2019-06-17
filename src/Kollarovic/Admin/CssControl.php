<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Application\UI\Control;
use Nette\Utils\Html;


class CssControl extends Control
{
	/** @var array */
	private $files;


	public function __construct(array $files)
	{
		$this->files = $files;
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
