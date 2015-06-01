<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initPlaceholders()
		{
			$this->bootstrap('view');
			$view = $this->getResource('view');
			$view->doctype('XHTML1_STRICT');
			//Meta
			$view->headMeta()->appendName('keywords', 'cafee, shop')->appendHttpEquiv('Content-Type','text/html;charset=utf-8');
			// Set the initial title and separator:
			$view->headTitle('Cafee')->setSeparator(' - ');
			// Set the initial stylesheet:
			$view->headLink()->prependStylesheet('/cafee-helper/css/main.css','screen',true);
			// Set the initial JS to load:
			$view->headScript()->prependFile('/cafee-helper/js/jquery-1.11.2.min.js');
			
			
		}

}

