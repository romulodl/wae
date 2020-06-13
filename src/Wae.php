<?php

namespace Romulodl;

class Wae
{
	private $macd;
	private $bb;
	private $atr;

	public function __construct($macd = null, $bb = null, $atr = null) {
		$this->macd = $macd ?: new Macd();
		$this->bb = $bb ?: new BollingerBands();
		$this->atr = $atr ?: new Atr();
	}

	/**
	 * Calculate Waddah Attar Explosion values
	 *
	 * params:
	 *  - hlc values [high, low, close] (all float)
	 *  - sensitivity
	 *  - fast_ema
	 *  - slow_ema
	 *  - bollinger bands channel
	 *  - bollinger bands standard deviation
	 *
	 * return array:
	 * [
	 *   trend bar (positive -> green) (negative -> red), MACD result
	 *   explosion line, BB result
	 *   dead zone line, ATR * 3.7
	 * ]
	 */
	public function calculate(
		array $hlc_values,
		int $sensitivity = 150,
		int $fast_ema = 20,
		int $slow_ema = 40,
		int $bb_channel = 20,
		int $bb_stdev = 2
	) : array
	{
		if (empty($hlc_values)) {
			throw new \Exception('[' . __METHOD__ . '] $values parameters is invalid');
		}

		$close_values = [];
		foreach ($hlc_values as $v) {
			$close_values[] = $v[2];
		}

		$macd = [
			$this->macd->calculate($close_values, $fast_ema, $slow_ema),
			$this->macd->calculate(array_slice($close_values, 0, -1), $fast_ema, $slow_ema),
		];

		$bb = $this->bb->calculate(array_slice($close_values, -1 * $bb_channel), $bb_stdev);

		return [
			($macd[0] - $macd[1]) * $sensitivity,
			$bb[1] - $bb[2],
			$this->atr->calculate($hlc_values) * 3.7
		];
	}
}
