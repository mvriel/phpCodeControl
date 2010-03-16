<?php
/**
 * sfOfcPluginConfiguration.class
 */

/**
 * stOfcPluginConfiguration
 *
 * @author Dawood RASHID
 * @version 24 mars 2009
 * @package symfony
 * @subpackage stOfcPlugin
 */

class stOfcPluginConfiguration extends sfPluginConfiguration
{
  /**
   * Initialize
   */
  public function initialize()
  {
  	// Plugin dir
    sfConfig::set('st_ofc_root_dir', dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');

    // Core OFC library dir
    sfConfig::set('st_ofc_lib_dir', sfConfig::get('st_ofc_root_dir') . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ofc');

    // OFC Object
    sfConfig::set('st_ofc_object', sfConfig::get('st_ofc_lib_dir') . DIRECTORY_SEPARATOR . 'open_flash_chart_object.php' );
    
    // OFC data dir
    sfConfig::set('st_ofc_data_dir', sfConfig::get('st_ofc_root_dir') . DIRECTORY_SEPARATOR . 'data');
    
    // stOfcPlugin's images directory
    sfConfig::set('st_ofc_images_dir', sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . 'stOfcPlugin' . DIRECTORY_SEPARATOR . 'images');
  }
}