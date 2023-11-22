<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Form;

use Nette\Application\UI\Form;
use Nette\Forms\FormRenderer;
use Nette\Localization\Translator;


class BaseFormFactory implements IBaseFormFactory
{
	/** @var FormRenderer */
	private FormRenderer $formRender;

	/** @var ?Translator */
	private ?Translator $translator;


	public function __construct(FormRenderer $formRender, ?Translator $translator = null)
	{
		$this->translator = $translator;
		$this->formRender = $formRender;
	}


	public function create(): Form
	{
		$form = new Form;
		$form->setTranslator($this->translator);
		$form->setRenderer($this->formRender);
		return $form;
	}
}
