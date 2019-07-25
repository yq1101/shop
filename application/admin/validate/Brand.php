<?php

namespace app\admin\validate;

use think\Validate;

class Brand extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */ 
    protected $rule = [
        'b_name'  => 'require|max:50|min:1|token',
        'b_description'   => 'require|max:100|min:1',
        'b_file' => 'file',
    ];
    

}
