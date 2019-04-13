<?php
class VerifyImage
{
	var $random_length = 5;
	var $verify_string = '';

	function VerifyImage()
	{
		if (FALSE == empty($_GET['length']) && TRUE == is_numeric($_GET['length'])) $this->random_length = intval($_GET['length']);
		$this->outputImage();
		if (FALSE == empty($_GET['type'])) $this->saveVerifyString($_GET['type']);
		exit();
	}

	function outputImage()
	{
		mt_srand((double)microtime() * 1000000);
		$verify_string = $this->generate();
		$image_id = imagecreatetruecolor($this->random_length * 7 + 10, 15);
		$bg = imagecolorallocate($image_id, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
		$textcolor = imagecolorallocate($image_id, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
		imagestring($image_id, 3, 5, 0, $verify_string, $textcolor);
		header("Content-type: image/png");
		imagepng($image_id);
		imagedestroy($image_id);
	}

	function generate($salt = "")
	{
		$ITOA64 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		mt_srand((double)microtime() * 1000000);
		for ($i = $this->random_length; $i > strlen($salt);) $salt .= $ITOA64[mt_rand(0, strlen($ITOA64) - 1)];
		$this->verify_string = $salt;
		return $this->verify_string;
	}

	function saveVerifyString($type="login")
	{
		@session_start();
		$_SESSION['verify_string'][$type] = $this->verify_string;
	}
}

new VerifyImage();
?>