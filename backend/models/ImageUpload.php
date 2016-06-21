<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * ImageUpload
 */
class ImageUpload extends Model
{
	/**
	 * [UploadPhoto description]
	 * @param [type]  $model      [实例化模型]
	 * @param [type]  $path       [图片存储路径]
	 * @param [type]  $originName [图片源名称]
	 * @param boolean $isthumb    [是否要缩略图]
	 */
	public static function Upload($model,$path,$originName,$isthumb=false){
		$root = $_SERVER['DOCUMENT_ROOT'].'/'.$path;
		//返回一个实例化对象
		$files = UploadedFile::getInstance($model,$originName);
		$folder = date('Ymd')."/";
		$pre = rand(999,9999).time();
		if($files && ($files->type == "image/jpeg" || $files->type == "image/pjpeg" || $files->type == "image/png" || $files->type == "image/x-png" || $files->type == "image/gif"))
		{
			$newName = $pre.'.'.$files->getExtension();
		}else{
			die($files->type);
		}
		if($files->size > 3000000){
			die("上传的文件太大");
		}
		if(!is_dir($root.$folder))
		{
			if(!mkdir($root.$folder, 0777, true)){
				die('创建目录失败...');
			}else{
				//	chmod($root.$folder,0777);
			}
		}
		//echo $root.$folder.$newName;exit;
		if($files->saveAs($root.$folder.$newName))
		{
			if($isthumb){
				self::imgZip($path.$folder.$newName,550,550,2);
				//	$this->thumbphoto($files,$path.$folder.$newName,$path.$folder.'thumb'.$newName);
				return $path.$folder.$newName;
			}else{
				return $path.$folder.$newName;
			}
				
		}
	}
	
	/*
	 * 函数: 调整图片尺寸或生成缩略图
	* 返回: True/False
	* 参数:
	* 		 $image  需要调整的图片(含路径)
	* 		 $dw=450  调整时最大宽度;缩略图时的绝对宽度
	* 		 $dh=450  调整时最大高度;缩略图时的绝对高度
	* 		 $type=1  1,调整尺寸; 2,生成缩略图
	*/
	
	public static function imgZip($image,$dw=450,$dh=450,$type=1){
		$image = $_SERVER['DOCUMENT_ROOT'].'/'.$image;
		 
		$path='img/';//路径
		$phtypes=array(
				'img/gif',
				'img/jpg',
				'img/jpeg',
				'img/bmp',
				'img/pjpeg',
				'img/x-png'
		);
		 
		if(!file_exists($image)){
			return false;
		}
		//如果需要生成缩略图,则将原图拷贝一下重新给$Image赋值
		if($type!=1){
			Copy($image,str_replace(".",".jpg_x.",$image));
			$image=str_replace(".",".jpg_x.",$image);
		}
		 
		//取得文件的类型,根据不同的类型建立不同的对象
		$imgInfo=getImageSize($image);
		 
		Switch($imgInfo[2]){
			Case 1:
				$img = @ImageCreateFromGIF($image);
				Break;
			Case 2:
				$img = @ImageCreateFromJPEG($image);
				Break;
			Case 3:
				$img = @ImageCreateFromPNG($image);
				Break;
		}
		 
		//如果对象没有创建成功,则说明非图片文件
		if(empty($img)){
			//如果是生成缩略图的时候出错,则需要删掉已经复制的文件
			if($type!=1){
				unlink($image);
			}
			return false;
		}
		//如果是执行调整尺寸操作则
		if($type==1){
			$w=ImagesX($img);
			$h=ImagesY($img);
			$width = $w;
			$height = $h;
			if($width>$dw){
				$par=$dw/$width;
				$width=$dw;
				$height=$height*$par;
				if($height>$dh){
					$par=$dh/$height;
					$height=$dh;
					$width=$width*$par;
				}
			}else if($height>$dh){
				$par=$dh/$height;
				$height=$dh;
				$width=$width*$par;
				if($width>$dw){
					$par=$dw/$width;
					$width=$dw;
					$height=$height*$par;
				}
			}else{
				$width=$width;
				$height=$height;
			}
			$nImg = imagecreatetruecolor($width,$height);   //新建一个真彩色画布
			ImageCopyReSampled($nImg,$img,0,0,0,0,$width,$height,$w,$h);//重采样拷贝部分图像并调整大小
			ImageJpeg ($nImg,$image);     //以JPEG格式将图像输出到浏览器或文件
	
			return true;
			//如果是执行生成缩略图操作则
		}else{
			$w=ImagesX($img);
			$h=ImagesY($img);
			$width = $w;
			$height = $h;
			$nImg = imagecreatetruecolor($dw,$dh);
			if($h/$w>$dh/$dw){ //高比较大
				$width=$dw;
				$height=$h*$dw/$w;
				$IntNH=$height-$dh;
				ImageCopyReSampled($nImg, $img, 0, -$IntNH/1.8, 0, 0, $dw, $height, $w, $h);
			}else{   //宽比较大
				$height=$dh;
				$width=$w*$dh/$h;
				$IntNW=$width-$dw;
				ImageCopyReSampled($nImg, $img, -$IntNW/1.8, 0, 0, 0, $width, $dh, $w, $h);
			}
			ImageJpeg ($nImg,$image);
			return true;
		}
	}
}
