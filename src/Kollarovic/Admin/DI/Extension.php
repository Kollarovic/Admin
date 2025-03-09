<?php

declare(strict_types=1);

namespace Kollarovic\Admin\DI;

use Kollarovic\Admin\Form\BaseFormFactory;
use Kollarovic\Admin\Form\DefaultLoginFormFactory;
use Kollarovic\Admin\AdminControlFactory;
use Kollarovic\Admin\LoginControlFactory;
use Kollarovic\Admin\Loader\DefaultLoaderFactory;
use Nette;
use Nette\DI\ContainerBuilder;
use Nextras\FormsRendering\Renderers\Bs3FormRenderer;


class Extension extends Nette\DI\CompilerExtension
{

	/**
	 * @return array<string, mixed>
	 */
	private function getDefaultConfig(ContainerBuilder $builder): array
	{
		$adminLte2Files = [
			'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
			'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
			'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.10/css/AdminLTE.min.css',
			'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.10/css/skins/_all-skins.min.css',

			'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js',
			'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
			'https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.10/js/adminlte.min.js',
		];

		$adminLte3Files = [
			'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback',
			'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css',
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
			'https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css',
			'https://cdn.jsdelivr.net/npm/admin-lte@3.2/plugins/icheck-bootstrap/icheck-bootstrap.min.css',

			'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js',
			'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js',
			'https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js',
		];

		return [
			'name' => 'Admin',
			'shortName' => 'A',
			'templateType' => 'AdminLte2',
			'skin' => 'red',
			'header' => '',
			'footer' => '',
			'ajax' => false,
			'defaultFiles' => [
				'AdminLte2' => $adminLte2Files,
				'AdminLte3' => $adminLte3Files,
			],
			'files' => [],
			'navigation' => 'admin',
			'login' => [
				'pageTitle' => 'Login - Admin',
				'pageName' => 'Admin',
				'pageMsg' => 'Authentication',
				'email' => true,
			],
		];
	}


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->getDefaultConfig($builder));

		$loaderFactory = $builder->addDefinition($this->prefix('loaderFactory'))
			->setFactory(DefaultLoaderFactory::class);

		foreach (array_merge($config['defaultFiles'], $config['files']) as $name => $value) {
			if (is_array($value)) {
				foreach ($value as $file) {
					$loaderFactory->addSetup('addFile', [$name, $file]);
				}
			} else {
				$loaderFactory->addSetup('addFile', [null, $value]);
			}
		}

		$builder->addDefinition($this->prefix('formRenderer'))
			->setFactory(Bs3FormRenderer::class)
			->setAutowired(false);

		$builder->addDefinition($this->prefix('baseFormFactory'))
			->setFactory(BaseFormFactory::class, [
				'formRenderer' => $this->prefix('@formRenderer'),
			]);

		$builder->addDefinition($this->prefix('loginFormFactory'))
			->setFactory(DefaultLoginFormFactory::class, ['useEmail' => $config['login']['email']]);

		$builder->addFactoryDefinition($this->prefix('loginControlFactory'))
			->setImplement(LoginControlFactory::class)
			->getResultDefinition()
			->addSetup('setTemplateType', [$config['templateType']])
			->addSetup('setPageTitle', [$config['login']['pageTitle']])
			->addSetup('setPageName', [$config['login']['pageName']])
			->addSetup('setPageMsg', [$config['login']['pageMsg']]);

		$builder->addFactoryDefinition($this->prefix('adminControlFactory'))
			->setImplement(AdminControlFactory::class)
			->getResultDefinition()
			->addSetup('setTemplateType', [$config['templateType']])
			->addSetup('setSkin', [$config['skin']])
			->addSetup('setAdminName', [$config['name']])
			->addSetup('setAdminShortName', [$config['shortName']])
			->addSetup('setNavigationName', [$config['navigation']])
			->addSetup('setHeader', [$config['header']])
			->addSetup('setFooter', [$config['footer']])
			->addSetup('setAjaxRequest', [$config['ajax']]);
	}
}
