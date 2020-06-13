<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Romulodl\Wae;

final class WaeTest extends TestCase
{
	public function testCalculateWithWrongTypeValues(): void
	{
		$this->expectException(Exception::class);

		$obj = new Wae();
		$obj->calculate(['poop']);
	}

	public function testCalculateWithEmptyValues(): void
	{
		$this->expectException(Exception::class);

		$obj = new Wae();
		$obj->calculate([]);
	}

	public function testCalculateWithValidValues(): void
	{
		$values = require(__DIR__ . '/values.php');

		$obj = new Wae();
		$wae = $obj->calculate($values);

		$this->assertSame(-1698.63, round($wae[0], 2));
		$this->assertSame(1563.56, round($wae[1], 2));
		$this->assertSame(1887.67, round($wae[2], 2));
	}
}
