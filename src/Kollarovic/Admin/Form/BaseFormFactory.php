<?php

namespace Kollarovic\Admin\Form;

use Nette\Object;
use Nette\Forms\IFormRenderer;
use Nette\Localization\ITranslator;
use Nette\Application\UI\Form;


class BaseFormFactory extends Object implements IBaseFormFactory
{

	/** @var IFormRenderer */
	private $formRender;

	/** @var ITranslator */
	private $translator;


	function __construct(IFormRenderer $formRender, ITranslator $translator= NULL)
	{
		$this->translator = $translator;
		$this->formRender = $formRender;
	}


	public function create()
	{
		$form = new Form;
		$form->setTranslator($this->translator);
		$form->setRenderer($this->formRender);
		return $form;
	}

}