<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Form;

use Nette\Application\UI\Form;
use Nette\Forms\FormRenderer;
use Nette\Localization\Translator;


class BaseFormFactory implements FormFactory
{
	public function __construct(
		private FormRenderer $formRenderer,
		private ?Translator  $translator = null
	) {
	}


	public function create(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);
		$form->setRenderer($this->formRenderer);
		return $form;
	}
}
