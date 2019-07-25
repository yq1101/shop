<?php
namespace app\admin\controller;
use gmars\rbac\Rbac;
use Request;
use Db;
class Permission extends Common
{
    public function list()
    {
    	return $this->fetch();
    }

    public function show()
    {
    	$rbac= new Rbac();
    	$arr=Db::query("select p.id,p.name,p.description,p.path,p_c.name as p_c_name,p.category_id from permission as p join permission_category as p_c on p.category_id=p_c.id");


    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($arr);
    }

    public function addAction()
    {
    	$data=Request::post();
    	// var_dump($data['path']);
    	$validate = new \app\admin\validate\Permission;
    	    if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
           }
    	$rbac= new Rbac();
    	$aa=$rbac->getPermission([['name', '=', $data['name']]]);
    	$bb=$rbac->getPermission([['path', '=', $data['path']]]);
    	if (empty($aa)&&empty($bb)) {
    	$rbac->createPermission([
    		'name' => $data['name'],
    		'description' => $data['description'],
    		'status' => 1,
    		'type' => 1,
    		'category_id' => $data['category_id'],
    		'path' => $data['path'],
		]);
    	$arr=['code'=>'0','status'=>'ok','data'=>'添加成功'];
    	echo $json=json_encode($arr);
    }else{
    	$arr=['code'=>'1','status'=>'error','data'=>'名字或路径不能重复'];
    	echo $json=json_encode($arr);
    }
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
    	$result=Db::query("delete from permission where id=$id");
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
            
    	for ($i=0; $i < count($arr); $i++) { 
    		$id=$arr[$i];
    		Db::query("delete from permission where id=$id");
    	}
    	$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
		echo $json=json_encode($arr);
		die;
    }

    public function updateAction()
    {
    	$data=Request::post();
    	$validate = new \app\admin\validate\Permission;
    	    if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
           }
        $rbac= new Rbac();
    	$name=$data['name'];
    	$path=$data['path'];
    	$arr=Db::query("select * from permission where name='$name' or path='$path'");
    	unset($data['__token__']);
    if(empty($arr)){
    		$arr=Db::table('permission')->update($data);
    		$arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
			echo $json=json_encode($arr);
			die;
    	}else{
    		foreach ($arr as $key => $value) {
    			if ($value['id']!=$data['id']) {
    				$arr=['code'=>'1','status'=>'error','data'=>'name或者path已经存在'];
					echo $json=json_encode($arr);
					die;
    			}
    		}
    		$arr=Db::table('permission')->update($data);
    		$arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
			echo $json=json_encode($arr);
			die;
    	}

    }
}