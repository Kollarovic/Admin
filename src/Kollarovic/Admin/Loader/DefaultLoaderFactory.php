<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Loader;

use Nette\Application\UI\Control;


class DefaultLoaderFactory implements LoaderFactory
{
	/** @var array<string, array<string, string>> */
	private array $files = [];


	public function addFile(?string $name, string $file): static
	{
		$this->files[$name][$file] = $file;
		return $this;
	}


	public function removeFile(?string $name, string $file): static
	{
		unset($this->files[$name][$file]);
		return $this;
	}


	/**
	 * @return array<string, string>
	 */
	public function getFiles(string $name): array
	{
		$globalFiles = $this->files[null] ?? [];
		$specificFiles = $this->files[$name] ?? [];
		return $globalFiles + $specificFiles;
	}


	public function createCssLoader(string $name): Control
	{
		$files = array_filter($this->getFiles($name), [$this, 'isCss']);
		return new CssControl($files);
	}


	public function createJavaScriptLoader(string $name): Control
	{
		$files = array_filter($this->getFiles($name), [$this, 'isJs']);
		return new JsControl($files);
	}


	private function isCss(string $file): bool
	{
		return (bool) preg_match('~\.css$|/css\?~', $file);
	}


	private function isJs(string $file): bool
	{
		return (bool) preg_match('~\.js$~', $file);
	}
}
