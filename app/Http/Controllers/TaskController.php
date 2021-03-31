<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Category;
use App\Helper;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
  private $loggedUser;
  private $user_id;

  public function __construct(){
    $this->middleware('auth:api');
    $this->loggedUser = auth()->user();
  }

  public function create(Request $request){
    $array = [];

    $name = $request->input('name');
    $description = $request->input('description');
    $deadline = $request->input('deadline');
    $category_id = $request->input('category_id');

    if($name && $category_id){
      if(!$this->checkValidCategoryId($category_id)) {
        $array['error'] = "Categoria inválida";
        return response()->json($array,400);
      }

      if($deadline && strtotime($deadline) === false ){
        $array['error'] = "Data inválida. Utilize o padrão 'aaaa-mm-dd HH:MM:SS'";
        return response()->json($array,400);
      }

      $newTask = new Task();
      $newTask->name = $name;
      $newTask->description = $description;
      $newTask->deadline = $deadline;
      $newTask->user_id =  $this->loggedUser['id'];
      $newTask->category_id = $category_id;

      $newTask->save();
      $array['task'] = $newTask;
    }
    else{
      $array['error'] = "Os campos nome e categoria são obrigatório ";
      return response()->json($array,400);
    }
    return response()->json($array,201);
  }

  public function read(Request $request, $id = null) {
    $page = intval($request->query('page','1'));
    $perPage = intval($request->query('limit','5'));
    $sort = $request->query('sort','deadline');

    if($id){
      $task = Task::find($id);

      if( $task->user_id !=  $this->loggedUser['id'] ){
        $array['error'] = 'Tarefa não encontrada';
        return response()->json($array,404);
      }

      $array['task'] = $task;
    }
    else{
      $array = Task::paginateTasks($page, $perPage,'user_id', $this->loggedUser['id'],$sort);
    }

    return response()->json($array,200);
  }
  public function readCategoryTasks(Request $request, $category_id = null){
    $page = intval($request->query('page','1'));
    $perPage = intval($request->query('limit','2'));
    $sort = $request->query('sort','deadline');

    if($category_id){
      $category = Category::find($category_id);
      if( $category->user_id !=  $this->loggedUser['id'] ){
        $array['error'] = 'Categoria inválida';
        return response()->json($array,400);
      }

      $array = Task::paginateTasks($page, $perPage,'category_id', $category_id,$sort);;
    }
    else{
      $array['error'] = 'Categoria não enviada';
      return response()->json($array,400);
    }

    return response()->json($array,200);
  }

  public function update(Request $request, $id){
    $array = [];
    $fields = ['name','description','deadline','category_id'];

    $task = Task::find($id);
    if(!$task || ($task->user_id !=  $this->loggedUser['id']) ){
      $array['error'] = 'Tarefa não encontrada';
      return response()->json($array,404);
    }

    $inputs = Helper::getInputFields($request,$fields);

    Helper::setUpdateFields($task, $inputs);
    $task->save();
    $array['task'] = $task;

    return response()->json($array,200);
  }


  public function delete($id){
    $task = Task::find($id);

    if(!$task || ($task->user_id !=  $this->loggedUser['id']) ){
      $array['error'] = 'Tarefa não encontrada';
      return response()->json($array,404);
    }

    $task->delete();

    return [];
  }
  public function done($id){
    $array = [];

    $task = Task::find($id);
    if(!$task || ($task->user_id !=  $this->loggedUser['id']) ){
      $array['error'] = 'Tarefa não encontrada';
      return response()->json($array,404);
    }

    $task->done = $task->done == 1 ? 0 : 1 ;
    $task->save();
    $array['task'] = $task;

    return response()->json($array,200);
  }
  private function checkValidCategoryId($category_id){
    $category = Category::find($category_id);
    return ( $category && ($category['user_id'] ==  $this->loggedUser['id'])) ? true : false;
  }
}
