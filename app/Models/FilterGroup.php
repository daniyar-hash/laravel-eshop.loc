<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FilterGroup extends Model
{
    
    protected $table = 'filter_groups';

    protected $fillable = ['title'];

    public $timestamps = false;

}
