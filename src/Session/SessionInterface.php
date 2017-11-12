<?php namespace TT\Session;

/**
 * @author  Samir Rustamov <rustemovv96@gmail.com>
 * @link 	https://github.com/SamirRustamov/Session
 */





interface SessionInterface
{




    public function __construct();


    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value);


    /**
     * @param $key
     * @return bool
     */

    public function get($key);


    /**
     * @param array $data
     */
    public function set_userdata(array $data = []);


    /**
     * @return array
     */
    public function userdata():array;

    /**
     * @param $key
     * @return bool
     */
    public function has($key):Bool;


    /**
     * @param $key
     */
    public function delete($key);




    public function path($path = null);




    public function domain($domain = null);




    public function destroy();
}
