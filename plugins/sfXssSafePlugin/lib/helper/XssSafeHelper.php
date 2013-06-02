<?php

/**
 * XssSafe Helper - Clean cross site scripting exploits from string 
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Alexandre Mogère
 *
 * @uses <a href="http://htmlpurifier.org/">HTML Purifier</a>
 */

define('HTMLPURIFIER_PREFIX', realpath(dirname(__FILE__) . '/../vendor/htmlpurifier/library'));

if (!class_exists('HTMLPurifier_PropertyList'))
{
  require_once(HTMLPURIFIER_PREFIX . '/HTMLPurifier.auto.php');
}

/**
 * The function runs HTML Purifier as an alternative between
 * escaping raw and escaping entities.
 *
 * @param string $html the value to clean
 * @return string the escaped value
 */
function esc_xsssafe($html)
{
  return sfXssSafe::clean($html);
}

define('ESC_XSSSAFE', 'esc_xsssafe');

/**
 * Truncates raw +text+ to the length of +length+ and replaces the last three characters with the +truncate_string+
 * if the +text+ is longer than +length+.
 */
function truncate_safe_text($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false)
{
  sfContext::getInstance()->getConfiguration()->loadHelpers('Text');

  return esc_xsssafe(truncate_text($text, $length, $truncate_string, $truncate_lastspace));
}