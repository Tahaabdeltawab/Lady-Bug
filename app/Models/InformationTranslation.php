<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformationTranslation extends Model
{
    protected $fillable = ['title', 'content'];
    public $timestamps = false;
}