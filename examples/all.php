<?php

$start = microtime(true);

require_once __DIR__.'/bootstrap.php';


use TT\Session\Session;



$session = new Session();

$session->set("name","Session Library");

echo $session->get("name");

echo "<br />";

//------------Helper Example----------------------

//Set Session

session("Helper","Helper function example");
// Or session()->set("example","item");
// Or session()->example = "item";

// Set callback

$session->set("Callback",function($session){
  return "Callback:".$session->get("Helper");
});

//Get callback

echo $session->get(function($session){
  return "Callback";
});

echo "<br />";

//Get Session

echo session("Helper");
// Or session()->get("Helper");
// Or session()->Helper;

echo "<br />";

// Delete session

$session->delete("Helper");

$session->delete(function($session){
  return "Callback";
});

//Set path

/*
  $session->path('/yourpath')->set("mypath",",mypathdata");
*/


// Set Domain

/*
  $session->path('/yourdomain')->set("mydomain",",mydomaindata");
*/


//------------------- Advanced-------------------

//Set session
Session::name("Samir"); // Set session


//Get session

echo Session::name(); // print Samir


//----------------DEBUG-------------------
echo "<br /><br />---------------------------------------------<br />";

$finish = microtime( true);

echo '<b style="color:green">All prosess execute time:</b>
      <font color="red">'
         .number_format(($finish - $start)* 1000, 2).
     '</font> ms';
