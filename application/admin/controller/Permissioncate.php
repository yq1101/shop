<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Permissioncate extends Common
{
    public function list()
    {
    	return $this->fetch();
    }

    public function show()
    {	
    	$rbac= new Rbac();
    	$arr=$rbac->getPermissionCategory([['status', '=', 1]]);
    	echo json_encode($arr);
    }

    public function addAction()
    {
    	$data=Request::post();
    	$validate = new \app\admin\validate\Permissioncate;
    	    if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
    	// $name=Request::post('name');
    	// $description=Request::post('description');
    	$rbac= new Rbac();
    	$aa=$rbac->getPermissionCategory([['name', '=', $data['name']]]);
    		if (empty($aa)) {
	    		$arr=$rbac->savePermissionCategory([
				    'name' => $data['name'],		    
				    'description' => $data['description'],
				    'status' => 1
				]);
				$arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
				echo $json=json_encode($arr);
    		}else{
				$arr=['code'=>'1','status'=>'error','data'=>'分类已经存在'];
				echo $json=json_encode($arr);
    		} 	
    	
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
            
    	for ($i=0; $i < count($arr); $i++) { 
    		$id=$arr[$i];
    		Db::query("delete from permission_category where id=$id");
    	}
    	$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
		echo $json=json_encode($arr);
    }

    public function delete()
    {   
        $data=Request::post();
        $validate = new \app\admin\validate\Permissioncate1;
        if (!$validate->check($data)) {
            $arr=['code'=>'302','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }

    	$id=Request::post('id');
    	$result=Db::query("delete from permission_category where id=$id");
		$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo $json=json_encode($arr);
        die;
    }

    public function updateAction()
    {
        $data=Request::post();
        $validate = new \app\admin\validate\Permissioncate;
            if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
        $rbac= new Rbac();
        $result=$rbac->getPermissionCategory([['name', '=', $data['name']]]);    
        if (empty($result)) {
                Db::table('permission_category')->where
                ('id',$data['id'])->update(['name'=>$data['name'],'description'=>$data['description']]);
                $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                echo $json=json_encode($arr);
        }else{
            if($result[0]['id']!=$data['id']){
                $arr=['code'=>'1','status'=>'error','data'=>'权限已经存在'];
                echo $json=json_encode($arr);
                die;
            }else{
                Db::table('permission_category')->where
                ('id',$data['id'])->update(['name'=>$data['name'],'description'=>$data['description']]);
                $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                echo $json=json_encode($arr);
            }
        }
    }

}