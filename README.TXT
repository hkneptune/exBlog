#############################################################
		此版本为 exBlog 1.3.0 beta 2
   仅供测试之用 不推荐老版本升级 如若升级 出现问题 后果自负
	   测试结果 问题 和 建议 请发布到论坛 谢谢
		    http://bbs.exblog.net
-------------------------------------------------------------
	官方测试:
		    http://test.exblog.net
-------------------------------------------------------------
	另:
		征集多语言包翻译人员
		征集原创模板
	有意者请留言:
		    http://bbs.exblog.net
#############################################################
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                        exBlog 介绍, 版本 1.3.0
            CopyRight (c) 2004, The exSoft Team. All rights reserved.
            Welcome to: http://www.exBlog.net
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

What's exBlog  :p

  - exBlog 是一款基于 PHP + MYSQL 构建的 BLOG 程序,
  - 如果您对 exBlog 有好的建议或发现 BUG 请联系我们, 
  - 我们的 WebSite 是: http://www.exBlog.net

 写在最前面
 ^^^^^^^^^^
 因为从 1.2.0 RC1  版本开始加入了图片上传功能，所以Linux主机会遇到写权限问题。所以如果您的主机是Linux或者Unix、FreeBSD等非Windows类主机请将上传后的upload目录属性设置为777。否则将不能成功上传图片。Windows系统不受此影响，无需修改。

 全新安装
 ^^^^^^^^
   + 注意: 如果你已经安装过,这个程序将会破坏掉上一次安装的资料.除非选用不同的前缀.

   + exBlog 1.3.0 捆绑 i7c，exurl 模板（其他模版可到 http://skin.exblog.net 下载 ）.
   + 安装前请您先阅读 licence.txt 文档

   + 如果你想选择默认语言包：
     　　用编辑器(比如:记事本)打开 admin/install.php 修改 $langURL 为相应地址.
     
   + 请将以下 目录/文件 设置成可写：
     　　├upload			上传文件目录
     　　├include			用来生成基本配置文件
     　　│　└config.inc.php	如果存在的话,最好删除
     　　└admin			用来安装成功之后，安装程序的自毁

   + 关于权限：
      # 如果您的主机是Linux或者Unix、FreeBSD等非Windows类主机请将上传后以上目录属性设置为777。
      # Windows系统不受此影响，无需修改。

   + 将 ./upload 目录下所有文件上传至服务器

   + 运行 ./admin/install.php 建表,创建管理员

   + 当你安装成功，一定要删除 install.php ！如果以上设置正确，将会自毁，否则请手动删除！

   + 安装完成! 进入 ./admin/login.php 登录后台管理程式

   + Enjoy it.

 升级安装
 ^^^^^^^^^
   + 请先备份你所有数据,以免出现意外导致数据丢失
   + 将 exBlog 目录下所有文件上传至服务器端,并覆盖原来文件
   + 请确认 config.inc.php 中设置正确
   + 在浏览器中输入：http://yoursite/update/update.htm，选择适合你的升级方式，点击升级数据库
     http://yoursite为你的blog网址.请仔细确认自己从哪个版本升级。
   + 安装完成! 进入后台登陆管理本Blog
   + 那个 update 目录的使命结束了，高兴可以删掉
   + Enjoy it.

如果你在升级过程中出现错误,请在我们主页上提出,并copy出错信息,最好能配上插图

 页面调用
 ^^^^^^^^
   + 在你所要显示的地方加入<script src=./shell.php?sort=$sort></script>
   + $sort为你所建分类的名称(如'sort=业界新闻'),如不加无参数则调用整个blog表最新8条标题
   + 须注意shell.php文件路径正确
   + 此版本的shell.php已支持使用图片作为前缀。详情请编辑shell.php文件。


 WAP 使用
 ^^^^^^^^^
