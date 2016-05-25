<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Task
 * @package App\Models
 */
class Task extends Model
{

    public $table = 'tasks';
    


    public $fillable = [
        'title',
        'description',
        'priority',
        'assignee'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'priority' => 'string',
        'assignee' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required'
    ];
}
