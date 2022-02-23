<?php
/**
 * This file is part of Raven.
 *
 * (c) Sentry Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (BSD-3-Clause).
 */

class Raven_SanitizeDataProcessor extends Raven_Processor
{
    // Asterisk out passwords from password fields in frames, http, and basic extra data.
    const MASK = '********';
    const FIELDS_RE = '/(authorization|password|passwd|secret)/i';
    const VALUES_RE = '/^\d{16}$/';

    public function apply($value, $fn, $key=null)
    {
        if (is_array($value)) {
            foreach ($value as $k=>$v) {
                $value[$k] = $this->apply($v, $fn, $k);
            }
            return $value;
        }
        return call_user_func($fn, $key, $value);
    }

    public function sanitize($key, $value)
    {
        if (empty($value)) {
            return $value;
        }

        if (preg_match(self::VALUES_RE, $value)) {
            return self::MASK;
        }

        if (preg_match(self::FIELDS_RE, $key)) {
            return self::MASK;
        }

        return $value;
    }

    public function process($data)
    {
        return $this->apply($data, [$this, 'sanitize']);
    }
}
