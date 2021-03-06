
	<div class="box">
		<div class="t">服务-流程-工场模式</div>
		<p>化简PHP（BaiPHP）开发框架依据服务-流程-工场模式进行设计，该模式将程序划分为服务（Service）、流程（Flow）和工场（Work）三个部分。</p>
		<a class="row" href="/download/Flowork.zip">下载文档</a>
	</div>
	<div class="box">
		<div class="t">工场（Work）</div>
		<ul>
			<li>工场只开放一个常规公开入口（如果必须开放其他入口，也应是静态入口）；</li>
			<li>工场（理论上）只做一件事情或者一类事情；</li>
			<li>工场（理论上）是独立的，封闭的；</li>
			<li>工场相对固定，不随入口数据的变化而变化；</li>
			<li>工场具备动态执行自己的方法的能力；</li>
			<li>工场能够按照预置流程（像一道流水线一样）智能运作。</li>
		</ul>
		<div class="t">流程（Flow）</div>
		<ul>
			<li>流程只开放一个常规公开入口；</li>
			<li>流程具备动态获取与使用工场和其他流程的能力；</li>
			<li>流程通过有序的组织工场以及与其他流程相复合，进而完成高级的复杂的任务；</li>
			<li>流程相对易变，入口数据的不同可能导致流程的改变。</li>
		</ul>
		<div class="t">服务（Service）</div>
		<ul>
			<li>服务位于程序的最前端，直接与用户打交道。服务接受用户的数据，委派流程进行处理，并向用户显示和反馈信息；</li>
			<li>服务由两部分组成：一部分负责组织界面，一部分负责组织信息；</li>
			<li>服务可有若干个界面入口，但只有一个信息出口用于委派流程；</li>
			<li>服务一直在变，因为需求永不满足。</li>
		</ul>
	</div>
