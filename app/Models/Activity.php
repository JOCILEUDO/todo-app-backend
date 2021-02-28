<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'user_id',
    'category_id',
    'title',
    'expire_date',
    'finished',
    'description',
  ];

  public function Category()
  {
    return $this->belongsTo(Category::class, 'category_id', 'id');
  }
}
