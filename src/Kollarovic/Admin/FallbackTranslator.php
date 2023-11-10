<?php

declare(strict_types=1);

namespace Kollarovic\Admin;

use Nette\Localization\Translator;

class FallbackTranslator implements Translator
{
    function translate(\Stringable|string $message, ...$parameters): string|\Stringable
    {
        return $message;
    }
}