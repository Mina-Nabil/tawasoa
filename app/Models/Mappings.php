<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mappings extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'equation_map';
    protected $fillable = ['lower_limit', 'higher_limit', 'result'];
}
