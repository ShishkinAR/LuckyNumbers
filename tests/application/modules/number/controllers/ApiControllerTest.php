<?php

class Number_ApiControllerTest extends ControllerTestCase {
	public function testCheckAction() {
		$this->dispatch('/number/api/check');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('check');
	}

	public function testCheckNumber2Action() {
		$this
			->getRequest()
			->setParams(array("number" => "2"))
			->setMethod('POST');

		$this->dispatch('/number/api/check');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('check');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'ok');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '100');
		$this->assertArrayHasKey('data', $aData);
		$this->assertArrayHasKey('count', $aData['data']);
		$this->assertEquals(10, $aData['data']['count']);
	}

	public function testCheckNumber4Action() {
		$this
			->getRequest()
			->setParams(array("number" => "4"))
			->setMethod('POST');

		$this->dispatch('/number/api/check');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('check');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'ok');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '100');
		$this->assertArrayHasKey('data', $aData);
		$this->assertArrayHasKey('count', $aData['data']);
		$this->assertEquals(670, $aData['data']['count']);
	}

	public function testCheckNumber14Action() {
		$this
			->getRequest()
			->setParams(array("number" => "14"))
			->setMethod('POST');

		$this->dispatch('/number/api/check');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('check');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'ok');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '100');
		$this->assertArrayHasKey('data', $aData);
		$this->assertArrayHasKey('count', $aData['data']);
		$this->assertEquals(3671331273480, $aData['data']['count']);
	}

	public function testCheckFailNumberAction() {
		$this
			->getRequest()
			->setParams(array("number" => "aaa"))
			->setMethod('POST');

		$this->dispatch('/number/api/check');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('check');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'error');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '201');
	}

	public function testCheckFailPostAction() {
		$this->dispatch('/number/api/check');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('check');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'error');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '301');
	}

	public function testCheckFailOddAction() {
		$this
			->getRequest()
			->setParams(array("number" => "3"))
			->setMethod('POST');

		$this->dispatch('/number/api/check');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('check');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'error');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '202');
	}

	public function testCheckFailMaxAction() {
		$this
			->getRequest()
			->setParams(array("number" => "312"))
			->setMethod('POST');

		$this->dispatch('/number/api/check');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('check');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'error');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '203');
	}

	public function testList4Action() {
		$this
			->getRequest()
			->setParams(array("number" => "4"))
			->setMethod('POST');

		$this->dispatch('/number/api/list');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('list');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'ok');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '100');
		$this->assertArrayHasKey('data', $aData);
		$this->assertArrayHasKey('numbers', $aData['data']);
		$this->assertTrue(is_array($aData['data']['numbers']));
		$this->assertEquals(100, count($aData['data']['numbers']));
	}

	public function testListFailPageAction() {
		$this
			->getRequest()
			->setParams(array("number" => "4", "page" => 0))
			->setMethod('POST');

		$this->dispatch('/number/api/list');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('list');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'error');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '310');
	}

	public function testListFailLimitAction() {
		$this
			->getRequest()
			->setParams(array("number" => "4", "limit" => 0))
			->setMethod('POST');

		$this->dispatch('/number/api/list');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('list');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'error');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '311');
	}

	public function testList6Action() {
		$this
			->getRequest()
			->setParams(array("number" => "6"))
			->setMethod('POST');

		$this->dispatch('/number/api/list');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('list');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'ok');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '100');
		$this->assertArrayHasKey('data', $aData);
		$this->assertArrayHasKey('numbers', $aData['data']);
		$this->assertTrue(is_array($aData['data']['numbers']));
		$this->assertEquals(100, count($aData['data']['numbers']));
	}

	public function testList6Min5231Action() {
		$this
			->getRequest()
			->setParams(array("number" => "6", "page" => "2"))
			->setMethod('POST');

		$this->dispatch('/number/api/list');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('list');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'ok');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '100');
		$this->assertArrayHasKey('data', $aData);
		$this->assertArrayHasKey('numbers', $aData['data']);
		$this->assertTrue(is_array($aData['data']['numbers']));
		$this->assertEquals(100, count($aData['data']['numbers']));
	}

	public function testList4Min9999Action() {
		$this
			->getRequest()
			->setParams(array("number" => "4", "page" => "7"))
			->setMethod('POST');

		$this->dispatch('/number/api/list');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('list');
		$this->assertResponseCode(200);

		$sAnswer = $this->getResponse()->getBody();

		$this->assertGreaterThan(0, strlen($sAnswer));

		$aData = json_decode($sAnswer, true);

		$this->assertTrue(is_array($aData));
		$this->assertGreaterThan(0, count($aData));
		$this->assertArrayHasKey('type', $aData);
		$this->assertEquals($aData['type'], 'ok');
		$this->assertArrayHasKey('code', $aData);
		$this->assertEquals($aData['code'], '100');
		$this->assertArrayHasKey('data', $aData);
		$this->assertArrayHasKey('numbers', $aData['data']);
		$this->assertTrue(is_array($aData['data']['numbers']));
		$this->assertEquals(70, count($aData['data']['numbers']));
	}

	public function testList4Min10000Action() {
		$this
			->getRequest()
			->setParams(array("number" => "4", "page" => "8"))
			->setMethod('POST');

		$this->dispatch('/number/api/list');
		$this->assertModule('number');
		$this->assertController('api');
		$this->assertAction('list');
		$this->assertResponseCode(404);
	}
}