<?php

class Number_IndexControllerTest extends ControllerTestCase {
	// проверяем что данный экшен доступен
	public function testIndexAction() {
		$this->dispatch('/number/index/index');
		$this->assertModule('number');
		$this->assertController('index');
		$this->assertAction('index');
	}

	// проверяем поведение экшена по умолчанию
	public function testIndexDefaultAction() {
		$this->dispatch('/number/index/index');
		$this->assertModule('number');
		$this->assertController('index');
		$this->assertAction('index');
		$this->assertResponseCode(200);

		$this->assertQueryCount('#number_btn', 1);
		$this->assertQueryCount('#test_number', 1);
		$this->assertQueryCount('#numbers', 1);
	}
}