exBlog从RC2版本开始加入对手机浏览的支持, 为了是您的BLOG顺利实现这一功能, 请仔细阅读一下文档

   + 如何使您的WEB服务器支持WAP数据发送? 
    
	1. 个人服务器情况下
	默认的WEB服务器是不支持WAP数据发送的, 但是, 只需要轻松配置一下服务器对应的MIME格式, 
	就可轻松升级! 具体做法如下: 
	A. IIS 服务器
		设定方式: 打开(开始)-->(程序)-->(WindowsNT4.0 Option Pack)-->
		(Microsoft Internet Information Server)-->(Internet服务管理员)
		在打开的窗口里面选中Server, 右键选择"属性", 增加如下的MIME格式: 
		文件类型　MIME
		·WML　text/vnd.WAP.wml
		·WMLC application/vnd.WAP.wmlc
		·WMLS text/vnd.WAP.wmlscript
		·WMLSC application/vnd.WAP.wmlscriptc
		·WBMP image/vnd.WAP.wbmp
	B. PWS 4.0
		1. 打开注册表编辑器, 在HKEY_CLASSES_ROOT下面新建一个主键".WML"; 
		2. 在HKEY_CLASSES_ROOT\.WML下面新建一个字符串, 命名为"Content Type", 
   		值为 "text/vnd.WAP.wml" Family: 'Times New Roman'; mso-ansi-font-size: '10.5pt'>;
		3. 在HKEY_LOCAL_MACHINE\Software\CLASSES\MIME\Database\Content Type新建一个主键"text/vnd.WAP.wml"
		4. 在"text/vnd.WAP.wml"主键下面新建一个字符串, 名称"Extension", 值为".WML"; 
		5. 依次重复以上2, 3, 4步骤, 建立其他的MIME类型
	C. LINUX APACHE
		使用管理员身份登入系统, 在命令行, 输入"ps -ef"命令兰看看当前的www服务是否正常, 
		然后使用vi来编辑"/etc/httpd/conf/srm.conf"文件, 在AddType处增加如下类型: 
		AddType text/vnd.WAP.wml .wml
		AddType application/vnd.WAP.wmlc .wmlc
		AddType text/vnd.WAP.wmls0.5pt"> .wmlscript
		AddType application/vnd.WAP.wmlsc .wmlscriptc
		AddType image/vnd.WAP.wbmp .wbmp
		保存后, 将 httpd 重新启动即可。

	2. 虚拟主机情况下
	如果您的BLOG使放在虚拟主机的情况下, 那么请和您的主机商联系, 是否支持WAP的MIME格式, 
	有的主机是默认支持的, 如我的主机商(www.512j.com),有的则不支持, 如果不幸没有, 这就要看您的交涉能力了。

 
  + 如何通过WAP终端访问自己的BLOG
　　	待您确认好自己的服务器支持WAP网关后, 就可以用手机顺利访问了, 在手机浏览器里输入youurl.com/WAP
    就可以看到您Blog里最新的3篇BLOG, yoururl.com是您blog的主页地址, 请自行更改。如果想一次浏览更多篇BLOG,
    只需将WAP/index.php文件的第24行
	$query = "SELECT * FROM $x[blog] ORDER BY id DESC LIMIT 0,3";
	中的3改为您想要一次浏览的篇数就可以了, 例如想一次浏览5篇, 则这样: 
	$query = "SELECT * FROM $x[blog] ORDER BY id DESC LIMIT 0,5";
	如果您对 WAP 的使用方法有任何疑问, 请到 http://www.exblog.net 论坛提出,或与 sheer QQ联系: 19104424
 Contact Us
 ^^^^^^^^^^	
   + 如您发现 BUG 或有更好的建议请通过下列方法联系我们:
   + Email: support@exblog.net
   + homepage: http://www.exBlog.net
   + 如果觉得这个程序还可以,请推荐给您朋友谢谢!!thanks

                                                      exSoft Team
                                                      2004/12/21