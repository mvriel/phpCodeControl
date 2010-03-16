<?php

/**
 * sfGeshi class.
 *
 * Class providing easy use of GeSHi in symfony.
 *
 * @package    plugin
 * @subpackage sfGeshiPlugin
 * @class      sfGeshi
 * @author     Tomasz Ducin <tomasz.ducin@gmail.com>
 */
class sfGeshi
{
  /**
   * Internal callback function used within parse_mixed method
   * (@link sfGeshi->parse_mixed()).
   * Calls parse_single method once (and is called by parse_mixed)
   * for each code block.
   *
   * @param Array $matches - Array of matches returned for each code block.
   */
  static private function highlight($matches)
  {
    return sfGeshi::parse_single($matches[3], $matches[2]);
  }

  /**
   * Method highlights single code block. If the language file is overriden
   * in sfGeshiPlugin/lib/custom directory, it uses it. Otherwise, it uses
   * default GeSHi language file from its lib.
   *
   * @param String $code - code block.
   * @param String $language - language name of the code block.
   */
  static public function parse_single($code, $language)
  {
    $geshi_object = new GeSHi($code, $language);
    $alternative_directory = GESHI_LANG_ROOT.'../../custom/';
    $alternative_file = $alternative_directory.strtolower($language).'.php';
    if (file_exists($alternative_file))
    {
      $geshi_object->set_language_path($alternative_directory);
    }
    return $geshi_object->parse_code();
  }

  /**
   * Method highlights mixed code block. The block may contain both
   * normal text blocks and code blocks. Code blocks may be written
   * in different languages. Code blocks are surrounded with
   * [name]...[/name] tags (<i>nameM/i> specifies name of the language).
   * All the rest is treated as normal text and is not highlighted.
   *
   * @param String $mixed - mixed text-and-code block.
   */
  static public function parse_mixed($mixed)
  {
    return preg_replace_callback(
      "/(\[([0-9a-zA-Z]*)\]((.|\n)*?)\[\/])/",
      array("sfGeshi", "highlight"),
      $mixed);
  }
}

?>