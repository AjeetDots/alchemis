<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty scalar modifier plugin
 *
 * Type:     modifier<br>
 * Name:     scalar<br>
 * Purpose:  ensure value is scalar for output (prevents "Array to string conversion").
 *           If value is an array, returns the default; otherwise returns value cast to float.
 * Example:  {$var|scalar:0}
 * @param mixed
 * @param mixed default value when input is array (default 0)
 * @return float|string
 */
function smarty_modifier_scalar($value, $default = 0)
{
    if (is_array($value)) {
        return is_numeric($default) ? (float) $default : $default;
    }
    return is_numeric($value) ? (float) $value : (string) $value;
}
