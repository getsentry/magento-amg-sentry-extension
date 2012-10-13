<?php

class AMG_Sentry_Model_Observer {

    public function initSentryLogger($observer){
        if (Mage::getStoreConfigFlag('dev/amg-sentry/active')) {
            require_once(dirname(__FILE__) . DS . '..' . DS . 'functions.php');
            Mage::app()->setErrorHandler('sentry_error_handler');

            $error_handler = new Raven_ErrorHandler(Mage::getSingleton('amg-sentry/client'));
            set_error_handler(array($error_handler, 'handleError'));
            set_exception_handler(array($error_handler, 'handleException'));
        }
    }

}