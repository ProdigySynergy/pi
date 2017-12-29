<?php
/**
* @return states from db according to method
*/
class Autoload extends PIModel
{
	

  public function __construct()
  {
    parent::__construct();
  }

  public static function model($class = '')
  {
    if ( !empty($class) )
    {
      $aclass = explode(',', $class);

      for ($i=0; $i < count($aclass); $i++) {
        //Autoload classes
        $theclass = $aclass[$i];
        $theclass = ucwords(strtolower($theclass));
        $theclass = $theclass.'Model';
        spl_autoload_register(function($theclass) {
          
          if ( file_exists(ROOT.DS.'application'.DS.'Models'. DS . $theclass . '.php') )
          {
            require_once ROOT.DS.'application'.DS.'Models'. DS . $theclass . '.php';

          }
          
        });
      }
    }
  }


  public static function controller($class = '')
  {
    if ( !empty($class) )
    {
      $split = explode(',', $class);

      foreach ($split as $aclass) {
        $aclass = strtolower($aclass);
        //Autoload classes
        spl_autoload_register(function($aclass) {
          
          if ( file_exists(ROOT.DS.'application'.DS.'Controllers'. DS . $aclass . '.php') )
          {
            require_once ROOT.DS.'application'.DS.'Controllers'. DS . $aclass . '.php';

          }
          
        });
      }
    }
  }

}