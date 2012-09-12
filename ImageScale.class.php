<?php

/**
	* Image Scale Class
	* @author     Mohsin
	* @version    1.0
	* @date       09/12/2012
	*
	* Use : To create a scaled version of an image, keeping the aspect ratio intact,
	* to fit within a frame of pre defined dimensions.
	*
	* Example: To scale an image of any dimension to fit within a container of 700X524 
	* and with output image quality 80
	*
	* $scaleObject = new ImageScale('abc.jpg',700,524);
	* $scaleObject->scaleImage();
	* $scaleObject->save('abc_new.jpg',80);
	*/

class ImageScale{
	private $image;
	private $width;
	private $height;
	private $frameWidth;
	private $frameHeight;
	private $scaledImage;

	/* constructor function, takes in the filename and container dimensions where the output image needs to be placed */
	public function __construct($filename,$frameWidth,$frameHeight)
	{
		$this->image = $this->getFile($filename);

		//get image dimensions
		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);

		$this->frameWidth = $frameWidth;
		$this->frameHeight = $frameHeight;

	}

	/* method that opens up the file and creates an image resource */
	private function getFile($file)	
	{
		//get image file's extension
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

	/*method to scale the image */	
	public function scaleImage()
	{
		//get the dimensions to which the source image shall be scaled	
		$scaledDimensions = $this->getDimensions();

		$scaledWidth = $scaledDimensions['scaledWidth'];
		$scaledHeight = $scaledDimensions['scaledHeight'];
		$this->scaledImage = imagecreatetruecolor($scaledWidth, $scaledHeight);
		imagecopyresampled($this->scaledImage, $this->image, 0, 0, 0, 0, $scaledWidth, $scaledHeight, $this->width, $this->height);
	}

	/* Method to get the dimensions to which the source image shall be scaled	*/
	private function getDimensions()
	{
		//scale the longer side first and the shorter side as per the ratio
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

	/*Method to save the scaled image resource */
	public function save($finalDestination, $quality=80)
	{
		//get the extension of the file to be saved to determine the format of the output image 		
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
