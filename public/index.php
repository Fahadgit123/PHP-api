<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
#http://localhost:8080/MyApi/public/createuser
#http://localhost:8080/MyApi/public/hello/we
require '../vendor/autoload.php';

 require '../includs/Dboperation.php';

$app = new\Slim\App;

/*
endpoint: createuser
parameters : email, password, name, school
method :POST
*/
$app->post('/createuser', function(Request $request, Response $response){
 if(!haveEmptyParameters(array('email', 'password', 'name', 'school'), $response)){
     
    $request_data = $request->getParseBody();
    
    $email = $request_data['email'];
     $password = $request_data['password'];
     $name = $request_data['name'];
     $school = $request_data['school'];
     $hash_password = password_hash($password, PASSWORD_DEFAULT);

     $db = new Dboperation;
     $result = $db->createuser($email, $hash_password, $name, $school);
     if($result == USER_CREATED){
        $message = array();
        $message['error'] = true;
        $message['message'] = 'user already exist';
       $response->write(json_encode($message));
       return $response
                  ->withHeader('Content-type', 'application/json')
                  ->withStatus(201);

     }elseif($result == USER_FAILURE){
        $message = array();
        $message['error'] = true;
        $message['message'] = 'some error occur';
       $response->write(json_encode($message));
       return $response
                  ->withHeader('Content-type', 'application/json')
                  ->withStatus(422);

     }elseif($result == USER_EXIST){
        $message = array();
        $message['error'] = true;
        $message['message'] = 'user already exist';
       $response->write(json_encode($message));
       return $response
                  ->withHeader('Content-type', 'application/json')
                  ->withStatus(422);
     }
 }
 return $response
                  ->withHeader('Content-type', 'application/json')
                  ->withStatus(422);
});
function haveEmptyParameters($required_params, $response){
  $error = false;
  $error_params = '';
  $request_params = $_REQUEST;
  
  foreach($required_params as $param){
      if(!isset($request_params[$param]) || strlen($request_params[$param])<= 0 ){
          $error = true;
          $error_params = $param . ', ';
      }
  }
  if($error){
      $error_detail = array();
      $error_detail['error'] = true;
      $error_detail['message'] = 'Required parameters' . substr($error_params, 0, -2) . 'are missing or empty';
      $response->write(json_encode($error_detail));
  }
  return $error;
}

$app->run();