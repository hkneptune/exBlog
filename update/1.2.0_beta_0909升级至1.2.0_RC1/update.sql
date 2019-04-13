#建立表exblog_photo，保存图片上传配置
CREATE TABLE `exblog_photo` (
  `max_file_size` int(8) default NULL,
  `destination_folder` varchar(40) default NULL,
  `watermark` int(1) default NULL,
  `waterposition` int(1) default NULL,
  `waterstring` varchar(20) default NULL
) TYPE=MyISAM;

#初始化表exblog_photo
INSERT INTO `exblog_photo` VALUES (2000000, 'upload/', 0, 2, 'exBlog v1.2.0 RC1');

#建立表exblog_weather,保存天气中英文对照
CREATE TABLE `exblog_weather` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `enWeather` varchar(6) NOT NULL,
  `cnWeather` varchar(20) NOT NULL
) TYPE=MyISAM;

#初始化表exblog_weather
INSERT INTO `exblog_weather` VALUES (1, 'null', '请选择天气');
INSERT INTO `exblog_weather` VALUES (2, 'sunny', '阳光');
INSERT INTO `exblog_weather` VALUES (3, 'cloudy', '多云');
INSERT INTO `exblog_weather` VALUES (4, 'rain', '下雨');
INSERT INTO `exblog_weather` VALUES (5, 'snow', '下雪');