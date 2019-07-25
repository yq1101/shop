<?php
namespace app\admin\controller;
use Request;
use Db;
class Ware extends Common
{
	public function list()
    {   
        $g_id=Request::get('g_id');
        $this->assign('g_id',$g_id);
    	return $this->fetch();
    }
    public function add()
    {   
        $g_id=Request::get('g_id');
        $this->assign('g_id',$g_id);
        return $this->fetch();
    }
    public function show()
    {	
    	$goods_id=Request::get('goods_id');
        $ware=Db::query("select * from s_ware where goods_id=$goods_id");
        for ($i=0; $i <count($ware) ; $i++) { 
            $new_arr=[];
            $attr_id=$ware[$i]['goods_attr_id'];
            $all_attr_id=explode("-", $attr_id);
            for ($j=0; $j < count($all_attr_id); $j++) { 
                $details_id=$all_attr_id[$j];
                $details=Db::query("select * from s_attr_details where id=$details_id");
                $new_arr[]=$details[0]['name'];
            }
            $str=implode("-", $new_arr);
            $ware[$i]['attr_name']=$str;
        } 
        $js=['code'=>'0','status'=>'ok','data'=>$ware];
        return $js;
    }
    public function show1(){
        $data=Request::post();
        $g_id=$data['g_id'];
        $arr=Db::query("select id,name from s_attr_category");
        $arr1=Db::query("select attr_cate from s_goods where g_id=$g_id");
        $json=['code'=>'0','status'=>'ok','data'=>$arr];
        if (!empty($arr1)) {
            $json['default']=$arr1;
            // var_dump($json);            
        }
        echo json_encode($json);
        die;
    }
    public function show2(){
        $data=Request::post();
        $g_id=$data['g_id'];
        // $attrcate_id=$data['attrcate_id'];
        $arr=Db::query("select * from s_goods_attr where goods_id='$g_id'");
        // var_dump($arr);
        // die;
        $newarr=[];
        foreach ($arr as $key => $value) {
            $attr_id=$value['attr_id'];
            $attr_details_id=$value['attr_details_id'];
            $arr1=DB::query("select name from s_attribute where id='$attr_id'");
            $arr2=DB::query("select * from s_attr_details where id='$attr_details_id'");
            // var_dump($arr2);
            $newarr[$arr1[0]['name']][]=$arr2;//三维数组
        }
        // var_dump($newarr);
        // die;
        $json=['code'=>'0','status'=>'ok','data'=>$newarr];
        echo json_encode($json);
        die;
    }
    public function addAction(){
        $data=Request::post();
        $g_id=$data['g_id'];
        $new_arr=$data['new_arr'];
        // echo "string";
        // dump($new_arr);
        // die;
        $price=$data['price'];
        // $attrcate_id=$data['attrcate_id'];
        $number=$data['number'];
        $attr_id=implode("-", $new_arr);
        // echo "$attr_id";
        // // $arr=Db::query("")
        $arr=Db::query("select * from s_ware where goods_attr_id='$attr_id'");
        if (empty($arr)) {
            Db::query("insert into s_ware(goods_id,goods_attr_id,price,number) values ($g_id,'$attr_id',$price,$number)");
            // echo "insert into s_ware(goods_id,goods_attr_id,price,number) values ($g_id,'$attr_id',$price,$number)";die;
            $js=['code'=>'1','status'=>'ok','data'=>'添加成功!'];
        }else{
            $js=['code'=>'0','status'=>'error','data'=>'货品名字不能重复！'];
        }
        return json($js);
    }

    public function delete(){
        $data=Request::post();
        $id=$data['id'];
        $validate = new \app\admin\validate\Permissioncate1;
        if (!$validate->check($data)) {
            $arr=['code'=>'302','status'=>'error','data'=>$validate->getError()];
            echo $json=json_encode($arr);
            die;
        }
        $arr=Db::query("delete from s_ware where id='$id'");
        $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo $json=json_encode($arr);
        die;
    }
}