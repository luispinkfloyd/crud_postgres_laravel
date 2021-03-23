<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    protected $table = 'bases';
    public $timestamps = true;

    protected $fillable = [
         'servidor'
        ,'usuario'
        ,'password'
    ];
}
