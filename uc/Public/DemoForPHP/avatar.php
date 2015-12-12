<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="i_include/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="allBox">
  <div id="picBox">
    <div id="picViewOuter" style="height:255px">
<?php

//拟用户名
$username=NULL; //

//站点网址
$web['sitehttp']='http://'.(!empty($_SERVER['HTTP_X_FORWARDED_HOST'])?$_SERVER['HTTP_X_FORWARDED_HOST']:$_SERVER['HTTP_HOST']).'/';

//时区
$web['time_pos']=8;

//图片路径
$web['img_up_dir']='uploads';

//截图类型（限jpg、gif、png）
$web['img_up_format']='jpg';

//截图质量（限jpg、70-100，100为最好质量）
$web['img_up_quality']=100;

//上传最大尺寸（KB）
$web['max_file_size'][15]=200;

$web['img_name_b']='boqii_slt';

//截图命名
//$web['img_name_s']=''.$web['img_name_b'].'_small';
$web['img_name_s1']=''.$web['img_name_b'].'_small1';
$web['img_name_s2']=''.$web['img_name_b'].'_small2';
//截图尺寸（大、小二种，分宽、高）
$web['img_w_b'] = 96;
$web['img_h_b'] = 96;
$web['img_w_s'] = 48;
$web['img_h_s'] = 48;


if(strpos($_SERVER['HTTP_REFERER'],$web['sitehttp'])!==0){
  err('禁止本站外提交！');
}

//截图
if($_POST['ptype']==0){
  if(extension_loaded('gd')){
    if(!function_exists('gd_info')){
      err('重要提示：你的gd版本很低，图片处理功能可能受到约束！');
    }
  }else{
    err('重要提示：你尚未加载gd库，图片处理功能可能受到约束！');
  }
  $cimg_o=$_POST['picurl'];

  $cimg_b=typeto($cimg_o,$web['img_up_format']);
  $cimg_m=$web['img_up_dir'].'/boqii_middle.'.$web['img_up_format'];
  $cimg_s1=$web['img_up_dir'].'/'.$web['img_name_s1'].'.'.$web['img_up_format'];
  $cimg_s2=$web['img_up_dir'].'/'.$web['img_name_s2'].'.'.$web['img_up_format'];
  $cut_x=$_POST['imgw']/$_POST['noww']*$_POST['px'];
  $cut_y=$_POST['imgh']/$_POST['nowh']*$_POST['py'];
  $cut_w=$_POST['imgw']/$_POST['noww']*$_POST['pw'];
  $cut_h=$_POST['imgh']/$_POST['nowh']*$_POST['ph'];

  if($_POST['pw']/$_POST['ph']>$web['img_w_b']/$web['img_h_b']){
    $ow1=$web['img_w_b'];
    $oh1=ceil($ow1*$_POST['ph']/$_POST['pw']);
  }else{
    $oh1=$web['img_h_b'];
    $ow1=ceil($oh1*$_POST['pw']/$_POST['ph']);
  }
  if($_POST['pw']/$_POST['ph']>$web['img_w_s']/$web['img_h_s']){
    $ow2=$web['img_w_s'];
    $oh2=ceil($ow2*$_POST['ph']/$_POST['pw']);
  }else{
    $oh2=$web['img_h_s'];
    $ow2=ceil($oh2*$_POST['pw']/$_POST['ph']);
  }
  print_r($cimg_m);
  if(run_img_resize($cimg_b,$cimg_m,$cut_x,$cut_y,$cut_w,$cut_h,$cut_w,$cut_h,$web['img_up_quality']) &&
    run_img_resize($cimg_m,$cimg_s1,0,0,$ow1,$oh1,false,false,$web['img_up_quality']) && 
    run_img_resize($cimg_m,$cimg_s2,0,0,$ow2,$oh2,false,false,$web['img_up_quality'])){
    @unlink($cimg_m);
    if($cimg_o!=$cimg_b){
      @unlink($cimg_b);
    }
    $ow=$_POST['pw'];
    $oh=$_POST['ph'];
    if($ow1/$oh1>=240/180){
      if($ow1>240){
        $ow1=240;
        $oh1=ceil(240*$_POST['ph']/$_POST['pw']);
      }
    }else{
      if($oh1>180){
        $oh1=180;
        $ow1=ceil(180*$_POST['pw']/$_POST['ph']);
      }
    }
    $r = time();

    echo '<script>if(top!=self && top.document.getElementById(\'screenshotsShow\')!=null){top.document.getElementById(\'screenshotsShow\').innerHTML=\'<img src="'.preg_replace('/\.\.\//','',get_en_url($web['img_up_dir'])).'/'.urlencode($web['img_name_s1']).'.'.$web['img_up_format'].'?r='.$r.'" width="'.$ow1.'" height="'.$oh1.'" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="'.preg_replace('/\.\.\//','',get_en_url($web['img_up_dir'])).'/'.urlencode($web['img_name_s2']).'.'.$web['img_up_format'].'?r='.$r.'" width="'.$ow2.'" height="'.$oh2.'" /><br /><br />二种尺寸应用\';}</script>';
   echo '<img class="i_face_small" src="'.get_en_url($web['img_up_dir']).'/'.urlencode($web['img_name_s1']).'.'.$web['img_up_format'].'?r='.$r.'" width="'.$ow1.'" height="'.$oh1.'" />';

  }else{
    err('截图失败！');
  }
}

