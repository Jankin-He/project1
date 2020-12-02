<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Cate extends Model
{
    //1.关联的数据表
    public $table ='category';
    //2.主键
    protected $primaryKey='cate_id';
    //3.允许批量操作的字段
    // public $fillable='user_name,user_pass,emaill,phone';
    public $guarded='';
    //4.是否维护crated_at和update_at字段
    public $timestamps=false;
}
