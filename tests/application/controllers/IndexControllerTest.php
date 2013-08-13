<?php

class IndexControllerTest extends ControllerTestCase {
	public function testIndexAction() {
		$this->dispatch('/index/index');
		$this->assertModule('default');
		$this->assertController('index');
		$this->assertAction('index');
	}

	public function testIndexDefaultAction() {
		$this->dispatch('/index/index');
		$this->assertModule('default');
		$this->assertController('index');
		$this->assertAction('index');
		$this->assertResponseCode(200);

		$this->assertQueryCount('.view-content', 1);
		$this->assertQueryCount('a', 1);
	}
}