<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Entry extends Model
{
    use HasFactory;
    protected $with = ['variables'];

    /////public static queries
    public static function newEntry($name, $variablesArr): self|false
    {
        $newEntry = new self;
        $newEntry->company_name = $name;
        $newEntry->user_id = Auth::user()->id;
        try {
            $newEntry->save();
            $varArr = array();
            foreach ($variablesArr as $id => $val) {
                $varArr[$id] =  ['value'    =>  $val];
            }
            $newEntry->variables()->sync($varArr);
            return $newEntry;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    //model functions
    public function setMain(bool $bool)
    {
        $this->is_main = $bool;
        try {
            $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    ///scopes
    public function scopeSummary($query)
    {
        return $query->where('is_main', true);
    }
 
    public function scopeSearchBy($query, $text)
    {
        return $query->where('company_name', 'LIKE', "%{$text}%");
    }


    //////relations
    public function variables(): BelongsToMany
    {
        return $this->belongsToMany(Variable::class, 'entry_variable')->withPivot('value');
    }
}
