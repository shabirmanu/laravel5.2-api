<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Article
 * @package App
 */
class Article extends Model
{
  /**
   * To prevent mass assignment these field should be filled only.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'body', 'published_at', 'excerpt', 'user_id'
    ];

  /**
   * Fields that should be treated as carbon object.
   *
   * @var array
   */
  protected $dates = ['published_at'];

  /**
   * Query scope function.
   *
   * @param $query
   */
  public function scopePublished($query) {
      $query->where('published_at', '<=', Carbon::now());
    }

  /**
   * Mutator to mutate the value of date field.
   *
   * @param $date
   */
  public function setPublishedAtAttribute($date) {
      $this->attributes['published_at'] = Carbon::parse($date);
    }

  /**
   * Belongs to Relationship; Article belongs to a user.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user() {
      return $this->belongsTo('App\User');
  }

  /**
   * Tags that are associated with Article.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function tags() {
    return $this->belongsToMany('App\Tag', 'articles_tags');
  }

  public function getTagListAttribute() {
    return $this->tags->lists('id')->toArray();
  }

}
