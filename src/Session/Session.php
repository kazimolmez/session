<?php namespace TT\Session;

/**
 * @author  Samir Rustamov <rustemovv96@gmail.com>
 * @link https://github.com/SamirRustamov/Session
 */


use TT\Session\SessionHandlerFile;
use TT\Session\SessionInterface;



class Session implements SessionInterface
{

    const ENC_KEY = "fepojkfjpaksdjwqu9835df398uyd=2x#8X#$";

    protected static $config;


    public function __construct()
    {
        if (is_null(self::$config)) {
            self::$config = config('session');

            ini_set('session.cookie_httponly', self::$config['cookie']['http_only']);

            ini_set('session.use_only_cookies', self::$config['only_cookies']);

            if(!empty(trim(self::$config['lifetime']))) {
                ini_set('session.gc_maxlifetime', self::$config['lifetime']);
            }

            session_set_cookie_params(
              self::$config['lifetime'] ,
              self::$config['cookie']['path'],
              self::$config['cookie']['domain'],
              self::$config['cookie']['secure'],
              self::$config['cookie']['http_only']
            );

            if(!empty(trim(self::$config['cookie']['name']))) {
                session_name(self::$config['cookie']['name']);
            }

            if(self::$config['driver'] == 'file') {
              $handler = new SessionHandlerFile();
            }

            if(isset($handler)) {
              session_set_save_handler($handler,true);
              register_shutdown_function('session_write_close');
            }

            if (!isset($_SESSION)) {
                session_start();
                $this->set('session_hash', $this->hash());
            } else {
                if ($this->get('session_hash') != $this->hash()) {

                    $this->destroy();
                }
            }
        }
    }


    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value)
    {
        if (is_callable($value)) {
            return $this->set($key, call_user_func($value, $this));
        } else {
            $_SESSION[ $key ] = $value;
            @session_regenerate_id(session_id());
        }
    }


    /**
     * @return string
     */
    private function hash():String
    {
        return sha1(
          md5(@$_SERVER['REMOTE_ADDR'] . self::ENC_KEY . @$_SERVER['HTTP_USER_AGENT'])
        );
    }


    /**
     * @param $key
     * @return bool
     */

    public function get($key)
    {
        if (is_callable($key)) {
            return $this->get(call_user_func($key, $this));
        } else {
            return $_SESSION[$key] ?? false;
        }
        return false;
    }


    /**
     * @param array $data
     */
    public function set_userdata( array $data = [] )
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }


    /**
     * @return array
     */
    public function userdata():array
    {
        return  $_SESSION;
    }



    /**
     * @param $key
     * @return bool
     */
    public function has($key ):Bool
    {
        return isset($_SESSION[ $key ]);
    }


    /**
     * @param $key
     */
    public function delete($key)
    {
        if (is_callable($key)) {
            $this->delete(call_user_func($key, $this));
        } else {
            if (is_array($key)) {
                foreach ($key as  $value) {
                    $this->delete($value);
                }
            } else {
                if (isset($_SESSION[ $key ])) {
                    unset($_SESSION[ $key ]);
                }
            }
        }
    }



    public function path($path = null)
    {
      $cookie_params = session_get_cookie_params();

      if(is_null($path)) {
        return $cookie_params['path'];
      }

      session_set_cookie_params($cookie_params['lifetime'],$path);

      return $this;
    }


    public function domain($domain = null)
    {
      $cookie_params = session_get_cookie_params();

      if(is_null($domain)) {
        return $cookie_params['domain'];
      }

      session_set_cookie_params(
        $cookie_params['lifetime'],
        $cookie_params['path'],
        $domain
      );

      return $this;
    }



    public function __get($key)
    {
      return $this->get($key);
    }


    public function __set($key,$value)
    {
      return $this->set($key,$value);
    }


    public function __isset($key)
    {
      return $this->has($key);
    }


    public function __call($method,$args)
    {
      $value = $args[0] ?? null;
      return is_null($value)
             ? $this->get($method)
             : $this->set($method,$value);
    }


    public static function __callStatic($method,$args)
    {
      return (new static)->__call($method,$args);
    }


    public function __toString()
    {
      return "Session library";
    }



    public function destroy()
    {
        $_SESSION = [];
        session_destroy();
    }




}
