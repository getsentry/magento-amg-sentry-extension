<?php
/**

 AMG Sentry

 NOTICE OF LICENSE

 This source file is subject to the Open Software License (OSL 3.0)
 that is bundled with this package in the file LICENSE.txt.
 It is also available through the world-wide-web at this URL:
 http://opensource.org/licenses/osl-3.0.php

 @category      AMG
 @package       AMG_Sentry
 @copyright     Copyright © 2012 Jean Roussel <contact@jean-roussel.fr>
 @license       http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)

*/

class AMG_Sentry_Model_Observer {

	public function initSentryLogger($observer) {

		if (Mage::getStoreConfigFlag('dev/amg-sentry/active')) {

			require_once(dirname(__FILE__) . DS . '..' . DS . 'functions.php');
			Mage::app()->setErrorHandler('sentry_error_handler');

			$php_error_handler = new Raven_ErrorHandler(Mage::getSingleton('amg-sentry/client'));
			if (Mage::getStoreConfigFlag('dev/amg-sentry/php-errors')) {
				set_error_handler([$php_error_handler, 'handleError']);
			}

			if (Mage::getStoreConfigFlag('dev/amg-sentry/php-exceptions')) {
				set_exception_handler([$php_error_handler, 'handleException']);
			}
		}
	}
}
