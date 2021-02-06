<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
  private $loggedUser;
  private $userId;
  protected $table = 'categories';

  public function __construct(){
    $this->middleware('auth:api');
    $this->loggedUser = auth()->user();
  }


  public function create(Request $request){
    $array = [];

    $name = $request->input('name');
    $icon = $request->input('icon');

    if(!$name){
      $array['error'] = 'Envie o nome da categoria';
      return response()->json($array,400);
    }

    $newCategory = new Category();
    $newCategory->user_id =  $this->loggedUser['id'];
    $newCategory->name = $name;
    $newCategory->icon = $icon;
    $newCategory->save();
    $array['category'] = [
      'id'=> $newCategory->id,
      'name'=> $newCategory->name,
      'icon'=> $newCategory->icon,
      'tasks'=> 0
    ];

    return response()->json($array,201);
  }

  public function read($id = null){
    if($id){
      $category = Category::withCount('tasks')->find($id);

      if(!Gate::allows('manipulate-category',$category)){
        $array['error'] = 'Categoria não encontrada';
        return response()->json($array,404);
      }
      $array['category'] = $category;
    }
    else{
      $categories = Category::where('user_id', $this->loggedUser['id'])
      ->withCount('tasks')
      ->get();

      $array['categories'] = $categories;
    }

    return response()->json($array,200);
  }

  public function update(Request $request,$id){
    $array = [];

    $category = Category::find($id);
    $fields = ['name','icon'];
    if(!$category){
      $array['error'] = 'Categoria não encontrada';
      return response()->json($array,404);
    }

    if( $category->user_id !=  $this->loggedUser['id'] ){
      $array['error'] = 'Categoria não encontrada';
      return response()->json($array,404);
    }

    $inputs = Helper::getInputFields($request,$fields);
    Helper::setUpdateFields($category, $inputs);

    $category->save();
    $array['category'] = $category;

    return response()->json($array,200);
  }

  public function delete($id){
    $category =  Category::find($id);

    if( $category->user_id !=  $this->loggedUser['id'] ){
      $array['error'] = 'Categoria não encontrada';
      return response()->json($array,404);
    }

    $category->delete();

    return[];
  }
}
