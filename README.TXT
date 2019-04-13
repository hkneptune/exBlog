-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
                        exBlog 介绍, 版本 1.2.0 RC1
            CopyRight (c) 2004, The exSoft Studio. All rights reserved.
            Welcome to: http://exblog.fengling.net
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

What's exBlog  :p

  - exBlog 是一款基于 PHP + MYSQL 构建的 BLOG 程序,
  - 如果您对 exBlog 有好的建议或发现 BUG 请联系我们, 
  - 我们的 WebSite 是: http://exblog.fengling.net

 写在最前面
 ^^^^^^^^^^
 因为此版本加入了图片上传功能，所以Linux主机会遇到写权限问题。所以如果您的主机是Linux或者Unix、FreeBSD等非Windows类主机请将上传后的images\upload目录属性设置为777。否则将不能成功上传图片。Windows系统不受此影响。

 全新BLOG
 ^^^^^^^^^
   + 此 blog 目前只有 i7c， pink 两个模版, 由于美工比较忙, 所以只更改了一个模版, 其它模版以包的形式单个发布.
   + 安装前请您先阅读 licence.txt 文档
   + 将 ./upload 目录下所有文件上传至服务器
   + 将 ./include/config.inc.php 中相关内容修改为对应信息
   + 运行 ./admin/install.php 建表,创建管理员
   + 安装完成! 进入 ./admin/login.php 登录后台管理程式
   + Enjoy it.

 升级安装
 ^^^^^^^^^
   * 此 blog 目前只有 i7c， pink 两个模版, 由于美工比较忙, 所以只更改了一个模版, 其它模版以包的形式单个发布.
   + 请先备份你所有数据,以免出现意外导致数据丢失
   + 将 exblog 目录下所有文件上传至服务器端,并覆盖原来文件
   + 请确认 config.inc.php 中设置正确
   + 进入update文件夹，寻找适合自己的升级文件，
   + 有两种升级方式，一种从1.1.0或1.1.0 plus升级，一种从1.2.0 beta 0909版升级，注意区分，非1.2.0 beta 0909版本用户必须先升级到1.2.0 beta 0909再升到1.2.0 RC1。
   + 一个完整适用所有版本升级的update.php文件稍候提供，给您带来的不便很抱歉。
   + 安装完成! 进入后登管理本程式
   + Enjoy it.
   + (如果你在升级过程中出现错误,请在我们主页上提出,并copy出错信息.)

 页面调用
 ^^^^^^^^
   + 在你所要显示的地方加入<script src=./shell.php?sort=$sort></script>
   + $sort为你所建分类的名称(如'sort=业界新闻'),如不加无参数则调用整个blog表最新8条标题
   + 须注意shell.php文件路径正确
   + 此版本的shell.php已支持使用图片作为前缀。详情请编辑shell.php文件。

 Contact Us
 ^^^^^^^^^^
   + 如您发现 BUG 或有更好的建议请通过下列方法连联我们:
   + Email: exblog@fengling.net
   + homepage: http://exblog.fengling.net
   + 如果觉得这个程序还可以,请推荐给您朋友谢谢!!

                                                      exSoft Team
                                                      2004/10/22
