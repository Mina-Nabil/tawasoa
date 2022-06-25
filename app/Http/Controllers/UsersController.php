<?php

namespace App\Http\Controllers;

use App\Helpers\Commons;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function users()
    {
        $data = self::getUsersDataArray();
        $data['isPassNeeded'] = true;
        return view('auth.users', $data);
    }

    public function createUser(Request $request)
    {
        $request->validate([
            "username"  =>  "required",
            "fullname"  =>  "required",
            "password"  =>  "required",
            "type"      =>  "required|in:" .  implode(",", User::TYPES)
        ]);
        User::newUser($request->username, $request->fullname, $request->type, $request->password);
        return redirect()->action([UsersController::class, 'users']);
    }

    public function updateUser($id, Request $request)
    {
        /** @var User */
        $user = User::findOrFail($id);
        $request->validate([
            "username"  =>  "required",
            "fullname"  =>  "required",
            "password"  =>  "nullable",
            "type"      =>  "required|in:" .  implode(",", User::TYPES)
        ]);
        $user->updateInfo($request->username, $request->fullname, $request->type, $request->password);
        return redirect()->action([UsersController::class, 'users']);
    }

    public function user($id)
    {
        $data = self::getUsersDataArray($id);
        return view('auth.users', $data);
    }

    public function toggle($id)
    {
        /** @var User */
        $user = User::findOrFail($id);
        $user->setActive(!$user->is_active);
        return redirect()->action([UsersController::class, 'users']);
    }

    public function deactivate($id)
    {
        /** @var User */
        $user = User::findOrFail($id);
        $user->setActive(true);
        return redirect()->action([UsersController::class, 'users']);
    }

    public function delete($id)
    {
        /** @var User */
        $user = User::findOrFail($id);
        try {
            $user->delete();
        } catch (Exception $e) {
            report($e);
        }
        return redirect()->action([UsersController::class, 'users']);
    }


    private static function getUsersDataArray($id = null)
    {
        $data = Commons::getMainDataArray();
        $data['items'] = User::all();
        $data['title'] = "Tawasoa App Users";
        $data['subTitle'] = "Manage, Add and Delete Users data";
        $data['formTitle'] = "Add User";
        $data['cols'] = ['Username', 'Fullname', 'Type', 'Active', 'Edit'];
        $data['atts'] = [
            'username', 'fullname', 'type',
            [
                'toggle' => [
                    "att"   =>  "is_active",
                    "url"   =>  "users/toggle/",
                    "states" => [
                        "1" => "Active",
                        "0" => "Disabled",
                    ],
                    "actions" => [
                        "1" => "disable the User",
                        "0" => "Activate the User",
                    ],
                    "classes" => [
                        "1" => "label-success",
                        "0" => "label-danger",
                    ],
                ]
            ], ['edit' => ['url' => 'users/', 'att' => 'id']]
        ];

        if ($id != null) {
            $data['user'] = User::findOrFail($id);
            $data['isPassNeeded'] = false;
            $data['deleteURL'] = url('users/delete/' . $id);
            $data['formTitle'] = "Edit " . $data['user']->fullname;    
        }
        return $data;
    }
}
