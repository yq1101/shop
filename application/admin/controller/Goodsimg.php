<?php
namespace app\admin\controller;
use Request;
use Db;
class Goodsimg extends Common
{
	public function list()
    {   
        $g_id=Request::get('g_id');
        $this->assign('g_id',$g_id);
    	return $this->fetch();
    }
    public function show()
    {	
        $data=Request::post();
        // var_dump($data);die;
        $g_id=$data['g_id'];
        // $arr=Db::query("select id from s_goods_img where goods_id=$g_id");
        // var_dump($g_id);die;
    	$arr=Db::query("select id,big_img,middle_img,small_img from s_goods_img join s_goods on s_goods_img.goods_id=s_goods.g_id where s_goods_img.goods_id=$g_id");
    	$json=['code'=>'0','status'=>'ok','data'=>$arr];
    	echo json_encode($arr);
    	die;
    }
    public function addAction(){
        $data=Request::post();
        $g_id=$data['g_id'];
        $files = request()->file('g_img');
        foreach ($files as $file ) {
            $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,png,gif'])->move( './uploads/goodsimg');
            if($info){
                $name=$info->getFilename();
                $data=date("Ymd");
                $path="$data/$name";
                // var_dump($path);die;
                $image= \think\Image::open("./uploads/goodsimg/$path");
                $img_big="$data/big_$name";
                $image->thumb(150,150)->save('uploads/goodsimg/'.$img_big);
                $img_middle="$data/middle_$name";
                $image->thumb(100,100)->save('uploads/goodsimg/'.$img_middle);
                $img_small="$data/small_$name";
                $image->thumb(50,50)->save('uploads/goodsimg/'.$img_small);
                $arr=Db::query("insert into s_goods_img (`big_img`,`middle_img`,`small_img`,`goods_id`) values ('$img_big','$img_middle','$img_small','$g_id')");
                    $json=['code'=>'0','status'=>'ok','data'=>$arr];
                    return json($json);
            }else{
                $json=['code'=>'1','status'=>'error','data'=>$file->getError()];
                return json($json);
            }
        }
    }
    public function delete(){
        $data=Request::post();
        $id=$data['id'];
        $arr=Db::query("select big_img,middle_img,small_img from s_goods_img where id=$id");
        // var_dump($arr);die;
        $big_img=$arr[0]['big_img'];
        $img=str_replace('big_','',$big_img);
        // var_dump($img);die;
        $middle_img=$arr[0]['middle_img'];
        $small_img=$arr[0]['small_img'];
        unlink("./uploads/goodsimg/".$big_img);
        unlink("./uploads/goodsimg/".$middle_img);
        unlink("./uploads/goodsimg/".$small_img);
        unlink("./uploads/goodsimg/".$img);
        $arr=Db::query("delete from s_goods_img where id=$id");
        $arr=['code'=>'0','status'=>'ok','data'=>'删除成功'];
        echo $json=json_encode($arr);
        die;
    }
}