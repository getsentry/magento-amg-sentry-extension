<?php

/**
 * Sentry error handler
 *
 * @param integer $errno
 * @param string $errstr
 * @param string $errfile
 * @param integer $errline
 */
function sentry_error_handler($errno, $errstr, $errfile, $errline){
    if(error_reporting() != 0 or Mage::getStoreConfigFlag('dev/amg-sentry/ignore-error-control-operator')){
        Mage::getSingleton('amg-sentry/client')->sendMessage($errstr, sprintf('In %s on line %d', $errfile, $errline));
    }
}
