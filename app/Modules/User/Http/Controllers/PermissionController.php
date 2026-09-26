<?php

namespace App\Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controller;
use App\Modules\User\Entities\Role;

class PermissionController extends Controller
{

    public function getPermission()
    {
        $data = Role::paginate(20);
        return view('admin.user.permission.index', compact('data'));

    }
    public function getAddPermission()
    {
        return view('admin.user.permission.add');

    }
    public function postAddPermission(Request $request)
    {

        $role = new Role();
        $role->name = $request->get('name');
        $role->permission = serialize($request['access'] + ['fullAccess' => 0]);
        $role->save();
        if ($role->save()) {
            return redirect()->route('admin.permission.index', ['page' => $request->get('fallback_page',1)])

            ->with('success', 'آیتم جدید اضافه شد.');
        }
    }
    public function getEditPermission($id)
    {
        $data = Role::findorfail($id);
        return view('admin.user.permission.edit', compact('data'));
    }
    public function postEditPermission($id, Request $request)
    {
        $role = Role::find($id);
        $role->name = $request->get('name');
        $permissions = $request['access'];
        array_push($permissions, "admin.dashboard");
        $role->permission = serialize($permissions);
        $role->save();
        if ($role->save()) {
            return redirect()->route('admin.permission.index', ['page' => $request->get('fallback_page',1)])
                ->with('success', 'آیتم مورد نظر با موفقیت ویرایش شد.');
        }

    }
    public function getDeletePermission($id)
    {
        $role = Role::find($id);
        $serialized_array = serialize($role);
        Role::destroy($id);
        return Redirect::back()->with('success', 'آیتم موردنظر با موفقیت حذف شد');
    }

}
