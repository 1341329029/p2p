<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Att extends Model
{
    protected $primaryKey='aid';
    public $timestamps = false;

    protected $fillable =['pid','age','uid','name'];
}
