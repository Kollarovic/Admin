<?php

declare(strict_types=1);

namespace Kollarovic\Admin\DI;

use Kollarovic\Admin\AdminItemsFactory;
use Kollarovic\Admin\Form\BaseFormFactory;
use Kollarovic\Admin\Form\DefaultLoginFormFactory;
use Kollarovic\Admin\AdminControlFactory;
use Kollarovic\Admin\Icon\DefaultIconResolver;
use Kollarovic\Admin\LoginControlFactory;
use Kollarovic\Admin\Loader\DefaultLoaderFactory;
use Kollarovic\Admin\TemplateType;
use Nette\DI\CompilerExtension;
use Nette\Neon\Exception;
use Nextras\FormsRendering\Renderers\Bs3FormRenderer;
use Nette\Neon\Neon;


class Extension extends CompilerExtension
{

	/**
	 * @return array<string, mixed>
	 * @throws Exception
	 */
	private function getDefaultConfig(): array
	{
		return Neon::decodeFile(__DIR__ . '/admin.neon')['admin'];
	}


	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->getDefaultConfig());
		$templateType = $config['templateType'];
		$templateType = is_string($templateType) ? TemplateType::from($templateType) : $templateType;

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

		$iconResolverDefinition = $builder->addDefinition($this->prefix('iconResolver'))
			->setFactory(DefaultIconResolver::class);

		foreach ($config['icons'] as $type => $icons) {
			foreach ($icons as $name => $icon) {
				$iconResolverDefinition->addSetup('addIcon', [$type, $name, $icon]);
			}
		}

		$builder->addDefinition($this->prefix('itemsFactory'))
			->setFactory(AdminItemsFactory::class)
			->setAutowired(false)
			->addSetup('setTemplateType', [$templateType]);

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
			->addSetup('setTemplateType', [$templateType])
			->addSetup('setPageTitle', [$config['login']['pageTitle']])
			->addSetup('setPageName', [$config['login']['pageName']])
			->addSetup('setPageMsg', [$config['login']['pageMsg']]);

		$builder->addFactoryDefinition($this->prefix('adminControlFactory'))
			->setImplement(AdminControlFactory::class)
			->getResultDefinition()
			->setArgument('itemsFactory', $this->prefix('@itemsFactory'))
			->addSetup('setTemplateType', [$templateType])
			->addSetup('setSkin', [$config['skin']])
			->addSetup('setAdminName', [$config['name']])
			->addSetup('setAdminShortName', [$config['shortName']])
			->addSetup('setNavigationName', [$config['navigation']])
			->addSetup('setHeader', [$config['header']])
			->addSetup('setFooter', [$config['footer']])
			->addSetup('setAjaxRequest', [$config['ajax']]);
	}
}
