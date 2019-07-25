<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Role extends Common
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
    	$arr=Db::query("select id,name,description from role ");
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($arr);
    	die;
    }
    public function permissionShow()
    {
    	$rbac= new Rbac();
    	$arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");
    	// var_dump($arr);
    	$newarr=[];
    	foreach ($arr as $key => $value) {
    		// $newarr[$value['category_id']]=$value['p_c_name'];//二维数组
    		$newarr[$value['p_c_name']][]=$value;//三维数组
    	}
    	$json=['code'=>'0','status'=>'ok','data'=>$newarr];
    	return json($json);
    }

    public function mypermissionShow()
    {
    	$id=Request::get('id');
    	$rbac= new Rbac();
    	$arr=Db::query("select permission_id from role_permission where role_id='$id'");
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	return json($json);
    }


    public function addAction()
    {
    	$data=Request::post();
        $validate = new \app\admin\validate\Role;
         if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            return json($arr);
        }

        $rbac= new Rbac();
    	// $id=explode(",", $data['id']);
    	$id=$data['id'];
    	$name=$data['name'];
    	$description=$data['description'];
    	// var_dump($arr);
    	$bb=Db::query("select * from role where name='$name'");
    	// var_dump($bb);
    	unset($data['__token__']);
    	if(empty($bb)){
    	$arr=$rbac->createRole([
    		'name' => $name,
    		'description' => $description,
    		'status' => 1
			], $id);
			$json=['code'=>'0','status'=>'ok','data'=>$arr];
    		return json($json);
    	}else{
    		$json=['code'=>'1','status'=>'error','data'=>'名字不能重复'];
    		return json($json);
    	}
    }

   	public function updateAction()
    {
    	$data=Request::post();
    	$validate = new \app\admin\validate\Role;
    	    if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
           }
        $rbac= new Rbac();
    	$name=$data['name'];
    	$id=$data['id'];
    	$permission_id=$data['permission_id'];
    	$permission_id=explode(',',$permission_id);
    	unset($data['permission_id']);
    	$arr=Db::query("select * from role where name='$name'");
    	unset($data['__token__']);
    	if(empty($arr)||!empty($arr)&&$arr[0]['id']==$id){
    		$arr=Db::table('role')->update($data);
    		

			$arr=Db::query("delete from role_permission where role_id='$id'");
				
			foreach ($permission_id as $key => $value) {
				$arr=Db::query("insert into `role_permission` (`role_id`,`permission_id`) values ('$id','$value')");
					}
					$arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
				

    	}else{

    		$arr=['code'=>'1','status'=>'erroe','data'=>'重名了'];
			
    	}
		return json($arr);
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
        $rbac= new Rbac();
    	$rbac->delRole([$data['id']]);
		$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo $json=json_encode($arr);
        die;
    	
    }

    public function del()
    {
        $id=Request::post();
        $validate = new \app\admin\validate\Permissioncate1;
    	$arr=explode(",", $id['id']);       
    	if (empty($arr)) {
    		$arr=['code'=>'1','status'=>'error','data'=>'请至少勾选一个'];
    		echo $json=json_encode($arr);
    		die;
    	}elseif (!$validate->check($id)) {
            $arr=['code'=>'302','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
            
    		$rbac= new Rbac();
    		for ($i=0; $i < count($arr); $i++) { 
    		$id=$arr[$i];
    		$rbac->delRole($id);
    	}   	
    		$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
			echo $json=json_encode($arr);
    		die;
    }
}