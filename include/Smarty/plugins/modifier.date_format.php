<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Include the {@link shared.make_timestamp.php} plugin
 */
require_once $smarty->_get_plugin_filepath('shared','make_timestamp');
/**
 * Smarty date_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     date_format<br>
 * Purpose:  format datestamps via strftime<br>
 * Input:<br>
 *         - string: input date string
 *         - format: strftime format for output
 *         - default_date: default date if $string is empty
 * @link http://smarty.php.net/manual/en/language.modifier.date.format.php
 *          date_format (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string|void
 * @uses smarty_make_timestamp()
 */
/**
 * Convert a strftime() format string to a date() format string and format the timestamp.
 * This is a compatibility shim for PHP 8.1+ where strftime() is deprecated.
 */
function smarty_strftime_compat($format, $timestamp)
{
    $mapping = array(
        '%A' => 'l',       // Full day name
        '%a' => 'D',       // Short day name
        '%B' => 'F',       // Full month name
        '%b' => 'M',       // Short month name
        '%C' => '',        // Century (not directly supported, skip)
        '%d' => 'd',       // Day with leading zero
        '%e' => 'j',       // Day without leading zero
        '%G' => 'o',       // ISO year
        '%g' => 'y',       // ISO year 2-digit
        '%H' => 'H',       // Hour 24h with leading zero
        '%I' => 'h',       // Hour 12h with leading zero
        '%j' => 'z',       // Day of the year
        '%M' => 'i',       // Minutes
        '%m' => 'm',       // Month with leading zero
        '%n' => "\n",      // Newline
        '%p' => 'A',       // AM/PM uppercase
        '%P' => 'a',       // am/pm lowercase
        '%R' => 'H:i',     // 24hr HH:MM
        '%r' => 'h:i:s A', // 12hr time
        '%S' => 's',       // Seconds
        '%T' => 'H:i:s',   // 24hr time
        '%t' => "\t",      // Tab
        '%u' => 'N',       // Day of week (1=Mon)
        '%V' => 'W',       // ISO week number
        '%W' => 'W',       // Week number
        '%w' => 'w',       // Day of week (0=Sun)
        '%X' => 'H:i:s',   // Time representation
        '%x' => 'm/d/Y',   // Date representation
        '%Y' => 'Y',       // 4-digit year
        '%y' => 'y',       // 2-digit year
        '%Z' => 'T',       // Timezone abbreviation
        '%z' => 'O',       // Timezone offset
        '%%' => '%',       // Literal percent
    );
    $date_format = strtr($format, $mapping);
    return date($date_format, $timestamp);
}

function smarty_modifier_date_format($string, $format="%b %e, %Y", $default_date=null)
{
    if($string != '') {
        return smarty_strftime_compat($format, smarty_make_timestamp($string));
    } elseif (isset($default_date) && $default_date != '') {
        return smarty_strftime_compat($format, smarty_make_timestamp($default_date));
    } else {
        return;
    }
}

/* vim: set expandtab: */

?>
