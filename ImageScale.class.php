<?php

Class ImageScale{

	private $image;
	private $width;
	private $height;
	private $frameWidth;
	private $frameHeight;
	private $scaledImage;

	public function __construct($filename,$frameWidth,$frameHeight)
	{
		$this->image = $this->getFile($filename);
		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);
		$this->frameWidth = $frameWidth;
		$this->frameHeight = $frameHeight;
		
	}

	private function getFile($file)	
	{
		$ext = strtolower(strrchr($file, '.'));
		switch($ext)
		{
			case '.jpg':
			case '.jpeg':
				$img = imagecreatefromjpeg($file);
				break;
			case '.gif':
				$img = imagecreatefromgif($file);
				break;
			case '.png':
				$img = imagecreatefrompng($file);
				break;
			default:
				$img = false;
				break;
		}
		return $img;
	}
	
	public function scaleImage()
	{
		$scaledDimensions = $this->getDimensions();

		$scaledWidth = $scaledDimensions['scaledWidth'];
		$scaledHeight = $scaledDimensions['scaledHeight'];

		$this->scaledImage = imagecreatetruecolor($scaledWidth, $scaledHeight);

		imagecopyresampled($this->scaledImage, $this->image, 0, 0, 0, 0, $scaledWidth, $scaledHeight, $this->width, $this->height);
	}

	private function getDimensions()
	{
		if($this->width > $this->height)
		{
			$newWidth = $this->frameWidth;
			$newHeight = $this->frameWidth/$this->width*$this->height;
		}else{
			$newHeight = $this->frameHeight;
			$newWidth = $this->frameHeight/$this->height*$this->width;
		}
		return array('scaledWidth' => $newWidth , 'scaledHeight' => $newHeight);
	}

	public function save($finalDestination, $quality=80)
	{
 		$ext = strtolower(strrchr($finalDestination, '.'));

		switch($ext)
		{
			case '.jpg':
			case '.jpeg':
				if (imagetypes() & IMG_JPG) {
					imagejpeg($this->scaledImage, $finalDestination, $quality);
				}
				break;

			case '.gif':
				if (imagetypes() & IMG_GIF) {
					imagegif($this->scaledImage, $finalDestination, $quality);
				}
				break;

			case '.png':
				$quality = 9 - (round(($quality/100) * 9));
				if (imagetypes() & IMG_PNG) {
					 imagepng($this->scaledImage, $finalDestination, $quality);
				}
				break;
			default:
				break;
		}
		imagedestroy($this->scaledImage);
	}
}

?>
