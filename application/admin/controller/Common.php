<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Session;
class Common extends Controller
{
    function initialize(){
    $name=Session::get('name');
    if ($name) {
    	$this->assign("name",$name);
	    }else{
	    	$this->error("登陆错误",'Login/login');
	    }
    
    }

    public function commonToken()
    {
        $token = $this->request->token('__token__', 'sha1');
       	$arr=['token'=>$token];
       	echo $json=json_encode($arr); 
        // $this->assign('token', $token);
        // return $this->fetch();
    }
}