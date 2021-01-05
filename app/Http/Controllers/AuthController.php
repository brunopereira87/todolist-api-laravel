<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
  public function __construct(){
    $this->middleware('auth:api',[
      'except' => [
        'login',
        'create',
        'forgot',
        'reset',
        'unauthorized'
      ]
    ]);
  }
  public function login(Request $request){
    $array['error'] = '';

    $email = $request->input('email');
    $password = $request->input('password');

    if(!($email && $password)){
      $array['error'] = 'Por favor, envie o email e a senha';
      return $array;
    }

    $token = auth()->attempt([
      'email' => $email,
      'password' => $password
    ]);

    if(!$token){
      $array['error'] = 'Email e/ou senha incorretos';
      return $array;
    }

    $array['token'] = $token;
    $array['user'] = auth()->user();

    return $array;
  }

  public function forgot(){
    echo 'forgot';
  }

  public function reset(){
    echo 'reset';
  }

  public function create(Request $request){
    $array = ['error'=>''];

    $name = $request->input('name');
    $email = $request->input('email');
    $password = $request->input('password');

    if($name && $email && $password){
      $emailExists = User::where('email', $email)->count();

      if($emailExists !== 0){
        $array['error'] = 'Email já cadastrado';
        return $array;
      }

      $newUser = new User();
      $newUser->name = $name;
      $newUser->email = $email;
      $newUser->password = password_hash($password,PASSWORD_DEFAULT);

      $newUser->save();

      $token = auth()->attempt([
        'email' => $email,
        'password' => $password
      ]);

      if(!$token){
        $array['error'] = 'Erro inesperado';
        return $array;
      }

      $array['token'] = $token;

    }
    else{
      $array['error'] = 'Você não enviou todos os campos';
    }

    return $array;
  }

  public function logged(){
    return['user' => auth()->user()];
  }

  public function logout(){
    auth()->logout();
    return ['error' => ''];
  }

  public function refresh(){
    return ['token' => auth()->refresh()];
  }

  public function unauthorized(){
    return response()->json(['error'=>'Não autorizado'],401);
  }
}
