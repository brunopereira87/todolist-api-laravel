<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
class Category extends Model
{
  protected $table = 'categories';
  use HasFactory;

  // public function countTasks($id){
  //   return Task::where('category_id',$id)->count();
  // }
  public function tasks(){
    return $this->hasMany(Task::class);
  }
}

/*
SELECT * FROM
*/
