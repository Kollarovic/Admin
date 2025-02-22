<?php
declare(strict_types=1);

namespace Kollarovic\Admin\Test;

use Tester\Assert;
use Tester\DomQuery;

require_once __DIR__ . '/../bootstrap.php';


class LoginControlTest extends TestCase
{
	public function testRender()
	{
		$presenter = $this->createPresenter();
		$control = $presenter['loginControl'];
		ob_start();
		$control->render();
		$html = ob_get_clean();

		$dom = DomQuery::fromHtml($html);
		Assert::true($dom->has('body'));
		Assert::true($dom->has('form'));
	}
}


\run(new LoginControlTest());
