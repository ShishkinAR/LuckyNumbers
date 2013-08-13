<?php

class Number_Model_Numbers {

	/** @var int[] Кеш по количеству */
	protected $cacheCount = array();
	/** @var string[] Кеш по значению */
	protected $cacheValue = array();

	/**
	 * Прокси-метод для получения количества вариаций для указанных параметров через кеш
	 * @param int $j
	 * @param int $n
	 * @param int $m
	 * @return int
	 */
	protected function decompositionCountProxy($j, $n, $m) {
		$sKey = $j . '_' . $n . '_' . $m;
		if (!isset($this->cacheCount[$sKey])) {
			$this->cacheCount[$sKey] = $this->decompositionCount($j, $n, $m);
		}
		return $this->cacheCount[$sKey];
	}

	/**
	 * Расчёт количества вариаций для указанных параметров
	 * @param int $j
	 * @param int $n
	 * @param int $m
	 * @return int
	 */
	protected function decompositionCount($j, $n, $m) {
		if ($m == 0) {
			return ($n <= 9) ? 1 : 0;
		}
		if ($m == $n) {
			return 1;
		}
		if ($j > 0) {
			$iRight = $this->decompositionCountProxy($j - 1, $n - 1, $m) + $this->decompositionCountProxy(9, $n - 1, $m - 1);
			return $iRight;
		} else {
			$iLeft = $this->decompositionCountProxy(9, $n - 1, $m - 1);
			return $iLeft;
		}
	}

	/**
	 * Прокси-метод для получения варианта декомпозиции для указанных параметров по номеру через кеш
	 * @param int $num номер декомпозиции
	 * @param int $j
	 * @param int $n
	 * @param int $m
	 * @return string
	 */
	protected function generateDecompositionProxy($num, $j, $n, $m) {
		$sKey = $num . '_' . $j . '_' . $n . '_' . $m;
		if (!isset($this->cacheValue[$sKey])) {
			$v = array();
			$this->generateDecomposition($v, $num, $j, $n, $m);
			$r = array();
			$s = 0; $c = 0;
			foreach (array_reverse($v) as $i) {
				if ($i == 0) {
					$c++;
				} else {
					$r[] = $c;
					$c = 0; $s++;
				}
			}
			$r[] = $c;
			$this->cacheValue[$sKey] = implode('', $r);
		}
		return $this->cacheValue[$sKey];
	}

	/**
	 * Генерация последовательности по указанными параметрам по номеру последовательности
	 * @param int[] $v - массив для хранения бинарной последовательности прохода по дереву
	 * @param int $num - номер счастливого билета
	 * @param int $j
	 * @param int $n
	 * @param int $m
	 */
	protected function generateDecomposition(&$v, $num, $j, $n, $m) {
		if ($m == 0) {
			if ($n > 9) return;
			for ($i = 1; $i <= $n; $i++) $v[] = 0;
			return;
		}
		if ($m == $n) {
			for ($i = 1; $i <= $n; $i++) $v[] = 1;
			return;
		}
		$b = $this->decompositionCountProxy($j - 1, $n - 1, $m);
		if ($num <= $b && $j > 0) {
			$v[] = 0;
			$this->generateDecomposition($v, $num, $j - 1, $n - 1, $m);
		} else {
			$v[] = 1;
			if ($j == 0) {
				$this->generateDecomposition($v, $num, $j, $n - 1, $m - 1);
			} else {
				$this->generateDecomposition($v, $num - $b, 9, $n - 1, $m - 1);
				return;
			}
		}
	}

	/**
	 * Генерация номера счастливого билета по его порядковому номеру
	 * @param int $iNumber - количество разрядов
	 * @param int $iNum - порядковый номер
	 * @return string
	 */
	public function generateLuckyTicket($iNumber, $iNum) {
		$iHalf = (int)($iNumber / 2);
		$iLength = (int)($iHalf * 9);
		$b = 1;
		// Уточнение порядкового номера в рамках конкретной группы по сумме цифр в половине номера
		for ($i = 0; $i <= $iLength; $i++) {
			if ($i <= ($iLength / 2)) {
				$b = $this->decompositionCountProxy(9, $i + $iHalf - 1, $iHalf - 1);
			} else {
				$b = $this->decompositionCountProxy(9, $iLength - $i + $iHalf - 1, $iHalf - 1);
			}
			if (($iNum - pow($b, 2)) < 0) break;
			$iNum -= pow($b, 2);
		}
		$iNumLeft = (int)($iNum / $b);
		$iNumRight = $iNum % $b;
		// Получение декомпозиций по найденному номеру для левой и для правой части
		$sLeft = $this->generateDecompositionProxy($iNumLeft + 1, 9, $i + $iHalf - 1, $iHalf - 1);
		$sRight = $this->generateDecompositionProxy($iNumRight + 1, 9, $i + $iHalf - 1, $iHalf - 1);
		$iLeftLength = strlen($sLeft);
		$iRightLength = strlen($sRight);
		$sLeft = ($iLeftLength == $iHalf) ? $sLeft : (array_fill(0, $iHalf - $iLeftLength, '0'));
		$sRight = ($iRightLength == $iHalf) ? $sRight : (array_fill(0, $iHalf - $iRightLength, '0'));
		return $sLeft . $sRight;
	}

