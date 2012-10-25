<?php

require_once('library.inc.php');

class JaramImage extends JaramCommon {

	/**
	 * JaramCommon 에서 자동으로 init()실행
	 */
	function init() {
		$this->imgdir = HOMEPAGE_PATH."/static/images";

		if (!file_exists($this->imgdir)) {
			if (!file_exists(HOMEPAGE_PATH."/static")) {
				mkdir(HOMEPAGE_PATH."/static");
			}
			mkdir($this->imgdir);
		}
	}

	function getBoardImg($uid, $image_id, $size_x = null, $size_y = null) {
		// 테이블 뒤져서 값 구하기
		$org_path = '';
		return $this->getImage($uid, "jaram_board_file", $image_id, '', $org_path, $size_x, $size_y);
	}

	/**
	 * 사용자 이미지의 리사이즈 된 파일의 주소 리턴
	 */
	function getUserImg($uid, $size_x = null, $size_y = null) {
		return $this->getImage($uid, "jaram_users", $uid, "user", PROGRAM_ROOT.'/src/member/profile/'.$uid, $size_x, $size_y);
	}

	/**
	 * 코멘트용 사용자 이미지의 리사이즈 된 파일의 주소 리턴
	 */
	function getCommentImg($uid, $size_x = 50, $size_y = 50) {
		$filename = PROGRAM_ROOT.'/src/member/profile/'.$uid."_sub";
		if (!file_exists($filename)) {
			$filename = PROGRAM_ROOT.'/src/member/profile/'.$uid;
		}
		return $this->getImage($uid, "jaram_users", $uid, "comment", $filename, $size_x, $size_y);
	}

	
	/**
	 * 파라메터에 해당하는 이미지가 static/image 디렉토리에 존재하는지 확인하고 존재하지 않을 경우 
	 * 해당 이미지를 만들고 존재 할 경우 파일 이름을 리턴
	 *
	 * @param $uid 사용자 uid
	 * @param $table 이미지가 들어 있는 테이블
	 * @param $image_id 테이블 내의 이미지 primary key
	 * @param $flag 같은 pk값 안에 두개 이상의 이미지가 있을 경우 구분자
	 * @param $org_path 원본 이미지 위치
	 * @param $size_x 리사이즈 할 경우 가로크기
	 * @param $size_y 리사이즈 할 경우 세로크기
	 * 
	 * @return 웹에서 접근 가능한 이미지 파일 주소
	 */
	function getImage($uid, $table, $image_id, $flag, $org_path, $size_x = null, $size_y = null) {
		$plane_str = implode("|", array($uid, $table, $image_id, $flag, $org_path, $size_x, $size_y));
		$path_array = pathinfo($org_path);
		
		if (!$path_array['extension']) {
			if (!file_exists($org_path.".jpg")) {
				$this->logger->debug($org_path);
				copy($org_path, $org_path.".jpg");
			} else {
				$org_path = $org_path.".jpg";
				$path_array = pathinfo($org_path);
			}
		}

		$target_filename = $path_array['extension'] ? md5($plane_str).".".$path_array['extension'] : md5($plane_str);
		$this->logger->debug("original filename : {$org_path}\nhashed filename : {$target_filename}");
	
		$target_path = $this->imgdir."/".$target_filename;

		if (!file_exists($target_path)) {
			$this->logger->debug("start resize image");
			$this->makeImage($org_path, $target_path, $size_x, $size_y); 
		}

		return WEB_ABS_PATH."/static/images/".$target_filename;
	}

	function makeImage($source_path, $dest_path, $size_x, $size_y) {
		// 이미지 리사이즈
		if ($size_x && $size_y) {
			$this->resizeCropImage($source_path, $dest_path, $size_x, $size_y);
		}
		// 그냥 이미지 복사
		else {
			copy($source_path, $dest_path);
		}
	}

	function makeExtension() {
		
	}


	function resizeCropImage($src, $dst, $dstx, $dsty){
		//$src = original image location
		//$dst = destination image location
		//$dstx = user defined width of image
		//$dsty = user defined height of image
		$allowedExtensions = 'jpg jpeg gif png';
		$name = explode(".", strtolower($src));
		$currentExtensions = $name[count($name)-1];
		$extensions = explode(" ", $allowedExtensions);

		for($i=0; count($extensions)>$i; $i=$i+1) {
			if($extensions[$i]==$currentExtensions)
			{ 
				$extensionOK=1; 
				$fileExtension=$extensions[$i]; 
				break; 
			}
		}

		if($extensionOK){
			$size = getImageSize($src);
			$width = $size[0];
			$height = $size[1];
			if($width >= $dstx AND $height >= $dsty){
				$proportion_X = $width / $dstx;
				$proportion_Y = $height / $dsty;
				if($proportion_X > $proportion_Y ){
					$proportion = $proportion_Y;
				}else{
					$proportion = $proportion_X ;
				}
				$target['width'] = $dstx * $proportion;
				$target['height'] = $dsty * $proportion;
				$original['diagonal_center'] = round(sqrt(($width*$width)+($height*$height))/2);
				$target['diagonal_center'] =  round(sqrt(($target['width']*$target['width']) + ($target['height']*$target['height']))/2);
				$crop = round($original['diagonal_center'] - $target['diagonal_center']);
				if($proportion_X < $proportion_Y ){
					$target['x'] = 0;
					$target['y'] = round((($height/2)*$crop)/$target['diagonal_center']);
				}else{
					$target['x'] =  round((($width/2)*$crop)/$target['diagonal_center']);
					$target['y'] = 0;
				}
				if($fileExtension == "jpg" OR $fileExtension=='jpeg'){ 
					$from = ImageCreateFromJpeg($src); 
				} elseif ($fileExtension == "gif"){ 
					$from = ImageCreateFromGIF($src); 
				} elseif ($fileExtension == 'png'){
					$from = imageCreateFromPNG($src);
				}
				$new = ImageCreateTrueColor ($dstx,$dsty);
				imagecopyresampled ($new,  $from,  0, 0, $target['x'], 
				$target['y'], $dstx, $dsty, $target['width'], $target['height']);
				if($fileExtension == "jpg" OR $fileExtension == 'jpeg'){ 
					imagejpeg($new, $dst, 70); 
				} elseif ($fileExtension == "gif"){ 
					imagegif($new, $dst); 
				}elseif ($fileExtension == 'png'){
					imagepng($new, $dst);
				}
			} else {
				copy($src, $dst);
			}
		}
	}
}


?>