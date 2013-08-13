<?php

class ErrorControllerTest extends ControllerTestCase
{
	public function testErrorURL()
	{
		$this->dispatch('foo');
		$this->assertModule('default');
		$this->assertController('error');
		$this->assertAction('error');
	}
}