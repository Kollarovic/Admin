<?php
declare(strict_types=1);

namespace Kollarovic\Admin\Test;

use Nette\Application\Request;
use Nette\Configurator;


abstract class TestCase extends \Tester\TestCase
{
	protected function createContainer()
	{
		$configurator = new Configurator();
		$configurator->setDebugMode(false);
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/../config.neon');
		return $configurator->createContainer();
	}


	protected function createPresenter()
	{
		$container = $this->createContainer();
		$presenter = new MockPresenter();
		$presenter->autoCanonicalize = false;
		$container->callInjects($presenter);

		$request = new Request('Mock', 'GET', ['action' => 'default']);
		$presenter->run($request);
		return $presenter;
	}
}
