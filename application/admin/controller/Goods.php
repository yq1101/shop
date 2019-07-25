<?php
namespace app\admin\controller;
use Request;
use Db;
class Goods extends Common
{
	public function list()
    {
    	return $this->fetch();
    }
    public function show()
    {	
    	$arr=Db::query("select g_id,g_name,is_show,add_time,brand_name,brand_desc,name from `s_goods` join `s_brand` on s_goods.brand_id=s_brand.brand_id join `s_category` on s_goods.category_id=s_category.id");
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($arr);
    	die;
    }

    public function show1(){
        $arr=Db::query("select * from s_brand");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        echo json_encode($arr);
        die;
    }


    private function getTree($array, $pid =0, $level = 0){

    foreach ($array as $key => $value){
        //第一次遍历,找到父节点为根节点的节点 也就是pid=0的节点
        if ($value['pid'] == $pid){
            //父节点为根节点的节点,级别为0，也就是第一级
            $flg = str_repeat('|--',$level);
            // 更新 名称值
            $value['name'] = $flg.$value['name'];
            $id = $value['id'];
            $name= $value['name'];
            // 输出 名称
            echo "<option value='$id'>$name</option>";
            //开始递归,查找父ID为该节点ID的节点,级别则为原级别+1
            $this->getTree($array, $value['id'], $level+1);
        }
    }
    }

    public function show2(){
        
        $arr=Db::query("select * from s_category");        
        $this->getTree($arr);
        // $json=['code'=>'0','status'=>'ok','data'=>$arr1];
        // echo json_encode($arr);
        // die;
    }
    public function show3(){
        $data=Request::post();
        $id=$data['id'];
        $arr=Db::query("select * from s_category");
        $arr1=Db::query("select * from s_goods where g_id=$id");
        $arr2=Db::query("select * from s_brand");
        $json=['code'=>'0','status'=>'ok','data'=>$arr1,'opo'=>$arr,'ww'=>$arr2];
        echo json_encode($json);
        die;
    }

    public function addAction(){
        $data=Request::post();
        // var_dump($data);
        // die;
        $g_name=$data['g_name'];
        $brand_id=$data['brand_id'];
        $category_id=$data['category_id'];
        // $g_desc=$data['g_desc'];
        $arr=Db::query("select * from s_goods where g_name='$g_name'");
        $arr1=Db::query("select brand_name from s_brand where brand_id='$brand_id'");

        $brand_name=$arr1[0]['brand_name'];

        $arr2=Db::query("select name from s_category where id='$category_id'");

        $category_name=$arr2[0]['name'];

        if (!empty($arr)) {
            $json=['code'=>'1','status'=>'error','data'=>'重名了'];
            return json($json);
        }else{
            $arr=Db::query("insert into `s_goods` (`brand_id`,`category_id`,`g_name`) values ('$brand_id','$category_id','$g_name')");

                $json=['code'=>'0','status'=>'ok','data'=>$arr];
                return json($json);
        }
    
    }

    public function delete(){
        $data=Request::post();
        $id=$data['id'];
        // var_dump($id);die;
        $validate = new \app\admin\validate\Permissioncate1;
        if (!$validate->check($data)) {
            $arr=['code'=>'302','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
        $arr=Db::query("select * from s_goods_img where goods_id=$id");
        // var_dump($arr);
        // die;
        if (empty($arr)) {
            $arr=Db::query("delete from s_goods where g_id=$id");
            $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
            echo $json=json_encode($arr);
            die;
        }else{
            $big_img=$arr[0]["big_img"];
            $img=str_replace('big_','',$big_img);
            $middle_img=$arr[0]["middle_img"];
            $small_img=$arr[0]["small_img"];
            unlink("./uploads/goodsimg/".$big_img);
            unlink("./uploads/goodsimg/".$middle_img);
            unlink("./uploads/goodsimg/".$small_img);
            unlink("./uploads/goodsimg/".$img);
            
            $arr=Db::query("delete from s_goods_img where goods_id=$id");
            $arr=Db::query("delete from s_goods where g_id=$id");
            $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
            echo $json=json_encode($arr);
            die;
        }
        
    }
    public function updateAction(){
        $data=Request::post();
        // var_dump($data);die;
        $g_id=$data['g_id'];
        $g_name=$data['g_name'];
        $is_show=$data['is_show'];
        $category_id=$data['category_id'];
        $brand_id=$data['brand_id'];
        $arr=Db::query("select * from s_goods where g_id=$g_id");
        // var_dump($arr);
        // die;
        if (empty($arr)) {
            $arr=Db::query("update s_goods set `brand_id`='$brand_id',`category_id`='$category_id',`g_name`='$g_name',`is_show`='$is_show' where g_id='$g_id'");
            $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
            echo $json=json_encode($arr);
            die;
        }else{
            if ($arr[0]['g_id']==$g_id) {
                $arr=Db::query("update s_goods set `brand_id`='$brand_id',`category_id`='$category_id',`g_name`='$g_name',`is_show`='$is_show' where g_id='$g_id'");
                $arr=['code'=>'0','status'=>'ok','data'=>'修改成功'];
                echo $json=json_encode($arr);
                die;
            }else{
                $arr=['code'=>'1','status'=>'error','data'=>'修改失败'];
                echo $json=json_encode($arr);
                die;
            }
        }

    }
}