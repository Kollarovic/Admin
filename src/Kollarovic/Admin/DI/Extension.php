<?php

declare(strict_types=1);

namespace Kollarovic\Admin\DI;

use Kollarovic\Admin\Form\BaseFormFactory;
use Kollarovic\Admin\IAdminControlFactory;
use Kollarovic\Admin\ILoginControlFactory;
use Kollarovic\Admin\LoaderFactory;
use Kollarovic\Admin\LoginFormFactory;
use Nette;
use Nextras\FormsRendering\Renderers\Bs3FormRenderer;


class Extension extends Nette\DI\CompilerExtension
{
	private function getDefaultConfig($builder)
	{
		$dir = dirname(__DIR__) . '/assets';
		return [
			'wwwDir' => $builder->parameters['wwwDir'],
			'name' => 'Admin',
			'shortName' => 'A',
			'skin' => 'red',
			'footer' => '',
			'ajax' => false,
			'defaultFiles' => [
				'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
				'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
				'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.10/css/AdminLTE.min.css',
				'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.10/css/skins/_all-skins.min.css',

				'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js',
				'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
				'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.10/js/adminlte.min.js',
			],

			'files' => [],
			'navigation' => 'admin',
			'login' => [
				'pageTitle' => 'Login - Admin',
				'pageName' => 'Admin',
				'pageMsg' => 'Authentication',
				'usernameIcon' => null,
				'passwordIcon' => 'lock',
				'email' => true,
			],
		];
	}


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->getDefaultConfig($builder));
		if (empty($config['login']['usernameIcon'])) {
			$config['login']['usernameIcon'] = $config['login']['email'] ? 'envelope' : 'user';
		}

		$loaderFactory = $builder->addDefinition($this->prefix('loaderFactory'))
			->setFactory(LoaderFactory::class);

		foreach (array_merge($config['defaultFiles'], $config['files']) as $file) {
			$loaderFactory->addSetup('addFile', [$file]);
		}

		$builder->addDefinition($this->prefix('formRender'))
			->setFactory(Bs3FormRenderer::class)
			->setAutowired(false);

		$builder->addDefinition($this->prefix('baseFormFactory'))
			->setFactory(BaseFormFactory::class, [
				'formRender' => $this->prefix('@formRender'),
			]);

		$builder->addDefinition($this->prefix('loginFormFactory'))
			->setFactory(LoginFormFactory::class, ['useEmail' => $config['login']['email']]);

		$builder->addFactoryDefinition($this->prefix('loginControlFactory'))
			->setImplement(ILoginControlFactory::class)
			->getResultDefinition()
			->addSetup('setPageTitle', [$config['login']['pageTitle']])
			->addSetup('setPageName', [$config['login']['pageName']])
			->addSetup('setPageMsg', [$config['login']['pageMsg']])
			->addSetup('setUsernameIcon', [$config['login']['usernameIcon']])
			->addSetup('setPasswordIcon', [$config['login']['passwordIcon']]);

		$builder->addFactoryDefinition($this->prefix('adminControlFactory'))
			->setImplement(IAdminControlFactory::class)
			->getResultDefinition()
			->addSetup('setSkin', [$config['skin']])
			->addSetup('setAdminName', [$config['name']])
			->addSetup('setAdminShortName', [$config['shortName']])
			->addSetup('setNavigationName', [$config['navigation']])
			->addSetup('setFooter', [$config['footer']])
			->addSetup('setAjaxRequest', [$config['ajax']]);
	}
}
