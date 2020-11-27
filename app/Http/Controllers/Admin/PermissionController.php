<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //1.获取所有的角色数据
        $permission=Permission::get();
        //2.返回角色视图
        return view('admin.permission.member-list',compact('permission'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('admin.permission.member-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission=Permission::find($id);
        return view('admin/permission/member-edit',compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //1.根据id获取要修改的记录
        $permission=Permission::find($id);
        //2.获取要修改成的角色名

        $permissionname=$request->input('per_name');

        $permissionurl=$request->input('per_url');

        $permission->per_name=$permissionname;

        $permission->per_url=$permissionurl;

        $res=$permission->save();
        if($res){
            $data=[
                'status'=>0,
                'message'=>'修改成功',
            ];
        }else{
            $data=[
                'status'=>1,
                'message'=>'修改失败',
            ];
        }
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission=Permission::find($id);
        $permission=$permission->delete();
        if($res){
            $data=[
                'status'=>0,
                'message'=>'删除成功',
            ];
        }else{
            $data=[
                'status'=>1,
                'message'=>'删除失败',
            ];
        }
        return $data;
    }
}
