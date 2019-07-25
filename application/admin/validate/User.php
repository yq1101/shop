<?php

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'user_name'  => 'require|max:50|min:1|token',
        'password'  => 'require|max:50|min:1',
        'moblie'  => 'require|number|length:11',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'user_name.require' => '名称必须',
        'name.max'     => '名称最多不能超过50个字符',
        'name.min'     => '名称最少不能低于1个字符',
        'password.require' => '密码必须',
        'moblie.require' => '手机号必须',
        'moblie.length' =>'手机号必须11位'
    ];
}