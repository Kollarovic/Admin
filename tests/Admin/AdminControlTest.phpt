<?php
declare(strict_types=1);

namespace Kollarovic\Admin\Test;

use Tester\Assert;
use Tester\DomQuery;

require_once __DIR__ . '/../bootstrap.php';


class AdminControlTest extends TestCase
{
	public function testRender()
	{
		$presenter = $this->createPresenter();
		$control = $presenter['adminControl'];
		ob_start();
		$control->render();
		$html = ob_get_clean();

		$dom = DomQuery::fromHtml($html);
		Assert::true($dom->has('html'));
		Assert::true($dom->has('footer'));
		Assert::contains('Mario Kollarovic', $html);
	}
}


\run(new AdminControlTest());
