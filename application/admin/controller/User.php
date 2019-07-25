<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class User extends Common
{
	public function list()
    {
    	return $this->fetch();
    }
    public function add()
    {
    	return $this->fetch();
    }

    public function show()
    {
    	$rbac= new Rbac();
    	$arr=Db::query("select user.id,user.user_name,user.mobile,role.name from user join user_role on user.id=user_role.user_id join role on user_role.role_id=role.id ");
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($arr);
    	die;
    }

    public function addAction()
    {
    	$data=Request::post();
        $validate = new \app\admin\validate\User;
         if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }
        $rbac= new Rbac();
        $select_id=$data['select_id'];
    	$user_name=$data['user_name'];
    	$password=md5($data['password']);
    	$moblie=$data['moblie'];
    	$bb=Db::query("select * from user where user_name='$user_name'");
    	if (empty($bb)) {
    		$arr=Db::query("insert into `user` (`user_name`,`password`,`mobile`) values ('$user_name','$password','$moblie')");

    		$cc=Db::query("select id from user where user_name='$user_name'");
    		$id=$cc[0]['id'];
    		$arr=Db::query("insert into `user_role` (`user_id`,`role_id`) values ('$id','$select_id')");
    		$json=['code'=>'0','status'=>'ok','data'=>$arr];
    		return json($json);
    	}else{
    		$json=['code'=>'1','status'=>'error','data'=>'名字不能重复'];
    		return json($json);
    	}
    }

    public function delete()
    {
    	$data=Request::post();
    	$id=$data['id'];
        $validate = new \app\admin\validate\Permissioncate1;
        if (!$validate->check($data)) {
            $arr=['code'=>'302','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
        $aa=Db::query("select id from user_role where user_id='$id'");
        $id1=$aa[0]['id'];
        $arr=Db::query("delete from user where id='$id'");
        $arr=Db::query("delete from user_role where id='$id1'");
		$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo $json=json_encode($arr);
        die;
    	
    } 

     public function myShow()
    {
    	$data=Request::post();
    	$id=$data['id'];
    	$rbac= new Rbac();
    	$arr=Db::query("select role_id from user_role where user_id='$id'");
    	$role_id=$arr[0]['role_id'];
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	return json($json);
    }

    public function updateAction()
    {
    	$data=Request::post();
    	// $validate = new \app\admin\validate\User1;
    	//     if (!$validate->check($data)) {
     //        $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
     //        echo $json=json_encode($arr);
     //        die;
     //       }
        $rbac= new Rbac();    	
    	$id=$data['id'];
    	$select_id=$data['select_id'];
    	$user_name=$data['user_name'];
    	$mobile=$data['mobile'];
    	unset($data['__token__']);
    	$arr1=Db::query("select * from user where user_name='$user_name' or mobile='$mobile'");
    	if(empty($arr1)){
    		$arr=Db::query("update user set user_name='$user_name',mobile='$mobile'where id='$id'");
    		// $arr=Db::query("select role_id from user_role where user_id='$id'");
			$arr=Db::query("update user_role set role_id='$select_id' where user_id='$id'");
				
			$arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
				
            return json($arr);
    	}else{

    		foreach ($arr1 as $key => $value) {
               if ($value['id']!=$id) {
                    $arr=['code'=>'1','status'=>'error','data'=>'重名了'];
                    return json($arr);
               }
            }
            $arr=Db::query("update user set user_name='$user_name',mobile='$mobile'where id='$id'");
            $arr=Db::query("update user_role set role_id='$select_id' where user_id='$id'");
                
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                
            return json($arr);
			
    	}
		

    }


}