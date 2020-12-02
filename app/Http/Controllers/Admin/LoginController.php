<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Model\User;


class LoginController extends Controller
{
    //后台登录页
    public function login(){
        return view('admin.login');
    }

    //处理用户登录的方法
    public function dologin(Request $request){
        //1.接受表单提交的数据
        $input=$request->except('keys','_token');
        //2.进行表单验证
        $rule=[
            'username'=>'required|between:2,18',
            'password'=>'required|between:4,18|alpha_num',
            // 'captcha'=>'required|captcha',
        ];
        $msg=[
            'username.required'=>'用户不能为空',
            'username.between'=>'用户名长度必须在2-18位之间',
            'password.required'=>'密码不能为空',
            'password.between'=>'密码长度必须在4-18位之间',
            'password.alpha_dash'=>'密码必须是数字或字母',
            'captcha.required'=>'请输入验证码',
            'captcha.captcha'=>'验证码错误',
        ];
        $validator=Validator::make($input,$rule,$msg);
        /* if($validator->fails()){
            return redirect('/admin/login')->withErrors($validator)->withInput();
        }
        //3.验证是否有此用户（用户名 密码 验证码）
        $user=User::where('user_name',$input['username'])->first();

        if(!$user){
            return redirect('/admin/login')->with('errors','用户不存在');
        }

        if($input['password']!=Crypt::decrypt($user->user_pass)){
            return redirect('/admin/login')->with('errors','密码输入错误');
        } */
        $user=User::where('user_name',$input['username'])->first();
        if($validator->fails()){
            return redirect('/admin/login')->withErrors($validator)->withInput();
        }
        if(!$user||$input['password']!=Crypt::decrypt($user->user_pass)){
            $validator->errors()->add('errors','用户名或密码输入错误');
            return redirect('/admin/login')->withErrors($validator)->withInput();
        }
        $rule=[
            'username'=>'required|between:2,18',
            'password'=>'required|between:6,18|alpha_num',
            'captcha'=>'required|captcha',
        ];
        $validator=Validator::make($input,$rule,$msg);
        if($validator->fails()){
            return redirect('/admin/login')->withErrors($validator)->withInput();
        }
        //4.保存用户信息到session中
        session()->put('user',$user);
        //5.跳转到后台首页
        return redirect('/admin/index');
    }
    //加密算法
    public function jiami(){
        //1.md5加密，生成一个32位的字符串
        /* $str='123456';
        return md5($str); */
        //2.哈希加密
        /* $str='123456';
        $hash=Hash::make($str);
        if(Hash::check($str,$hash)){
            return '密码正确';
        }else{
            return '密码错误';
        } */
        //3.crypt加密
        $str='123456';
        // $crypt_str='eyJpdiI6IkdXOHBuZ3BDSS9wNU01MWNVNEJDOUE9PSIsInZhbHVlIjoiaFZsQmJLaVRjRllleHpUVDJNRHhyQT09IiwibWFjIjoiMWIwNzVjOWRmY2EzOGU3ZTQ1MmRkMjYzZTk4MGY3YzBiODI2MjFjNTU5ODQwNGM3NzZjYWE3N2JlNWU2NWFmNyJ9';
        // $crypt_str=Crypt::encrypt($str);
        // if(Crypt::decrypt($crypt_str)==$str){
        //     return '密码正确';
        // }
        //return $crypt_str;
        return Crypt::encrypt($str);
    }

    //后台首页
    public function index(){
        return view('admin.index');
    }

    //后台欢迎页
    public function welcome(){
        return  view('admin/welcome');
    }

    //退出登录
    public function logout(){
        //清空session中的用户信息
        session()->flush();
        //跳转到登录页面
        return redirect('admin/login');
    }

    //后台统计页面
    public function welcome1(){
        return view('admin.welcome1');
    }

    //后台会员列表（静态）
    public function memberlist(){
        return  view('admin/user/member-list');
    }
    //后台会员列表（动态）
    public function memberlist1(){
        return view('admin/user/member-list1');
    }
    //后台用户添加
    public function memberadd(){
        return view('admin/user/member-add');
    }
    //图标对应字体
    public function unicode(){
        return view('admin/unicode');
    }
    //没有权限对应的跳转
    public function noaccess(){
        return view('errors/noaccess');
    }
}
