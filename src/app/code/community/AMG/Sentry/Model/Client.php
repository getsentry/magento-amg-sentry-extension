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

$ravenDir = Mage::getBaseDir() . DS . 'lib' . DS . 'Raven';
require_once($ravenDir . DS . 'Autoloader.php');
Raven_Autoloader::register();
//require_once($ravenDir . DS . 'Compat.php');
//require_once($ravenDir . DS . 'ErrorHandler.php');
//require_once($ravenDir . DS . 'Stacktrace.php');

class AMG_Sentry_Model_Client extends Raven_Client {

	static protected $_logger = null;

	public function __construct() {

		$data = [];
		$data['tags']['runtime'] = 'PHP '.PHP_VERSION;

		parent::__construct(Mage::getStoreConfig('dev/amg-sentry/dsn'), $data);
	}

	/**
	 * Send a message to Sentry.
	 *
	 * @param string $title Message title
	 * @param string $description Message description
	 * @param string $level Message level
	 * @return integer Sentry event ID
	 */
	public function sendMessage($title, $description = '', $level = self::INFO) {
		return $this->captureMessage($title, ['description' => $description], $level);
	}

	/**
	 * Send an exception to Sentry.
	 *
	 * @param Exception $exception Exception
	 * @param string $description Exception description
	 * @return integer Sentry event ID
	 */
	public function sendException($exception, $description = '') {
		return $this->captureException($exception, $description);
	}

	/**
	 * Log a message to sentry
	 */
	public function capture($data, $stack) {
		if (!Mage::getStoreConfigFlag('dev/amg-sentry/active')) {
			return true;
		}
		if (!empty($data['sentry.interfaces.Message']['params']['description'])) {
			$data['culprit'] = $data['message'];
			$data['message'] = $data['sentry.interfaces.Message']['params']['description'];
			unset($data['sentry.interfaces.Message']['params']['description']);
		}
		if (!empty($data['sentry.interfaces.Exception']['value'])) {
			$data['message'] = isset($data['culprit']) ? $data['culprit'] : 'n/a';
			$data['culprit'] = $data['sentry.interfaces.Exception']['value'];
		}
		if (!isset($data['logger'])) {
			if (null !== self::$_logger) {
				$data['logger'] = self::$_logger;
			} else {
				$data['logger'] = Mage::getStoreConfig('dev/amg-sentry/logger');
			}
		}

		return parent::capture($data, $stack);
	}

	/**
	 * Set Sentry logger.
	 *
	 * @param string $logger Logger
	 */
	public function setLogger($logger) {
		$this->_logger = $logger;
	}

	/**
	 * Reset Sentry logger.
	 */
	public function resetLogger() {
		$this->_logger = null;
	}
}
