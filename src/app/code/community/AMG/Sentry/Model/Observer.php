<?php

class AMG_Sentry_Model_Observer {

    public function initSentryLogger($observer){
        if (Mage::getStoreConfigFlag('dev/amg-sentry/active')) {
            require_once(dirname(__FILE__) . DS . '..' . DS . 'functions.php');
            Mage::app()->setErrorHandler('sentry_error_handler');
        }
    }

}