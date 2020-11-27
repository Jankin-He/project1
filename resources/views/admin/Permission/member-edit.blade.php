<!DOCTYPE html>
<html class="x-admin-sm">
    
    <head>
        <meta charset="UTF-8">
        <title>用户添加页</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        @include('admin.public.styles')
        @include('admin.public.script')
        <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
        <!--[if lt IE 9]>
            <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
            <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="layui-fluid">
            <div class="layui-row">
                <form class="layui-form">
                    <div class="layui-form-item">
                        <div class="layui-form-item">
                            <label for="L_email" class="layui-form-label">
                                <span class="x-red">*</span>权限名称</label>
                            <div class="layui-input-inline">
                                <input type="hidden" name="uid" value="{{$permission->id}}"/>
                                <input type="text" id="L_prename" value="{{$permission->per_name}}" name="per_name" required="" lay-verify="nikename" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="L_email" class="layui-form-label">
                                <span class="x-red">*</span>权限路由</label>
                            <div class="layui-input-inline">
                                <input type="text" id="L_perurl" style="width: 300px" value="{{$permission->per_url}}" name="per_url" required="" lay-verify="nikeweb" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="L_repass" class="layui-form-label"></label>
                            <button class="layui-btn" lay-filter="edit" lay-submit="">修改</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>layui.use(['form', 'layer','jquery'],
            function() {
                $ = layui.jquery;
                var form = layui.form,
                layer = layui.layer;

                //自定义验证规则
                form.verify({
                    nikename: function(value) {
                        if (value.length < 1) {
                            return '权限名至少得1个字符啊';
                        }
                    },
                    nikeweb: [/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(([A-Za-z0-9-~]+).)+([A-Za-z0-9-~\\/])+$/,'权限路由必须是网址'],
                    // pass: [/(.+){6,12}$/, '密码必须6到12位'],
                    // repass: function(value) {
                    //     if ($('#L_pass').val() != $('#L_repass').val()) {
                    //         return '两次密码不一致';
                    //     }
                    // }
                });

                //监听提交
                form.on('submit(edit)',
                function(data) {
                    //console.log(data);
                    //发异步，把数据提交给php

                    var uid=$("input[name='uid']").val();
                    $.ajax({
                        type:'PUT',
                        url:'/admin/permission/'+uid,
                        dataType:'json',
                        headers:{
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:data.field,
                        success:function(data){
                            //弹层提示提交成功，并刷新父页面
                            // console.log(data);
                            if(data.status==0){
                                layer.alert(data.message,{icon:6},function(){
                                    parent.location.reload(true);
                                });
                            }else{
                                layer.alert(data.message,{icon:5});
                            }
                        },
                        error:function(){
                            //错误信息
                        }
                    })





                    /* layer.alert("增加成功", {
                        icon: 6
                    },
                    function() {
                        //关闭当前frame
                        xadmin.close();

                        // 可以对父窗口进行刷新 
                        xadmin.father_reload();
                    }); */
                    return false;
                });

            });</script>
        <script>var _hmt = _hmt || []; (function() {
                var hm = document.createElement("script");
                hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();</script>
    </body>

</html>