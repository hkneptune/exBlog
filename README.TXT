-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                        ExBlog 介绍, 版本 1.2.0
            Copyright (c) 2004, The ExSoft Studio. All rights reserved.
            Welcome to: http://exblog.fengling.net
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

What's ExBlog  :p

  - ExBlog 是一款基于 PHP + MYSQL 构建的 BLOG 程序,
  - 如果您对 ExBlog 有好的建议或发现 BUG 请联系我们, 
  - 我们的 WebSite 是: http://exblog.fengling.net

 全新BLOG
 ^^^^^^^^^
   * 此 blog 目前只有 i7c 一个模版, 由于美工比较忙, 所以只更改了一个模版, 其它模版以包的形式单个发布.
   + 安装前请您先阅读 licence.txt 文档
   + 将 ./exblog 目录下所有文件上传至服务器
   + 将 ./include/config.inc.php 中相关内容修改为对应信息
   + 运行 ./admin/install.php 建表,创建管理员
   + 安装完成! 进入 ./admin/login.php 登录后台管理程式
   + Enjoy it.

 升级安装
 ^^^^^^^^^
   * 此 blog 目前只有 i7c 一个模版, 由于美工比较忙, 所以只更改了一个模版, 其它模版以包的形式单个发布.
   + 请先备份你所有数据,以免出现意外导致数据丢失
   + 将 exblog 目录下所有文件上传至服务器端,并覆盖原来文件
   + 请确认 config.inc.php 中设置正确
   + 运行 update/1.1.0-to-1.2.0/update.php 文件
   + 安装完成! 进入后登管理本程式
   + Enjoy it.
   + (如果你在升级过程中出现错误,请在我们主页上提出,并copy出错信息.)

 页面调用
 ^^^^^^^^
   + 在你所要显示的地方加入<script src=./shell.php?sort=$sort></script>
   + $sort为你所建分类的名称(如'sort=业界新闻'),如不加无参数则调用整个blog表最新8条标题
   + 须注意shell.php文件路径正确

 Contact Us
 ^^^^^^^^^^
   + 如您发现 BUG 或有更好的建议请通过下列方法连联我们:
   + Email: exblog@126.com
   + homepage: http://exblog.fengling.net
   + 如果觉得这个程序还可以,请推荐给您朋友谢谢!!

                                                      ExBlog Team
                                                      2004/09/07
