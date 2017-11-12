<?php namespace TT\Session;

/**
 * @author  Samir Rustamov <rustemovv96@gmail.com>
 * @link 	https://github.com/SamirRustamov/Session
 */

use SessionHandlerInterface;



class SessionHandlerFile implements SessionHandlerInterface
{


  private $sessionSavePath;




  public function open($sessionSavePath,$sessionName):Bool
  {
    $this->sessionSavePath = config('session.file_location');
    return true;
  }


  public function close():Bool
  {
    return $this->gc(ini_get('session.gc_maxlifetime'));
  }



  public function read($id):String
  {
    return (string) @file_get_contents(
               $this->sessionSavePath.'/session_'.$id
             );
  }



  public function write($id,$sesionSavedata):Bool
  {
    $sessionSavePath = $this->sessionSavePath.'/session_'.$id;

    if($fp = @fopen($sessionSavePath,'w+')) {

      $return = fwrite($fp,$sesionSavedata);

      fclose($fp);

      return (bool) $return;

    }
    return false;

  }







  public function destroy($id):Bool
  {

    @unlink("{$this->sessionSavePath}/session_".$id);

    return true;
  }



  public function gc($maxlifetime):Bool
  {
    $sessionSavePath = $this->sessionSavePath;

    foreach (glob("{$sessionSavePath}/session_*") as $file) {
      if((filemtime($file) + $maxlifetime) < time()) {
        @unlink($file);
      }
    }
    return true;
  }





}
