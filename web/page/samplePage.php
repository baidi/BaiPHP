	<div class="box">
		<div class="t">案例1</div>
		<p>这个案例非常简单，只有两条echo语句。</p>
		<p>源程序位置：<span class="info">/web/page/sample1.php</span></p>
		<a class="row" target="blank" href="/sample1">访问案例</a>
		<div class="t">说明</div>
		<p>将任一PHP文件（<span class="info">sample1.php</span>）放置在页面路径(<span class="info">/web/page</span>)下，就可以通过http://localhost/文件名（<span class="info">sample1</span>）/来访问。当通过文件名（<span class="info">sample1</span>）来访问时，系统会按照下面的优先级寻找目标文件sample1Page.php、sample1Box.php、sample1.php。</p>
		<p>一般将Page作为HTTP请求的页面，Box作为AJAX请求的页面，无后缀作为共同页面组件。</p>
	</div>
	<div class="box">
		<div class="t">案例2</div>
		<p>这个案例展示了一个完整的HTML页面，并从用户配置文件（<span class="info">/bai/php/system/user.php</span>）中读取了一个配置变量$cSample。</p>
		<p>源程序位置：<span class="info">/web/page/sample2Page.php</span></p>
		<a class="row" target="blank" href="/sample2">访问案例</a>
		<div class="t">说明</div>
		<p>用户配置文件（<span class="info">/bai/php/system/user.php</span>）中声明的变量都作为全局变量来使用，可使用global关键字或$GLOBALS数组来访问。</p>
	</div>
	<div class="box">
		<div class="t">案例3</div>
		<p>这个案例中有一组输入项目，并在用户配置文件中配置了相应的验证规则，动手试一下是不是真的这么简单！</p>
		<p>源程序位置：<span class="info">/web/page/sample3Page.php、/web/page/sampleCheck.php</span></p>
		<a class="row" target="blank" href="/sample3">访问案例</a>
		<div class="t">说明</div>
		<p>通过在用户配置文件（<span class="info">/bai/php/system/user.php</span>）中设置验证规则，可以非常简单的实现从客户端到服务器的验证。</p>
		<p>验证规则为：required（非空） min=9（最小长度） max=9（最大长度） type=N（内容属性） function=P（调用函数） function=P ……</p>
		<p>各规则以空格间隔，调用函数可以任意叠加，其他一般只有一个。</p>
		<p>同时，增加了一个用于接收验证结果的AJAX页面：<span class="info">sampleCheck.php</span>，它会显示验证结果。</p>
	</div>
	<div class="box">
		<div class="t">案例4</div>
		<p>这个案例展示了简单的数据库访问。首先，修改系统配置文件（<span class="info">/bai/php/system/system.php</span>）中的数据库配置信息$cData。</p>
		<p>然后，复制下面的SQL文，在你的数据里里面执行，以建立必要的数据。</p>
		<?php include 'sampleSql.php';?>
		<p>源程序位置：<span class="info">/web/page/sample4Page.php、/web/action/Sample4Action.php</span></p>
		<a class="row" target="blank" href="/sample4">访问案例</a>
		<div class="t">说明</div>
		<p>数据操作需要建立一个Action的子类，并重写data方法，在其中调用Data类进行数据库访问操作。</p>
	</div>
