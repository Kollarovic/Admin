<?php

declare(strict_types=1);

namespace Kollarovic\Admin\Form;

use Nette\Application\UI\Form;
use Nette\Forms\IFormRenderer;
use Nette\Localization\ITranslator;


class BaseFormFactory implements IBaseFormFactory
{
	/** @var IFormRenderer */
	private $formRender;

	/** @var ITranslator|null */
	private $translator;


	public function __construct(IFormRenderer $formRender, ITranslator $translator = null)
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
