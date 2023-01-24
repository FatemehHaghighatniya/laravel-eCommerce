<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\role;
use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(){
        $roles=Role::latest()->paginate(20);
        return view ('admin.roles.index',compact('roles'));
    }

    public function create(){
        $permissions=Permission::all();
        return view('admin.roles.create',compact('permissions'));
    }

    public function store(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'name' => 'required',
            'display_name' => 'required'
        ]);
        try {
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'guard_name' => 'web'
            ]);
            $permissions = $request->except('_token', 'name', 'display_name');
            $role->givePermissionTo($permissions);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            alert()->error('مشکل در ایجاد نقش', $ex->getMessage())->persistent('حله');
            return redirect()->back();
        }
        alert()->success('نقش مورد نظر ایجاد شد', 'موفق');
        return redirect()->route('admin.roles.index');


    }




    public function edit( Role $role){
        $permissions=Permission::all();
            return view('admin.roles.edit',compact('role','permissions'));
        }





    public function update(Request $request,Role $role){
//        dd($request->all());
        $request->validate([
            'name'=>'required',
            'display_name'=>'required'
        ]);
        try {
            $role->update([
                'name'=>$request->name,
                'display_name'=>$request->display_name
            ]);
            $permission=$request->except('_token','_method','display_name','name');
            $role->syncPermissions($permission);
          DB::commit();
        }catch(\Exception $ex){
            DB::rollBack();
            alert()->error('ویرایش با خطا مواجه شد',$ex->getMessage())->persistent('حله');
            return redirect()->back();
        }
        alert()->success('نقش مورد نظر ویرایش شد','موفق');
        return redirect()->route('admin.roles.index');
        }

}
