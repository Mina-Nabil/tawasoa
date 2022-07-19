<?php

namespace App\Http\Controllers;

use App\Helpers\Commons;
use App\Models\Entry;
use App\Models\Equation;
use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntriesController extends Controller
{
    public function entries()
    {
        $data = self::getEntriesArray(false);
        return view('entries', $data);
    }

    public function summary()
    {
        $data = self::getEntriesArray();
        return view('entries', $data);
    }

    public function rawdata()
    {
        $data = self::getRawDataArray(false);
        // dd($data);
        return view('rawdata', $data);
    }

    public function mainRawdata()
    {
        $data = self::getRawDataArray(true);
        return view('rawdata', $data);
    }

    public function search(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(401);
        }
        $request->validate([
            "searchVal"    =>  "required"
        ]);
        $data = self::getEntriesArray(false, $request->searchVal);

        return view('entries', $data);
    }

    public function entry($id)
    {
        $data = Commons::getMainDataArray();
        $data['entry']    =   Entry::with('variables')->findOrFail($id);
        $data['varsTitle'] = $data['entry']->company_name . " Variables";
        $data['varsSubtitle'] = "Check {$data['entry']->company_name}'s Entries ";
        $data['varsItems'] = $data['entry']->variables;
        $data['varsCols'] = ['Name', 'Value'];
        $data['varsAtts'] = [
            'title',
            ['foreign'  =>  ['att' => 'value', 'rel'    =>  'pivot']]
        ];
        $varsArray = array();
        foreach ($data['varsItems'] as $var) {
            $varsArray[$var->initial] = $var->pivot->value;
        }
        $data['equations'] = Equation::withMapping()->get();
        foreach ($data['equations'] as $eq) {
            $eq->result = Commons::calculateExpression($eq->initials_expression, $varsArray);
            if (is_float($eq->result)) {
                $mappedValue = Commons::calculateMapping($eq->result, $eq->maps);
            } else {
                $mappedValue = -1;
            }
            $eq->mapped = $mappedValue == -1 ? "N/A" : number_format($mappedValue);
        }

        $data['deleteURL'] = url('entry/delete/' . $data['entry']->id);
        $data['setAsMain'] = url('entry/main/' . $data['entry']->id);
        $data['setAsNotMain'] = url('entry/notmain/' . $data['entry']->id);
        return view('entry', $data);
    }

    public function submitEntry(Request $request)
    {
        $request->validate([
            "companyName"  =>  "required",
            "var"   =>  "present|array"
        ]);

        $entry = Entry::newEntry($request->companyName, $request->var);
        if ($entry) {
            return redirect()->action([EntriesController::class, 'entry'], [$entry->id]);
        } else {
            return abort(500);
        }
    }

    public function setAsMain($id)
    {
        $entry =   Entry::findOrFail($id);
        $entry->setMain(true);

        return redirect()->action([EntriesController::class, 'summary']);
    }

    public function setAsNotMain($id)
    {
        $entry =   Entry::findOrFail($id);
        $entry->setMain(false);

        return redirect()->action([EntriesController::class, 'summary']);
    }

    public function delete($id)
    {
        $entry =   Entry::findOrFail($id);
        $entry->variables()->sync([]);
        $entry->delete();


        return redirect()->action([EntriesController::class, 'summary']);
    }

    private static function getRawDataArray($isSummary = true)
    {
        $data = Commons::getMainDataArray();
        if($isSummary){
            $data['rawdata'] = Entry::summary()->get();
            $data['title'] = "Raw Data - Main Entries";
            $data['subtitle'] = "Check all main raw entries";
        } else {
            $data['rawdata'] = Entry::limit(600)->get();
            $data['title'] = "Raw Data - All Entries";
            $data['subtitle'] = "Check all raw entries";
        }
        return $data;
    }

    private function getEntriesArray($isSummary = true, $searchText = null): array
    {
        $data = Commons::getMainDataArray();
        $data['equations']  =   Equation::withMapping()->get();
        if ($searchText == null) {
            if ($isSummary) {
                $data['title'] = "Summary";
                $data['subtitle'] = "Check all main entries";
                $data['entries'] = Entry::summary()->get();
            } else {
                $data['title'] = "Entries History";
                $data['subtitle'] = "Check all entries history";
                $data['entries'] = Entry::with('variables')->get();
            }
        } else {
            $data['title'] = "Search";
            $data['subtitle'] = "Showing search results for " . $searchText;
            $data['entries'] = Entry::searchBy($searchText)->get();
        }
        $data['equations']  =   Equation::all();

        foreach ($data['entries'] as $entry) {

            $varsArray = array();
            foreach ($entry->variables as $var) {
                $varsArray[$var->initial] = $var->pivot->value;
            }
            foreach ($data['equations'] as $eq) {
                $value = Commons::calculateExpression($eq->initials_expression, $varsArray);
                if (is_float($value)) {
                    $mappedValue = Commons::calculateMapping($value, $eq->maps);
                } else {
                    $mappedValue = -1;
                }

                $entry->{$eq->safe_name} = [
                    "map"   =>  $mappedValue == -1 ? "N/A" : number_format($mappedValue),
                    "value"   =>  is_float($value) ? number_format($value, 2) : "N/A"
                ];
            }
        }
        return $data;
    }
}
