<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Application\UI\Control;


class LoaderFactory implements ILoaderFactory
{
	/** @var array */
	private $files = [];


	public function addFile(string $file)
	{
		$this->files[$file] = $file;
		return $this;
	}


	public function removeFile(string $file): self
	{
		unset($this->files[$file]);
		return $this;
	}


	public function getFiles(): array
	{
		return $this->files;
	}


	public function createCssLoader(): Control
	{
		$files = array_filter($this->files, [$this, 'isCss']);
		return new CssControl($files);
	}


	public function createJavaScriptLoader(): Control
	{
		$files = array_filter($this->files, [$this, 'isJs']);
		return new JsControl($files);
	}


	private function isCss(string $file): bool
	{
		return (bool) preg_match('~\.css$~', $file);
	}


	private function isJs(string $file): bool
	{
		return (bool) preg_match('~\.js$~', $file);
	}
}
