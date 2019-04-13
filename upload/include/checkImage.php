<?php
/*============================================================================
*\    init Check Image
*\----------------------------------------------------------------------------
*\    Copyright(C) 2004 exSoft Team, All rights reserved
*\----------------------------------------------------------------------------
*\    主页:http://www.exBlog.net (如有任何使用&BUG问题请在论坛提出)
*\----------------------------------------------------------------------------
*\    本程序为免费程序,源代码使用者可任意更改,但请保留本版权信息!
*\----------------------------------------------------------------------------
*\    本页说明: 生成验证码图片
*\===========================================================================*/

//生成图像格式为.png
header("Content-Type:image/png");

//生成session,以便调用页面验证
session_start();
session_register('sysImg');


//创建一个60*20大小的图像
$image = imageCreate(60, 20);
//创建图像中使用的颜色
$blue = imageColorAllocate($image, 0, 0, 255);
$gray = imageColorAllocate($image, 200, 200, 200);
$black = imageColorAllocate($image, 0, 0, 0);
//生成4位随机数
for ($i=0; $i<4; $i++)
{
	$randNum .= rand(0, 9);
}

$_SESSION['sysImg'] = $randNum;

//填充图像区域
imageFilledRectangle($image, 0, 0, 60, 20, $gray);

//画出验证码
imageString($image, 5, 12, 2, $randNum, $blue);
//画出干扰素
for ($i=0; $i<140; $i++)
{	
	//生成随机颜色,并画出200个像素点的干扰素
	$randColor = imageColorAllocate($image, rand(0, 255), rand(0, 255), rand(0, 255));
	imageSetPixel($image, rand(0, 60), rand(0, 20), $randColor);
}
//画边框
imageRectangle($image, 0, 0, 59, 19, $black);
//生成.png图像
imagePng($image);
//释放资源
imageDestroy($image);
?>