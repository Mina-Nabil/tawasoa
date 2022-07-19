<?php

namespace App\Http\Controllers;

use App\Helpers\Commons;
use App\Models\Variable;
use Exception;
use Illuminate\Http\Request;

class VariablesController extends Controller
{
    public function variables()
    {
        $data = self::getVariablesDataArray();
        return view('variables', $data);
    }

    public function variable($id)
    {
        $data = self::getVariablesDataArray($id);
        return view('variables', $data);
    }

    public function addVariable(Request $request)
    {
        $request->validate([
            "name"  =>  "required|unique:variables,name",
            "unit"  =>  "required",
        ]);
        Variable::newVariable($request->name, $request->unit);
        return redirect()->action([self::class, 'variables']);
    }

    public function updateVariable($id, Request $request)
    {
        /** @var Variable */
        $variable = Variable::findOrFail($id);
        $request->validate([
            "name"  =>  "required|unique:variables,name," . $variable->id,
            "unit"  =>  "required",
        ]);
        $variable->updateInfo($request->name, $request->unit);
        return redirect()->action([self::class, 'variables']);
    }

    public function delete($id)
    {
        /** @var Variable */
        $variable = Variable::findOrFail($id);
        try {
            $variable->delete();
        } catch (Exception $e) {
            report($e);
        }
        return redirect()->action([self::class, 'variables']);
    }

    private static function getVariablesDataArray($id = null)
    {
        $data = Commons::getMainDataArray();
        $data['items'] = Variable::all();
        $data['title'] = "Variables";
        $data['subTitle'] = "Manage, Add and Delete Equations Variables";
        $data['formTitle'] = "Add Variable";
        $data['cols'] = ['Name', "Unit", 'Edit'];
        $data['atts'] = [
            'name', 'unit', ['edit' => ['url' => 'variables/', 'att' => 'id']]
        ];

        if ($id != null) {
            $data['variable'] = Variable::findOrFail($id);
            $data['deleteURL'] = url('variables/delete/' . $id);
            $data['formTitle'] = "Edit " . $data['variable']->name;
        }
        return $data;
    }
}
