<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag
 * @package App
 */
class Tag extends Model
{
    //
  /**
   * Fillable fields
   *
   * @var array
   */
  protected $fillable = ['name'];


  /**
   * Articles that are associated with this tag.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function articles() {
    return $this->belongsToMany('App\Article', 'articles_tags');
  }
}
