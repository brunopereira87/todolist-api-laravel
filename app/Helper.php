<?php

namespace App;

class Helper{
  public static function setUpdateFields(&$item, $inputs){
    foreach($inputs as $key => $input){
      $item[$key] = $input;
    }
  }

  public static function getInputFields($request,$fields){
    $inputs = [];

    foreach($fields as $field){
      $input = $request->input($field);

      if($input){
        $inputs[$field] = $input;
      }
    }

    return $inputs;
  }
}
