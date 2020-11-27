<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;
use App\Model\User;
use App\Model\Role;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 获取用户列表页
     *
     * @return \Illuminate\Http\Response
     */

     //获取授权页面
    public function auth($id){

        //获取当前角色
        $user=User::find($id);
        //获取所有的权限列表
        $role=Role::get();

        //获取当前角色拥有的权限
        $own_role=$user->role;
        // dd($own_perm);
        //角色拥有的权限的id
        $own_roles=[];
        foreach($own_role as $v){
            $own_roles[] = $v->id;
        }
        return view('admin.user.auth',compact('user','role','own_roles'));
    }

    //处理授权
    public function doAuth(Request $request)
    {   
        $input=$request->except('_token');
        // dd($input);

        //先删除当前用户已有角色
        DB::table('user_role')->where('user_id',$input['user_id'])->delete();
        //添加新授予的权限
        if(!empty($input['role_id'])){
            foreach($input['role_id'] as $v){
                DB::table('user_role')->insert(['user_id'=>$input['user_id'],'role_id'=>$v]);
            }
        }
        return redirect('admin/user');
    }


    public function index(Request $request)
    {
        //1.获取提交的请求参数
        // $input=$request->all();
        // dd($input);
        $user=User::orderBy('user_id','asc')
        ->where(function($query)use($request){
            $username=$request->input('username');
            $email=$request->input('email');
            if(!empty($username)){
                $query->where('user_name','like','%'.$username.'%');
            }
            if(!empty($email)){
                $query->where('email','like','%'.$email.'%');
            }
        })
        ->paginate($request->input('num')?$request->input('num'):3);
        // $user=User::paginate(3);
        return view('admin.user.member-list',compact('user','request'));
    }

    /**
     * Show the form for creating a new resource.
     * 返回用户添加页面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * 执行添加操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return 1111;
        //1.接收前台表单提交的数据
        $input=$request->all();
        //2.进行表单验证

        //3.添加到数据库的user表
        $username=$input['username'];
        $pass=Crypt::encrypt($input['pass']);
        $res=User::create(['user_name'=>$username,'user_pass'=>$pass,'email'=>$input['email']]);
        //4.根据添加是否成功，给客户端返回一个json格式的反馈
        if($res){
            $data=[
                'status'=>0,
                'message'=>'添加成功'
            ];
        }else{
            $data=[
                'status'=>1,
                'message'=>'添加失败'
            ];
        }
        return $data;
    }

    /**
     * Display the specified resource.
     * 显示一条数据
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
     * 返回一个修改页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::find($id);
        return view('admin/user/member-edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * 执行修改操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //1.根据id获取要修改的记录
        $user=User::find($id);
        //2.获取要修改成的用户名
        $username=$request->input('user_name');

        $email=$request->input('email');

        $pass=$request->input('pass');

        $user->email=$email;

        $user->user_pass=Crypt::encrypt($pass);

        $user->user_name=$username;


        $res=$user->save();
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
     * 执行删除操作
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::find($id);
        $res=$user->delete();
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
    
    //删除所有选中用户
    public function delAll(Request $request){
        $input=$request->input('ids');
        $res=User::destroy($input);

        // if(is_null($input)){
        //     $data=[
        //         'status'=>1,
        //         'message'=>'为空',
        //     ];
        // }else{
        //     $data=[
        //         'status'=>1,
        //         'message'=>'不为空',
        //     ];
        // }

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