function get_en_url($d) {
  $arr = @explode('/', $d);
  $arr = array_map('urlencode', $arr);
  return @implode('/', $arr);
}

function err($text,$bj='err'){
  die('<div class="'.$bj.'"></div>'.$text.'点此<a href="javascript:history.back()">返回</a></div></div></div></body></html>');
}

function alert($text,$url='i_up.php'){
  die('<div class="alert"></div>'.$text.'<script>location.href=\''.$url.'\';</script></div></div></div></body></html>');
}

//转换格式
function typeto($im,$format){
  $fr=strtolower(ltrim(strrchr($im,'.'),'.'));
  if($fr!=$format){
    if($fr=='gif'){
      $img=imagecreatefromgif($im);
    }elseif($fr=='png'){
      $img=imagecreatefrompng($im);
    }elseif($fr=='jpg'){
      $img=imagecreatefromjpeg($im);
    }
    if($format=='jpg') $f='jpeg';
    elseif($format=='png') $f='png';
    else $f='gif';
    $im=preg_replace("/\.".preg_quote($fr)."$/","",$im).".".$format;
    eval('
    if(image'.$f.'($img,$im)){
      imagedestroy($img);
    }
    ');
  }
  return $im;
}

//处理缩略图
function run_img_resize($img,$resize_img_name,$dx,$dy,$resize_width,$resize_height,$w,$h,$quality){
  $img_info=@getimagesize($img);
  $width=$img_info[0];
  $height=$img_info[1];
  $w=$w==false?$width:$w;
  $h=$h==false?$height:$h;
  switch($img_info[2]){
    case 1:
    $img=@imagecreatefromgif($img);
    break;
    case 2:
    $img=@imagecreatefromjpeg($img);
    break;
    case 3:
    $img=@imagecreatefrompng($img);
    break;
  }
  if(!$img) return false;
  if(function_exists("imagecopyresampled")){
    $resize_img=@imagecreatetruecolor($resize_width,$resize_height);
    $white=@imagecolorallocate($resize_img,255,255,255);
    @imagefilledrectangle($resize_img,0,0,$resize_width,$resize_height,$white);// 填充背景色
    @imagecopyresampled($resize_img,$img,0,0,$dx,$dy,$resize_width,$resize_height,$w,$h);
  }else{
    $resize_img=@imagecreate($resize_width,$resize_height);
    $white=@imagecolorallocate($resize_img,255,255,255);
    @imagefilledrectangle($resize_img,0,0,$resize_width,$resize_height,$white);// 填充背景色
    @imagecopyresized($resize_img,$img,0,0,$dx,$dy,$resize_width,$resize_height,$w,$h);
  }
  //if(file_exists($resize_img_name)) unlink($resize_img_name);
  switch($img_info[2]){
    case 1:
    @imagegif($resize_img,$resize_img_name);
    break;
    case 2:
    @imagejpeg($resize_img,$resize_img_name,$quality); //100质量最好，默认75
    break;
    case 3:
    @imagepng($resize_img,$resize_img_name);
    break;
  }
  @imagedestroy($resize_img);
  return true;
}




?>
    </div>
  </div>
</div>
</body>
</html>