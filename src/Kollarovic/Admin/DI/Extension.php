<?php

namespace Kollarovic\Admin\DI;

use Nette;


class Extension extends Nette\DI\CompilerExtension
{


	private function getDefaultConfig()
	{
		$dir = dirname(__DIR__) . '/assets';
		return [
			'wwwDir' => '%wwwDir%',
			'name' => 'Admin',
			'skin' => 'red',
			'footer' => '',
			'ajax' => FALSE,
			'defaultFiles' => [
				'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
				'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
				"$dir/AdminLTE.min.css",
				"$dir/_all-skins.min.css",
				"$dir/admin.css",
				'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js',
				'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js',
				"$dir/app.min.js",
				"$dir/netteForms.js",
			],

			'files' => [],
			'navigation' => 'admin',
			'login' => [
				'pageTitle' => 'Login - Admin',
				'pageName' => 'Admin',
				'pageMsg' => 'Authentication',
				'usernameIcon' => 'envelope',
				'passwordIcon' => 'lock',
				'usernameType' => 'email',
			],
		];
	}


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->getDefaultConfig());
		$builder = $this->getContainerBuilder();

		$loaderFactory = $builder->addDefinition($this->prefix('loaderFactory'))
			->setClass('Kollarovic\Admin\LoaderFactory', ['wwwDir' => $config['wwwDir']]);

		foreach (array_merge($config['defaultFiles'], $config['files']) as $file) {
			$loaderFactory->addSetup('addFile', [$file]);
		}

		$builder->addDefinition($this->prefix('formRender'))
			->setClass('Nextras\Forms\Rendering\Bs3FormRenderer')
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('baseFormFactory'))
			->setClass('Kollarovic\Admin\Form\BaseFormFactory', [
				'formRender' => $this->prefix('@formRender')
			]);

		$builder->addDefinition($this->prefix('loginFormFactory'))
			->setClass('Kollarovic\Admin\LoginFormFactory', [
				'username' => $config['login']['usernameType']
			]);

		$builder->addDefinition($this->prefix('loginControlFactory'))
			->setImplement('Kollarovic\Admin\ILoginControlFactory')
			->addSetup('setPageTitle', [$config['login']['pageTitle']])
			->addSetup('setPageName', [$config['login']['pageName']])
			->addSetup('setPageMsg', [$config['login']['pageMsg']])
			->addSetup('setUsernameIcon', [$config['login']['usernameIcon']])
			->addSetup('setPasswordIcon', [$config['login']['passwordIcon']]);

		$builder->addDefinition($this->prefix('adminControlFactory'))
			->setImplement('Kollarovic\Admin\IAdminControlFactory')
			->addSetup('setSkin', [$config['skin']])
			->addSetup('setAdminName', [$config['name']])
			->addSetup('setNavigationName', [$config['navigation']])
			->addSetup('setFooter', [$config['footer']])
			->addSetup('setAjaxRequest', [$config['ajax']]);
	}

}
