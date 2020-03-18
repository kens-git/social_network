<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model {
    protected $table = 'todo';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'priority'
    ];

    public static $priority = [
        'completed' => 'completed',
        'none' => 'none',
        'low' => 'low',
        'medium' => 'medium',
        'high' => 'high',
        'critical' => 'critical'
    ];
}
