<?php
declare(strict_types=1);


if (!$autoload = @include __DIR__ . '/../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

$autoload->addPsr4('Kollarovic\Admin\Test\\', __DIR__ . '/Admin');


Tester\Environment::setup();

define('TEMP_DIR', __DIR__ . '/temp');


function run(Tester\TestCase $testCase)
{
	$testCase->run();
}
