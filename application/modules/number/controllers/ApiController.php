<?php

class Number_ApiController extends Zend_Controller_Action {

	public function init() {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	}

	public function indexAction() {
		return true;
	}

	/**
	 * Проверка числа и определение количества счастливых билетов
	 * @return bool
	 */
	public function checkAction() {
		$iNumber = $this->checkNumber();
		if (!$iNumber) return false;
		$oNumber = new Number_Model_Numbers();
		$iResult = $oNumber->countLuckyTicket($iNumber);
		echo json_encode(array(
			'type' => 'ok', 'code' => '100',
			'message' => 'Количество счастливых билетов: ' . $iResult,
			'data' => array(
				'count' => (string)$iResult
			)
		));
		return true;
	}

	/**
	 * Получение списка счастливых билетов
	 * @return bool
	 */
	public function listAction() {
		$iNumber = $this->checkNumber(false);
		if (!$iNumber) return false;
		$iPage = (int)$this->getRequest()->getParam('page', 1);
		if ($iPage <= 0) {
			echo json_encode(array(
				'type' => 'error', 'code' => '310', 'message' => 'Номер страницы должен быть положительным',
			));
			return false;
		}
		$iLimit = (int)$this->getRequest()->getParam('limit', 100);
		if ($iLimit <= 0) {
			echo json_encode(array(
				'type' => 'error', 'code' => '311', 'message' => 'Ограничение количества номеров на одну страницу должно быть положительным',
			));
			return false;
		}
		$oNumber = new Number_Model_Numbers();
		$aData = $oNumber->generateLuckyTicketList($iNumber, $iPage, $iLimit);
		if (empty($aData)) {
			$this->getResponse()->setHttpResponseCode(404);
			echo json_encode(array(
				'type' => 'error', 'code' => '401', 'message' => 'Достигнут конец списка',
			));
			return false;
		}
		echo json_encode(array(
			'type' => 'ok', 'code' => '100',
			'message' => '',
			'data' => array(
				'numbers' => $aData,
			)
		));
		return true;
	}

	/**
	 * Проверка соответствия запроса и числа установленным правилам
	 * @param bool $bIsPost - признак необходимости проверять метод запроса
	 * @return bool|int
	 */
	protected function checkNumber($bIsPost = true) {
		/** @var $oRequest Zend_Controller_Request_Http */
		$oRequest = $this->getRequest();
		if ($bIsPost && !$oRequest->isPost()) {
			echo json_encode(array(
				'type' => 'error', 'code' => '301', 'message' => 'Запрос должен быть POST',
			));
			return false;
		}
		$iNumber = (int)$oRequest->getParam('number');
		if (!$iNumber || $iNumber < 0) {
			echo json_encode(array(
				'type' => 'error', 'code' => '201', 'message' => 'Введите допустимое значение',
			));
			return false;
		}
		if (($iNumber % 2) == 1) {
			echo json_encode(array(
				'type' => 'error', 'code' => '202', 'message' => 'Число должно быть четным.',
			));
			return false;
		}
		$iMaxNumber = !function_exists('bcadd') ? 310 : 999;
		if ($iNumber > $iMaxNumber) {
			echo json_encode(array(
				'type' => 'error', 'code' => '203', 'message' => 'Число из более чем ' . $iMaxNumber . ' разрядов?',
			));
			return false;
		}
		$iMaxNestingLevel = (int)ini_get('xdebug.max_nesting_level');
		if ($iMaxNestingLevel && $iNumber > ($iMaxNestingLevel - 10)) {
			echo json_encode(array(
				'type' => 'error', 'code' => '204', 'message' => 'Параметры сервера не позволют сделать расчёты для подобных чисел',
			));
			return false;
		}
		return $iNumber;
	}
}