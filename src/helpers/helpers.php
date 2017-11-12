<?php

/**
 * @author  Samir Rustamov <rustemovv96@gmail.com>
 * @link 	https://github.com/SamirRustamov/Session
 */



use TT\Session\Session;


function session($key = null,$value = null)
{
  static $instance = null;

  if(is_null($instance)) {
    $instance = new Session();
  }


  if(is_null($key)) {
      return $instance;
  } elseif (is_null($value)) {
      return $instance->get($key);
  } else  {
      return $instance->set($key,$value);
  }


}



function config($config)
{

  $static_config      = null;
  $static_config_name = null;

  if(strpos($config,'.') !== false) {
    list($config,$item) = explode('.',$config,2);
  }

  $config_file = BASEDIR."/src/config/{$config}.php";

  if(file_exists($config_file)) {
    if(is_null($static_config)) {
      $static_config = require($config_file);
    }
    if(is_null($static_config_name)) {
      $static_config_name = $config;
    }

    if($static_config_name == $config) {
      if(isset($item)) {
        return $static_config[$item] ?? false;
      } else {
        return $static_config;
      }
    } else {
      $static_config = require($config_file);
      $static_config_name = $config;
      if(isset($item)) {
        return $static_config[$item] ?? false;
      } else {
        return $static_config;
      }
    }

    if(isset($item)) {
      return $data[$item] ?? false;
    } else {
      return $data;
    }
  } else {
    throw new Exception("Config file not found [{$config_file}]");

  }
}
