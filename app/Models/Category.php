<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'user_id',
    'title',
    'color',
    'icon'
  ];

  public function Activities()
  {
    return $this->hasMany(Activity::class, 'category_id', 'id');
  }
}
