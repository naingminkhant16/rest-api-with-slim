<?php

namespace Model;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = "persons";
    protected $fillable = ['name', 'age'];
}
