<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmedTypeGinfoTranslation extends Model
{
    protected $fillable = ['title', 'content'];
    public $timestamps = false;
}