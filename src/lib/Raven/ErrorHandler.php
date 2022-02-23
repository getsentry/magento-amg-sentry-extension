<?php
/**
 * This file is part of Raven.
 *
 * (c) Sentry Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (BSD-3-Clause).
 */

class Raven_ErrorHandler
{
    public function __construct($client)
    {
        $this->client = $client;
    }

    public function handleException($e, $isError = false)
    {
        $e->event_id = $this->client->getIdent($this->client->captureException($e));
        if (!$isError && $this->call_existing_exception_handler && $this->old_exception_handler) {
            call_user_func($this->old_exception_handler, $e);
        }
    }

    public function handleError($code, $message, $file='', $line=0, $context=[])
    {

        $e = new ErrorException($message, 0, $code, $file, $line);
        $this->handleException($e, true);


        if ($this->call_existing_error_handler && $this->old_error_handler) {
            call_user_func($this->old_error_handler, $code, $message, $file, $line, $context);
        }
    }

    public function registerExceptionHandler($call_existing_exception_handler = true)
    {
        $this->old_exception_handler = set_exception_handler([$this, 'handleException']);
        $this->call_existing_exception_handler = $call_existing_exception_handler;
    }

    public function registerErrorHandler($call_existing_error_handler = true, $error_types = E_ALL)
    {
        $this->old_error_handler = set_error_handler([$this, 'handleError'], $error_types);
        $this->call_existing_error_handler = $call_existing_error_handler;
    }

    private $old_exception_handler = null;
    private $call_existing_exception_handler = false;
    private $old_error_handler = null;
    private $call_existing_error_handler = false;
}
