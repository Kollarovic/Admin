<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Kollarovic\Admin\Icon\IconResolver;
use Kollarovic\Navigation\Item;
use Kollarovic\Navigation\ItemsFactory;


class AdminItemsFactory implements ItemsFactory
{
	/** @var array<Item> */
	private array $items = [];
	private TemplateType $templateType = TemplateType::DEFAULT;


	public function __construct(
		private readonly ItemsFactory $itemsFactory,
		private readonly IconResolver $iconResolver
	) {
	}


	public function setTemplateType(TemplateType $templateType): void
	{
		$this->templateType = $templateType;
	}


	public function create(string $name): Item
	{
		if (isset($this->items[$name])) {
			return $this->items[$name];
		}

		$rootItem = $this->itemsFactory->create($name);
		$this->applyIconsRecursively($rootItem);
		$this->items[$name] = $rootItem;
		return $rootItem;
	}


	private function applyIconsRecursively(Item $item): void
	{
		$this->applyResolvedIcon($item);
		foreach ($item->getItems(true) as $childItem) {
			$this->applyResolvedIcon($childItem);
		}
	}


	private function applyResolvedIcon(Item $item): void
	{
		$resolvedIcon = $this->iconResolver->resolveIcon($this->templateType->value, $item->getIcon());
		$item->setIcon($resolvedIcon);
	}

}
