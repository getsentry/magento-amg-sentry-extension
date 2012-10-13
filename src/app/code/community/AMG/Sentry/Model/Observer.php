<?php

class AMG_Sentry_Model_Observer {

    public function initSentryLogger($observer){
        if (Mage::getStoreConfigFlag('dev/amg-sentry/active')) {
            require_once(dirname(__FILE__) . DS . '..' . DS . 'functions.php');
            Mage::app()->setErrorHandler('sentry_error_handler');

            $php_error_handler = new Raven_ErrorHandler(Mage::getSingleton('amg-sentry/client'));
            if (Mage::getStoreConfigFlag('dev/amg-sentry/php-errors')) {
                set_error_handler(array($php_error_handler, 'handleError'));
            }

            if (Mage::getStoreConfigFlag('dev/amg-sentry/php-exceptions')) {
                set_exception_handler(array($php_error_handler, 'handleException'));
            }
        }
    }

}

