<?php
/**
 * This file is part of Raven.
 *
 * (c) Sentry Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (BSD-3-Clause).
 */

class Raven_Processor
{
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Process data and return updated message.
     */
    public function process($data)
    {
        return $data;
    }
}
