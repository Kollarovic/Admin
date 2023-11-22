<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Application\UI\Control;
use Nette\Utils\Html;


class JsControl extends Control
{
    /** @var array<string, string> */
    private array $files;


    /**
     * @param array<string, string> $files
     */
	public function __construct(array $files)
	{
		$this->files = $files;
	}


	public function render(): void
	{
		foreach ($this->files as $file) {
			echo Html::el('script')
				->type('text/javascript')
				->src($file);
		}
	}
}
