<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    use HasFactory;

    protected $appends = ["title"];

    //accessors
    public function getTitleAttribute()
    {
        return ucfirst(str_replace("_", " ", $this->name)) . " ({$this->unit})";
    }

    //model functions
    public function updateInfo(string $name, string $unit): bool
    {
        $name = str_replace(' ', '-', strtolower($name));
        $this->name = $name;
        $this->unit = $unit;
        try {
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    /////static queries
    public static function newVariable(string $name, string $unit): self|false
    {
        $name = str_replace(' ', '_', strtolower($name));
        $newVar = new self;
        $newVar->name = $name;
        $newVar->unit = $unit;
        $newVar->initial = self::getMaxChar();
        try {
            $newVar->save();
            return $newVar;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    private static function getMaxChar(): string
    {
        $maxVar = self::whereRaw("initial = (SELECT MAX(initial) from variables)")->first();
        if ($maxVar) {
            return chr(ord($maxVar->initial) + 1);
        } else return 'a';
    }
}
