<?php

namespace App\Http\Controllers;

use App\Helpers\Commons;
use App\Models\Equation;
use Exception;
use Illuminate\Http\Request;

class EquationsController extends Controller
{
    public function equations()
    {
        $data = self::getEquationsDataArray();
        return view('equations', $data);
    }

    public function equation($id)
    {
        $data = self::getEquationsDataArray($id);
        return view('equations', $data);
    }

    public function addEquation(Request $request)
    {
        $request->validate([
            "name"  =>  "required|unique:equations,name",
            "expression"  =>  "required"
        ]);
        Equation::newEquation($request->name, $request->expression, self::getMapsArrayFromMapsInput($request));
        return redirect()->action([self::class, 'equations']);
    }

    public function updateEquation($id, Request $request)
    {
        /** @var Equation */
        $equation = Equation::findOrFail($id);
        $request->validate([
            "name"  =>  "required|unique:equations,name," . $equation->id
        ]);
        $equation->updateInfo($request->name, $request->expression, self::getMapsArrayFromMapsInput($request));
        return redirect()->action([self::class, 'equations']);
    }

    public function delete($id)
    {
        /** @var Equation */
        $equation = Equation::findOrFail($id);
        try {
            $equation->maps()->delete();
            $equation->delete();
        } catch (Exception $e) {
            report($e);
        }
        return redirect()->action([self::class, 'equations']);
    }


    private static function getEquationsDataArray($id = null)
    {
        $data = Commons::getMainDataArray();
        $data['items'] = Equation::withMapping()->get();
        $data['title'] = "Equations";
        $data['subTitle'] = "Manage, Add and Delete Equations";
        $data['formTitle'] = "Add Equation";
        $data['cols'] = ['Name', 'Expression', 'Edit'];
        $data['atts'] = [
            'name', 'expression', ['edit' => ['url' => 'equations/', 'att' => 'id']]
        ];

        if ($id != null) {
            $data['equation'] = Equation::withMapping()->findOrFail($id);
            $data['deleteURL'] = url('equations/delete/' . $id);
            $data['formTitle'] = "Edit " . $data['equation']->name;
        }
        return $data;
    }

    private static function getMapsArrayFromMapsInput($inputRequest): array
    {
        if ($inputRequest->from == null) return [];
        $ret = array();
        foreach ($inputRequest->from as $key => $from) {
            array_push($ret, [
                "from"  =>  $from,
                "to"  =>  $inputRequest->to[$key],
                "value"  =>  $inputRequest->value[$key],
            ]);
        }
        return $ret;
    }
}
