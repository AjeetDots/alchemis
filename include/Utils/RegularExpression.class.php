<?php

/*
 * PHP
 * ---
 * 
 * See http://uk3.php.net/manual/en/reference.pcre.pattern.syntax.php
 *
 *
 * Perl-style
 * ----------
 * 
 * preg_match() does a Perl match.  Equivalent of m// operator in Perl
 * 
 * To match case-insensitivity, use the i flag on the pattern
 * 
 * regex are case-senstive by default
 * 
 * caret (^) at the beginning of a regex indicates that it must match the beginning of the
 * string (more precisely 'anchors' the regex to the beginning of the string).
 * 
 * dollar ($) at the end of a regex means it must match the end of the string (more precisely
 * 'anchors' the regex to the end of the string).
 * 
 * period (.) in a regex matches any single characters
 * 
 * Each pattern must be encolsed in a pair of delimiters.  Traditionally, the slash (/) character 
 * is used; eg /pattern/.  However, any non-alphanumeric character except the backslash (\) can be 
 * used.  This is useful when matching string containing slashes, such as filenames.  Eg, the 
 * following are equivalent:
 * 
 *     preg_match('/\/usr\/local\//', 'usr/local/bin/perl');  // returns true
 *     preg_match('#/usr/local/#',    'usr/local/bin/perl');  // returns true
 *     preg_match('{/usr/local/}',    'usr/local/bin/perl');  // returns true
 * 
 * period (.) matches any single character except for a newline (\n)
 * 
 * dollar sign ($) matches at the end of a string or, if the string ends in a newline, just
 * before that newline.
 * 
 * Repeating Sequences
 * -------------------
 * 
 *     ?      0 or 1
 *     *      0 or more
 *     +      1 or more
 *     {n}    exactly n times
 *     {n,m}  at least n times, no more than m times
 *     {n,}   at least n times
 * 
 *   For example:
 * 
 *     ereg('[0-9]{3}-[0-9]{3}-[0-9]{4}', '303-555-1212);   // returns true
 *     ereg('[0-9]{3}-[0-9]{3}-[0-9]{4}', '64-9-555-1234);  // returns false
 * 
 * Define a range of characters with a hyphen (-).  This simplifies character classes like 
 * 'all letters' and 'all digits'.
 * 
 *   For example:
 * 
 *     [0-9] is equivalent to [0123456789]
 * 
 * other examples are:
 * 
 *     [a-z] is equivalent to [abcdefghijklmnopqrstuvwxyz]
 *     [A-Z] is equivalent to [ABCDEFGHIJKLMNOPQRSTUVWXYZ]
 *
 * 
 * Alternatives
 * ------------
 * 
 * vertical pipe (|) to specify alternatives, eg ['cat|dog']
 * 
 * 
 * Subpatterns
 * -----------
 * 
 * Parentheses are used to group bits of a regualr expression together to be treated as a single 
 * unit.
 * 
 * The parentheses also cause the substring that matches the subpattern to be captured.  If you 
 * pass an array as the the argument to a match function, the array is populated with any captured 
 * substrings:
 * 
 *     ereg('([0-9]+)', 'You have 42 magic beans', $captured);
 *     // returns true and populates $captured with 42
 * 
 * Character Classes
 * -----------------
 * 
 *     \s    Whitespace                         [\r\n \t]
 *     \S    Non-whitespace                     [^\r\n \t]
 *     \w    Word (identifier) character        [0-9A-Za-z]
 *     \W    Non-word (identifier) character    [^0-9A-Za-z]
 *     \d    Digit                              [0-9]
 *     \D    Non-digit                          [^0-9]
 */


define('REGEX_EMAIL_ADDRESS',  '/^\S+@.+\..+$/');

define('REGEX_INTEGER',        '/^-?\d+$/');
define('REGEX_INTEGER_POS',    '/^\d+$/');
define('REGEX_INTEGER_NEG',    '/^-\d+$/');

define('REGEX_DECIMAL',        '/^-?\d+(\.\d+)?$/');
define('REGEX_DECIMAL_POS',    '/^\d+(\.\d+)?$/');
define('REGEX_DECIMAL_NEG',    '/^-\d+(\.\d+)?$/');

// 'DD-MM-YYYY' or 'DD/MM/YYYY'
//define('REGEX_DATE',           '/(^\d{2}-\d{2}-\d{4}$)|(^\d{2}\/\d{2}\/\d{4}$)/');

// 'YYYY-MM-DD'
define('REGEX_MYSQL_DATE',     '/(^\d{4}-\d{2}-\d{2}$)/');

// 'HH:MM:SS'
define('REGEX_MYSQL_TIME',     '/(^\d{2}:\d{2}:\d{2}$)/');

// 'YYYY-MM-DD HH:MM:SS'
define('REGEX_MYSQL_DATETIME', '/(^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$)/');


//define('REGEX_DATE',          '/^\d{4}-\d{2}-\d{2}$/');
define('REGEX_DATE',          '/^\d{2}\/\d{2}\/\d{4}$/');
define('REGEX_TIME',          '/^\d{2}:\d{2}:\d{2}$/');
define('REGEX_TIMESTAMP',     '/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/');
define('REGEX_YEAR_MONTH',    '/^\d{6}$/');

?>