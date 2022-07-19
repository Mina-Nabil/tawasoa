<?php

namespace App\Helpers;

use App\Models\Variable;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use MathParser\StdMathParser;
use MathParser\Interpreting\Evaluator;

class Commons
{

    public static function getMainDataArray(): array
    {
        $data = array();
        $data['variables'] = Variable::all();
        $data['searchURL']      = url('search');
        $data['postEntryUrl']   = url('entry');
        return $data;
    }

    public static function calculateExpression(string $expression, array $variables): float|string
    {
        try {
            $parser = new StdMathParser();

            // Generate an abstract syntax tree
            $AST = $parser->parse($expression);


            $evaluator = new Evaluator();
            $evaluator->setVariables($variables);
            $value = $AST->accept($evaluator);
            return $value;
        } catch (Exception $e) {
            report($e);
            return "N/A";
        }
    }

    public static function calculateMapping(float $value, Collection $mappings)
    {
        foreach ($mappings as $map) {
            if ($value >= $map->lower_limit &&  $value <= $map->higher_limit) {
                return $map->result;
            }
        }
        return -1;
    }
}
