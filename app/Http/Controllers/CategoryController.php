<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
  private $loggedUser;
  private $userId;
  protected $table = 'categories';

  public function __construct(){
    $this->middleware('auth:api');
    $this->loggedUser = auth()->user();
    $this->userId = $this->loggedUser['id'];
  }


  public function create(Request $request){
    $array['error'] = '';

    $name = $request->input('name');
    $icon = $request->input('icon');

    if(!$name){
      $array['error'] = 'Envie o nome da categoria';
      return $array;
    }

    $newCategory = new Category();
    $newCategory->user_id = $this->userId;
    $newCategory->name = $name;
    $newCategory->icon = $icon;
    $newCategory->save();
    $array['category'] = [
      'id'=> $newCategory->id,
      'name'=> $newCategory->name,
      'icon'=> $newCategory->icon,
    ];

    return $array;
  }

  public function read($id = null){
    if($id){
      $category = Category::find($id);
      if( $category->user_id != $this->userId ){
        $array['error'] = 'Categoria inválida';
        return $array;
      }
      $array['category'] = $category;
    }
    else{
      $array['categories'] = Category::where('user_id',$this->userId)->get();
    }

    return $array;
  }

  public function update(Request $request,$id){
    $array['error'] = '';

    $category = Category::find($id);

    if(!$category){
      $array['error'] = 'Categoria não encontrada';
      return $array;
    }

    if( $category->user_id != $this->userId ){
      $array['error'] = 'Categoria inválida';
      return $array;
    }


    $name = $request->input('name');
    $icon = $request->input('icon');

    if($name){
      $category->name = $name;
    }
    if($icon){
      $category->icon = $icon;
    }

    $category->save();
    $array['category'] = $category;

    return $array;
  }

  public function delete($id){
    $category =  Category::find($id);

    if( $category->user_id != $this->userId ){
      $array['error'] = 'Categoria inválida';
      return $array;
    }

    $category->delete();

    return[];
  }
}
