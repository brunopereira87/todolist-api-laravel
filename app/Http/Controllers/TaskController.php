<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
  private $loggedUser;
  private $user_id;

  public function __construct(){
    $this->middleware('auth:api');
    $this->loggedUser = auth()->user();
    $this->user_id = $this->loggedUser['id'];
  }

  public function create(Request $request){
    $array['error'] = '';

    $name = $request->input('name');
    $description = $request->input('description');
    $deadline = $request->input('deadline');
    $category_id = $request->input('category');

    if($name && $category_id){
      if(!$this->checkValidCategoryId($category_id)) {
        $array['error'] = "Categoria inválida";
        return $array;
      }

      if($deadline && strtotime($deadline) === false ){
        $array['error'] = "Data inválida. Utilize o padrão 'aaaa-mm-dd HH:MM:SS'";
        return $array;
      }

      $newTask = new Task();
      $newTask->name = $name;
      $newTask->description = $description;
      $newTask->deadline = $deadline;
      $newTask->user_id = $this->user_id;
      $newTask->category_id = $category_id;

      $newTask->save();
      $array['task'] = $newTask;
    }
    else{
      $array['error'] = "Os campos nome e categoria são obrigatório ";
    }
    return $array;
  }

  private function checkValidCategoryId($category_id){
    $category = Category::find($category_id);
    return ( $category && ($category['user_id'] == $this->user_id)) ? true : false;
  }
}
