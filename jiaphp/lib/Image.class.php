<?php

/**
 * CWCMS  图片处理文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Image.class.php 202 2015-12-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');
@ini_set("memory_limit", "64M");

class Image
{
	public $source,
	       $thumb_width = null,
	       $thumb_height = null,
	       $thumb_quality = 100,
	       $watermark,
	       $watermark_ext,
	       $watermark_im,
	       $watermark_width,
	       $watermark_height,
	       $watermark_minwidth = 100,
	       $watermark_minheight = 80,
	       $watermark_position = 9,
	       $watermark_trans = 65,
	       $watermark_quality = 100;
	       
	private $imginfo,
	        $imagecreatefromfunc,
	        $imagefunc,
	        $animatedgif = 0;
	
	function set_source($source)
	{
		if (!file_exists($source)) return false;
		$this->source = $source;
		$this->animatedgif = false;
		$this->imginfo = @getimagesize($this->source);
		switch($this->imginfo['mime'])
		{
			case 'image/jpeg':
				$this->imagecreatefromfunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
				$this->imagefunc = (imagetypes() & IMG_JPG) ? 'imagejpeg' : '';
				break;
			case 'image/gif':
				$this->imagecreatefromfunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
				$this->imagefunc = (imagetypes() & IMG_GIF) ? 'imagegif' : '';
				break;
			case 'image/png':
				$this->imagecreatefromfunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
				$this->imagefunc = (imagetypes() & IMG_PNG) ? 'imagepng' : '';
				break;
		}
		if($this->imginfo['mime'] == 'image/gif') 
		{
			if($this->imagecreatefromfunc && !@imagecreatefromgif($this->source)) 
			{
				$this->errno = 1;
				$this->imagecreatefromfunc = $this->imagefunc = '';
				return false;
			}
			$this->animatedgif = strpos(file_get_contents($this->source), 'NETSCAPE2.0') === false ? false : true;
		}
		return !$this->animatedgif;
	}
	
	function set_thumb($width = null, $height = null, $quality = 100)
	{
		$this->thumb_width = is_null($width) ? null : intval($width);
		$this->thumb_height = is_null($height) ? null : intval($height);
		$this->thumb_quality = min(100, intval($quality));
	}
	
	function thumb($source, $target = null)
	{
		
		if(!function_exists('imagecreatetruecolor') || !function_exists('imagecopyresampled') || !function_exists('imagejpeg') || !$this->set_source($source)) return false;

		list($img_w, $img_h) = $this->imginfo;
		if((is_null($this->thumb_width) || $img_w <= $this->thumb_width) && (is_null($this->thumb_height) || $img_h <= $this->thumb_height)) return false;

		if (is_null($target)) $target = $this->source;

		$thumb_w = $this->thumb_width ? $this->thumb_width : $img_w;
		//$thumb_h = $this->thumb_height ? $this->thumb_height : $img_h;
		$thumb_h = $this->thumb_height;
		$x_ratio = $thumb_w / $img_w;
		if(is_null($thumb_h)){
			$thumb_h = $x_ratio*$img_h;
			$sx = 0;
			$sy = 0;
			$cx = $img_w;
			$cy = $img_h;
		}else{
			$y_ratio = $thumb_h / $img_h;
			if(($x_ratio * $img_h) < $thumb_h)
			{
				$cy = $img_h;
				$cx = intval( $img_h * ($thumb_w/$thumb_h) );
				//$thumb['width'] = $thumb_w;
				//$thumb['height'] = ceil($x_ratio * $img_h);
				$sx = -1 * intval( ($cx-$img_w)/2 );
				$sy = 0;
			}
			else
			{
				$cx = $img_w;
				$cy = intval( $img_w * ($thumb_h/$thumb_w) );
				//$thumb['width'] = ceil($y_ratio * $img_w);
				//$thumb['height'] = $thumb_h;
				$sx = 0;
				$sy = -1 * intval( ($cy-$img_h)/2 );
			}
			//$cx = $img_w;
			//$cy = $img_h;
		}
       
		$imagecreatefromfunc = $this->imagecreatefromfunc;
		$img_photo = $imagecreatefromfunc($this->source);
		$thumb_photo = imagecreatetruecolor($thumb_w, $thumb_h);
		imagecopyresampled($thumb_photo, $img_photo ,0, 0, $sx, $sy, $thumb_w, $thumb_h, $cx, $cy);
		clearstatcache();
		
		$imagefunc = $this->imagefunc;
		$result = $this->imginfo['mime'] == 'image/jpeg' ? $imagefunc($thumb_photo, $target, $this->thumb_quality) : $imagefunc($thumb_photo, $target);
		
		@imagedestroy($thumb_photo);
		@imagedestroy($img_photo);

		return $result;
	}
	
	function set_watermark($watermark, $minwidth = null, $minheight = null, $position = null, $trans = null, $quality = null)
	{
		if (!file_exists($watermark)) return false;
		
		$this->watermark = $watermark;
		$this->watermark_ext = strtolower(pathinfo($watermark, PATHINFO_EXTENSION));
		if (!in_array($this->watermark_ext, array('gif', 'png')) || !is_readable($this->watermark)) return false;
		
		$this->watermark_im	= $this->watermark_ext == 'png' ? @imagecreatefrompng($this->watermark) : @imagecreatefromgif($this->watermark);
		if(!$this->watermark_im) return false;
		
		$watermarkinfo	= @getimagesize($this->watermark);
		$this->watermark_width	= $watermarkinfo[0];
		$this->watermark_height	= $watermarkinfo[1];
		
		if (!is_null($minwidth)) $this->watermark_minwidth = intval($minwidth);
		if (!is_null($minheight)) $this->watermark_minheight = intval($minheight);
		if (!is_null($position)) $this->watermark_position = intval($position);
		if (!is_null($trans)) $this->watermark_trans = min(100, intval($trans));
		if (!is_null($quality)) $this->watermark_quality = min(100, intval($quality));
	}
	
	function watermark($source, $target = null)
	{  
		if (!$this->set_source($source) || ($this->watermark_minwidth && $this->imginfo[0] <= $this->watermark_minwidth) || ($this->watermark_minheight && $this->imginfo[1] <= $this->watermark_minheight) || !function_exists('imagecopy') || !function_exists('imagealphablending') || !function_exists('imagecopymerge')) return false;
	
		if (is_null($target)) $target = $source;

		list($img_w, $img_h) = $this->imginfo;

		$wmwidth = $img_w - $this->watermark_width;
		$wmheight = $img_h - $this->watermark_height;
		if($wmwidth < 10 || $wmheight < 10) return false;

		switch($this->watermark_position)
		{
			case 1:
				$x = +5;
				$y = +5;
				break;
			case 2:
				$x = $wmwidth / 2;
				$y = +5;
				break;
			case 3:
				$x = $wmwidth - 5;
				$y = +5;
				break;
			case 4:
				$x = +5;
				$y = $wmheight / 2;
				break;
			case 5:
				$x = $wmwidth / 2;
				$y = $wmheight / 2;
				break;
			case 6:
				$x = $wmwidth;
				$y = $wmheight / 2;
				break;
			case 7:
				$x = +5;
				$y = $wmheight - 5;
				break;
			case 8:
				$x = $wmwidth / 2;
				$y = $wmheight - 5;
				break;
			default:
				$x = $wmwidth - 5;
				$y = $wmheight - 5;
		}
		
		$im = imagecreatetruecolor($img_w, $img_h);
		$imagecreatefromfunc = $this->imagecreatefromfunc;
		$source_im = @$imagecreatefromfunc($this->source);
		imagecopy($im, $source_im, 0, 0, 0, 0, $img_w, $img_h);
			
		if($this->watermark_ext == 'png')
		{
			imagecopy($im, $this->watermark_im, $x, $y, 0, 0, $this->watermark_width, $this->watermark_height);
		}
		else
		{
			imagealphablending($this->watermark_im, true);
			imagecopymerge($im, $this->watermark_im, $x, $y, 0, 0, $this->watermark_width, $this->watermark_height, $this->watermark_trans);
		}
		clearstatcache();
		
		$imagefunc = $this->imagefunc;
		$result = $this->imginfo['mime'] == 'image/jpeg' ? $imagefunc($im, $target, $this->watermark_quality) : $imagefunc($im, $target);
		@imagedestroy($im);
		return $result;
	}
	
	/**
     * 取得图像信息
     * @static
     * @access public
     * @param string $image 图像文件名
     * @return mixed
     */

    public function getImageInfo($img) {
        $imageInfo = getimagesize($img);
        if ($imageInfo !== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
            $imageSize = filesize($img);
            $info = array(
                "width" => $imageInfo[0],
                "height" => $imageInfo[1],
                "type" => $imageType,
                "size" => $imageSize,
                "mime" => $imageInfo['mime']
            );
            return $info;
        } else {
            return false;
        }
    }
	
    /**
     * 生成缩略图
     * @static
     * @access public
     * @param string $image  原图
     * @param string $type 图像格式
     * @param string $thumbname 缩略图文件名
     * @param string $maxWidth  宽度
     * @param string $maxHeight  高度
     * @param string $position 缩略图保存目录
     * @param boolean $interlace 启用隔行扫描
     * @return void
     */
    public function equalThumb($image, $thumbname, $type='', $maxWidth=200, $maxHeight=50, $interlace=true) {
        // 获取原图信息
        $info = $this->getImageInfo($image);
        if ($info !== false) {
            $srcWidth = $info['width'];
            $srcHeight = $info['height'];
            $type = empty($type) ? $info['type'] : $type;
            $type = strtolower($type);
            $interlace = $interlace ? 1 : 0;
            unset($info);
            $scale = min($maxWidth / $srcWidth, $maxHeight / $srcHeight); // 计算缩放比例
            if ($scale >= 1) {
                // 超过原图大小不再缩略
                $width = $srcWidth;
                $height = $srcHeight;
            } else {
                // 缩略图尺寸
                $width = (int) ($srcWidth * $scale);
                $height = (int) ($srcHeight * $scale);
            }

            // 载入原图
            $createFun = 'ImageCreateFrom' . ($type == 'jpg' ? 'jpeg' : $type);
            if(!function_exists($createFun)) {
                return false;
            }
            $srcImg = $createFun($image);

            //创建缩略图
            if ($type != 'gif' && function_exists('imagecreatetruecolor'))
                $thumbImg = imagecreatetruecolor($width, $height);
            else
                $thumbImg = imagecreate($width, $height);
              //png和gif的透明处理 by luofei614
            if('png'==$type){
                imagealphablending($thumbImg, false);//取消默认的混色模式（为解决阴影为绿色的问题）
                imagesavealpha($thumbImg,true);//设定保存完整的 alpha 通道信息（为解决阴影为绿色的问题）    
            }elseif('gif'==$type){
                $trnprt_indx = imagecolortransparent($srcImg);
                 if ($trnprt_indx >= 0) {
                        //its transparent
                       $trnprt_color = imagecolorsforindex($srcImg , $trnprt_indx);
                       $trnprt_indx = imagecolorallocate($thumbImg, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
                       imagefill($thumbImg, 0, 0, $trnprt_indx);
                       imagecolortransparent($thumbImg, $trnprt_indx);
              }
            }
            // 复制图片
            if (function_exists("ImageCopyResampled"))
                imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
            else
                imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);

            // 对jpeg图形设置隔行扫描
            if ('jpg' == $type || 'jpeg' == $type)
                imageinterlace($thumbImg, $interlace);

            // 生成图片
            $imageFun = 'image' . ($type == 'jpg' ? 'jpeg' : $type);
            $imageFun($thumbImg, $thumbname);
            imagedestroy($thumbImg);
            imagedestroy($srcImg);
            return $thumbname;
        }
        return false;
    }

}