	/**
	 * Постраничная генерация номеров счастливых билетов указанной разрядности
	 * @param int $iNumber
	 * @param int $iPage
	 * @param int $iLimit
	 * @return string[]
	 */
	public function generateLuckyTicketList($iNumber, $iPage, $iLimit) {
		$iMax = $this->countLuckyTicket($iNumber);
		$iOffset = $iLimit * ($iPage - 1);
		$aData = array();
		for ($i = $iOffset; $i < min($iMax, $iOffset + $iLimit); $i++) {
			$iValue = $this->generateLuckyTicket($iNumber, $i);
			$aData[] = $iValue;
		}
		return $aData;
	}

	/**
	 * Медленный поиск номеров счастливых билетов, зато по порядку
	 * @param int $iNumber Разрядность номера
	 * @param int $iLimit Количество необходимых номеров
	 * @param int $iStart Первый номер, с которого начинать искать счастливые номера
	 * @return array
	 */
	public function generateSortedLuckyTicketList($iNumber, $iLimit, $iStart) {
		$iHalf = (int) ($iNumber / 2);
		$i = 0;
		$aNumbersCount = $this->numberCount($iNumber);
		$iDelimiter = pow(10, $iHalf);
		$iLeft = (int) ($iStart / $iDelimiter);
		$iRight = $iStart - $iLeft * $iDelimiter;
		$aData = array();
		do { // Цикл по левой части номера
			$iLeftCount = 0;
			$iLeftSum = array_sum(str_split((string)$iLeft));
			do { // Цикл по правой части номера
				$iRightSum = array_sum(str_split((string)$iRight));
				if ($iLeftSum == $iRightSum) {
					$iValue = $iLeft * $iDelimiter + $iRight;
					$iLength = strlen((string)$iValue);
					$aData[] = (string)($iLength == $iNumber ? $iValue : (implode('', array_fill(0, ($iNumber - $iLength), '0')) . $iValue));
					$i++;
					$iLeftCount++;
					// Если количество найденных номеров в правой части для текущей суммы в левой части достигнуто - переходим к следующему числу в левой части
					if ($iLeftCount >= $aNumbersCount[$iHalf][$iLeftSum]) {
						break;
					}
				}
				$iRight++;
			} while ($i < $iLimit && $iRight < $iDelimiter);
			$iRight = 1;
			$iLeft++;
		} while ($i < $iLimit && $iLeft < $iDelimiter);
		return $aData;
	}

	/**
	 * Расчёт количества счастливых билетов для указанной длины числа
	 * @param int $iNumber
	 * @return int
	 */
	public function countLuckyTicket($iNumber) {
		return $this->countLuckyTicketFast($iNumber);
	}

	/**
	 * Медленный расчёт количества счастливых билетов для указанной длины числа
	 * @param int $iNumber
	 * @return int
	 */
	public function countLuckyTicketSlow($iNumber) {
		$iHalf = (int)($iNumber / 2);
		$iLength = (int)($iHalf * 9);
		$iCount = 0;
		for ($i = 0; $i <= $iLength; $i++) {
			if ($i <= ($iLength / 2)) {
				$b = $this->decompositionCountProxy(9, $i + $iHalf - 1, $iHalf - 1);
			} else {
				$b = $this->decompositionCountProxy(9, $iLength - $i + $iHalf - 1, $iHalf - 1);
			}
			$iCount = function_exists('bcadd') ? bcadd($iCount, bcpow($b, 2)) : ($iCount + pow($b, 2));
		}
		return $iCount;
	}

	/**
	 * Быстрый алгоритм расчёта количества счастливых билетов на основе разрядности
	 * @param int $iNumber
	 * @return int
	 */
	public function countLuckyTicketFast($iNumber) {
		$iHalf = (int)($iNumber / 2);
		$aData = $this->numberCount($iNumber);
		$iCount = 0;
		for ($i = 0; $i <= $iHalf * 9; $i++) {
			$iCount = function_exists('bcadd') ? bcadd($iCount, bcmul($aData[$iHalf][$i], $aData[$iHalf][$i])) : ($iCount + $aData[$iHalf][$i] * $aData[$iHalf][$i]);
		}
		return $iCount;
	}

	/**
	 * Расчёт списка возможных вариаций номеров счастливых билетов на основе разрядности
	 * @param int $iNumber
	 * @return int[][]
	 */
	protected function numberCount($iNumber) {
		$iHalf = (int)($iNumber / 2);
		$aData = array();
		for ($i = 1; $i <= $iHalf; $i++) {
			$iLength = $i * 9 + 1;
			if ($i == 1) {
				for ($j = 0; $j < $iLength; $j++)
					$aData[$i][$j] = 1;
			}
			else {
				$iSum = 0;
				$k = 0;
				for (; $k <= $iLength / 2; $k++) {
					$iSum += $aData[$i - 1][$k];
					if ($k >= 10)
						$iSum -= $aData[$i - 1][$k - 10];
					$aData[$i][$k] = $iSum;
				}
				for (; $k < $iLength; $k++) {
					$aData[$i][$k] = $aData[$i][$iLength - 1 - $k];
				}
			}
		}
		return $aData;
	}
}