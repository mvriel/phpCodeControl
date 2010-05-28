<?php

/**
 * This model represents a set of changes on a single file in a specific revision.
 *
 * @package    phpCodeControl
 * @subpackage model
 * @author     Mike van Riel <mike.vanriel@naenius.com>
 */
class FileChange extends BaseFileChange
{
  /**
   * Returns the previous file change or null if none are found
   *
   * @return FileChange|null
   */
  public function findPrevious()
  {
    // if this is an addition, return null
    if ($this->getFileChangeTypeId() == 1)
    {
      return null;
    }

    // get the previous commit which contains a file with the same file_path
    return Doctrine_Query::create()->
            addFrom('Commit c')->
            innerJoin('c.FileChange fc')->
            where('c.scm_id=?', $this->getCommit()->getScmId())->
            andWhere('fc.file_path=?', $this->getFilePath())->
            andWhere('c.timestamp < ?', $this->getCommit()->getTimestamp())->
            addOrderBy('c.timestamp DESC')->
            limit(1)->
            fetchOne();
  }

  /**
   * Returns the Mime content type for the file of this filechange.
   *
   * @return string
   */
  public function getMimeContentType()
  {
    $filename = $this->getFilePath();

    $mime_types = array(

      'txt' => 'text/plain',
      'java' => 'text/plain',
      'phtml' => 'text/plain',
      'tpl' => 'text/plain',
      'diff' => 'text/plain',
      'patch' => 'text/plain',
      'htm' => 'text/html',
      'html' => 'text/html',
      'php' => 'text/html',
      'css' => 'text/css',
      'js' => 'application/javascript',
      'json' => 'application/json',
      'xml' => 'application/xml',
      'swf' => 'application/x-shockwave-flash',
      'flv' => 'video/x-flv',

      // images
      'png' => 'image/png',
      'jpe' => 'image/jpeg',
      'jpeg' => 'image/jpeg',
      'jpg' => 'image/jpeg',
      'gif' => 'image/gif',
      'bmp' => 'image/bmp',
      'ico' => 'image/vnd.microsoft.icon',
      'tiff' => 'image/tiff',
      'tif' => 'image/tiff',
      'svg' => 'image/svg+xml',
      'svgz' => 'image/svg+xml',

      // archives
      'zip' => 'application/zip',
      'rar' => 'application/x-rar-compressed',
      'exe' => 'application/x-msdownload',
      'msi' => 'application/x-msdownload',
      'cab' => 'application/vnd.ms-cab-compressed',

      // audio/video
      'mp3' => 'audio/mpeg',
      'qt' => 'video/quicktime',
      'mov' => 'video/quicktime',

      // adobe
      'pdf' => 'application/pdf',
      'psd' => 'image/vnd.adobe.photoshop',
      'ai' => 'application/postscript',
      'eps' => 'application/postscript',
      'ps' => 'application/postscript',

      // ms office
      'doc' => 'application/msword',
      'rtf' => 'application/rtf',
      'xls' => 'application/vnd.ms-excel',
      'ppt' => 'application/vnd.ms-powerpoint',

      // open office
      'odt' => 'application/vnd.oasis.opendocument.text',
      'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    // try to guess the mimetype based on the extension
    $parts = explode('.', $filename);
    $ext = strtolower(array_pop($parts));
    if (array_key_exists($ext, $mime_types))
    {
      return $mime_types[$ext];
    }

    // try whether the file info module exists
    if (function_exists('finfo_open'))
    {
      $finfo = finfo_open(FILEINFO_MIME);
      $mimetype = finfo_file($finfo, $filename);
      finfo_close($finfo);
      return $mimetype;
    }

    // dunno; so it must be a binary
    return 'application/octet-stream';
  }

}
