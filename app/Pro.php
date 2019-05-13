<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pro extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'pid';
    public $timestamps = false;

    protected $fillable = ['money','mobile','uid','name','pubtime'];
}
