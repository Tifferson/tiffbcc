<?

class Datafeeds extends OC_Controller {

	function __construct()
	{
		parent::__construct();
    		$this->load->model('Datafeed');
	}
	
	function total_gallons_picked_up(){
		echo $this->Datafeed->total_gallons_picked_up();
	}

	function total_gallons_picked_up_img() {
		header("Content-type: image/png");
		 
		$image = imagecreatetruecolor(210,65);
		$white = imagecolorallocate($image,255,255,255);
		$black = imagecolorallocate($image,0,0,0);
		imageantialias($image, true);
		imagefill($image,0,0,$white);

		$gallons = $this->Datafeed->total_gallons_picked_up();
		$tag = "gallons of oil collected!";

		$font  = BASEPATH.'/fonts/verdanabold.ttf';
		$this->render($image, $font, 28, $black, number_format($gallons, 0, '.', ','), 210, 0); 
		$this->render($image, $font, 9, $black, "gallons of oil collected!", 210, 40); 
		imagepng ($image);
		imagedestroy($image);
			
	}

	function render($image, $font, $size, $color, $text, $imgwidth, $y) {
		list($llx, $lly, $lrx, $lry, $urx, $ury, $ulx, $uly) = imagettfbbox($size, 0, $font, $text);
		$width = $lrx - $llx;
		$height = $lly - $uly;
		imagettftext($image, $size, 0, $imgwidth/2 - $width/2, $y+$size, $color,$font,$text);
	}
}

?>
