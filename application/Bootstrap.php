<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	protected function _initAutoload() {
		new Zend_Application_Module_Autoloader(array(
			'namespace' => 'Number',
			'basePath' => APPLICATION_PATH . '/modules/number',
		));
	}
}