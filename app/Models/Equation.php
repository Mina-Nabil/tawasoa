<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equation extends Model
{
    use HasFactory;

    protected $appends = ["safe_name"];

    //accessors
    public function getSafeNameAttribute() 
    {
        return str_replace(" ", "_", $this->name) ;
    }

    //static functions
    public static function newEquation(string $name, string $expression, array $maps): self|false
    {
        $newEq = new self;
        $newEq->name = $name;
        $newEq->expression = $expression;
        $newEq->initials_expression = self::getInitialsExpression($expression);

        try {
            $newEq->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
        $newEq->setMaps($maps);
        return $newEq;
    }

    private static function getInitialsExpression($expression): string
    {
        $initialsExp = $expression;
        $variables = Variable::all();
        foreach ($variables as $var) {
            $initialsExp = str_replace($var->name, $var->initial,  $initialsExp);
        }
        return $initialsExp;
    }

    //functions
    public function updateInfo(string $name, string $expression, array $maps): bool
    {
        $this->name = $name;
        $this->expression = $expression;
        $this->initials_expression = self::getInitialsExpression($expression);
        $this->setMaps($maps);
        try {
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function setMaps(array $maps): bool
    {
        $this->maps()->delete();
        foreach ($maps as $map) {
            echo $this->maps()->create([
                "lower_limit"   =>  $map['from'],
                "higher_limit"   =>  $map['to'],
                "result"   =>  $map['value']
            ]);
        }
        return true;
    }

    //scops
    public function scopeWithMapping($query)
    {
        return $query->with(['maps' => function ($query) {
            $query->orderBy('lower_limit', 'asc');
        }]);
    }

    //relations
    public function maps(): HasMany
    {
        return $this->hasMany(Mappings::class, 'equation_id');
    }
}
