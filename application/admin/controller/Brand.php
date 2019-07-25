<?php
namespace app\admin\controller;
use Request;
use Db;
class Brand extends Common
{
	public function list()
    {
    	return $this->fetch();
    }

    public function show()
    {	
    	$arr=Db::query("select brand_id,brand_name,site_url,brand_desc,brand_logo from s_brand ");
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($arr);
    	die;
    }
    public function addAction(){
        $data=Request::post();       
        $b_name=$data['b_name'];
        $b_url=$data['b_url'];
        $b_description=$data['b_description'];
    	$validate = new \app\admin\validate\Brand;
    	    if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
        $file = request()->file('b_file');
        $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads');
            if($info){

                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $path=str_replace("\\","/",$info->getSaveName());

    	        $bb=Db::query("select * from s_brand where brand_name='$b_name'");
            	if (empty($bb)) {
            		$arr=Db::query("insert into `s_brand` (`brand_name`,`brand_desc`,`site_url`,`brand_logo`) values ('$b_name','$b_description','$b_url','$path')");
            		$json=['code'=>'0','status'=>'ok','data'=>$arr];
            		return json($json);
            	}else{
            		$json=['code'=>'1','status'=>'ok','data'=>'品牌名称不能重复'];
            		return json($json);
            	}
            }
    }
    public function del(){
    	$data=Request::post();
    	$id=$data['id'];
    	$arr=explode(",", $id);
    	// var_dump($arr);
    	// die;
    	$validate = new \app\admin\validate\Permissioncate1;
    	if (empty($arr)) {
    		$arr=['code'=>'1','status'=>'error','data'=>'请至少勾选一个'];
    		echo $json=json_encode($arr);
    		die;
    	}elseif (!$validate->check($data)) {
            $arr=['code'=>'302','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
            
    	for ($i=0; $i < count($arr); $i++) { 
    		$id=$arr[$i];
    		Db::query("delete from s_brand where brand_id=$id");
    	}
    	$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
		echo $json=json_encode($arr);
    	die;
    }

    public function delete(){
    	$data=Request::post();
        $validate = new \app\admin\validate\Permissioncate1;
        if (!$validate->check($data)) {
            $arr=['code'=>'302','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }

    	$id=Request::post('id');
        $arr=Db::query("select brand_logo from s_brand where brand_id=$id");
        // var_dump($arr[0]['brand_logo']);die;
        $img=$arr[0]['brand_logo'];
        unlink("./uploads/".$img);
    	$arr=Db::query("delete from s_brand where brand_id=$id");

		$arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo $json=json_encode($arr);
        die;
    }
    public function updateAction(){
    	$data=Request::post();
    	$validate = new \app\admin\validate\Brand;
    	    if (!$validate->check($data)) {
            $arr=['code'=>'1','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
        $id=$data['b_id'];
        $name=$data['b_name'];
        $site_url=$data['b_site_url'];
        $description=$data['b_description'];
        // $sort_order=$data['b_sort_order'];
        $bb=Db::query("select * from s_brand where brand_name='$name'");
        // var_dump($bb);
        // die;
        if (empty($bb)) {
        	 $arr=Db::query("update s_brand set brand_name='$name',brand_desc='$description',site_url='$site_url' where brand_id='$id'");
        	$arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            echo $json=json_encode($arr);
            die;
        }else{
        	if($bb[0]['brand_id']!=$id){
                $arr=['code'=>'1','status'=>'error','data'=>'权限已经存在'];
                echo $json=json_encode($arr);
                die;
            }else{
                $arr=Db::query("update s_brand set brand_name='$name',brand_desc='$description',site_url='$site_url' where brand_id='$id'");
                $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                echo $json=json_encode($arr);
                die;
            }
        }
    }

    public function fileAction(){
        $data=Request::post();
        $id=$data['id'];
        $newfile = request()->file('up_file');
        $arr=Db::query("select brand_logo from s_brand where brand_id=$id");
        // var_dump($arr[0]['brand_logo']);die;
        $img=$arr[0]['brand_logo'];
        unlink("./uploads/".$img);
        $info = $newfile->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads');
        if ($info) {
            $path=str_replace("\\","/",$info->getSaveName());
            $arr=Db::query("update s_brand set brand_logo='$path' where brand_id=$id");
            $json=['code'=>'0','status'=>'ok','data'=>$arr];
            return json($json);
        }else{
            $json=['code'=>'1','status'=>'error','data'=>'修改失败'];
            return json($json);
        }
    }
}