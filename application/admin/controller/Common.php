<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Request;
use think\facade\Session;
use gmars\rbac\Rbac;

class Common extends Controller
{
    function initialize(){
    $name=Session::get('name');
    if ($name) {
    	$this->assign("name",$name);
	    }else{
	    	$this->error("登陆错误",'Login/login');
	    }
        
        $module=Request::module();
        $class=Request::controller();
        $action=Request::action();
        $arr_class=['Permissioncate','Permission','Role','User'];
        $arr_action=['show','updateaction','delete','addaction'];
    if (in_array($class, $arr_class)) {
        if (in_array($action,$arr_action)) {
            $str="$module/$class/$action";

            $str=strtolower($str);

            $rbac= new Rbac(); 
            $bool=$rbac->can($str);
            // echo $str;
            // var_dump($bool);
            // die;
            // $bool=$rbac->can("$module/$class/$action");
            if (!$bool) {
                header("Content-Type:application/json");
                $arr=['code'=>'10001','status'=>'error','data'=>'没有权限'];
                echo json_encode($arr);
                die;
            } 
        }
    }
        // var_dump($bool);

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