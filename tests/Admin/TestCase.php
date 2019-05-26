<?

namespace Kollarovic\Admin\Test;

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
        $container->callInjects($presenter);
        return $presenter;
    }

}
