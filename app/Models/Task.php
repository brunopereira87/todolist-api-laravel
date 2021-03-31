<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Task extends Model
{
  use HasFactory;

  public function category(){
    return $this->belongsTo(Category::class,'category_id');
  }
  public static function paginateTasks($page, $perPage, $where, $whereValue,$sort){
    $array = [];

    $tasks = Task::with('category')->where($where, $whereValue);
    $tasks = self::defineSort($tasks,$sort);
    $tasks = $tasks->offset($perPage * ($page - 1))->limit($perPage)->get();

    $total = Task::where($where,$whereValue)->count();

    $array['tasks'] = $tasks;
    $array['total_tasks'] = $total;
    $array['total_pages'] = ceil($total / $perPage);
    $array['current_page'] = $page;

    return $array;
  }

  private static function defineSort($tasks, $sortString){
    $sortParams = explode(',',$sortString);

    foreach($sortParams as $param){
      $descPos = strpos($param, '-');
      $order = ($descPos === false ) ? 'asc' : 'desc';
      $param = str_replace('-','',$param);
      // echo "$param => $order ___";
      $tasks = $tasks->orderBy($param,$order);
    }

    // exit;
    return $tasks;
  }
}
