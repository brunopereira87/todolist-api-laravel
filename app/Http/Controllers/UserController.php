<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
  private $loggedUser;

  public function __construct(){
    $this->middleware('auth:api');
    $this->loggedUser = auth()->user();
  }

  public function read(){
    return ['user'=> $this->loggedUser];
  }
  public function update(Request $request){
    $array['error'] = '';

    $name = $request->input('name');
    $password = $request->input('password');
    $passwordConfirm = $request->input('passwordConfirm');

    $user = User::find($this->loggedUser['id']);
    if($name){
      $user->name = $name;
    }

    if($password || $passwordConfirm){
      if($password === $passwordConfirm){
        $user->password = password_hash($password,PASSWORD_DEFAULT);
      }
      else {
        $array['error'] = 'As duas senhas devem ser iguais';
        return $array;
      }
    }

    $user->save();
    $array['user'] = $user;

    return $array;
  }
}
