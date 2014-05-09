<?php
$action = $this->event[Flow::ACTION];
### 测试对象
$testee = self::pick(Test::TESTEE, $action);
### 源代码
$codes = file(self::pick(Test::SOURCE, $action), true);
### 测试结果
$results = self::pick(Test::RESULT, $action);
### 代码覆盖
$lines = self::pick(Test::LINES, $action);
### 项目统计
$counts = self::pick(Test::COUNT, $action);
### 通过的测试
$resultCovered = self::pick(Test::RESULT, $counts);
### 覆盖的代码
$lineCovered   = self::pick(Test::LINES, $counts);
$resultCount   = count($results);
$lineCount     = count($lines);
$linePercent = '100%';
if ($lineCovered != $lineCount) {
	$linePercent = number_format($lineCovered * 100 / $lineCount, 1).'%';
}
?>
<style type="text/css">
.covered {background-color:#f0fff0;}
.uncovered {background-color:#fff0f0;}
</style>
<div class="box">
	<div class="t">
		<?php echo $testee; ?>
		<span class="fg">|</span>
		<?php echo $resultCovered, '/', $resultCount; ?>
	</div>
	<div class="text"><?php echo implode('', $results); ?></div>
	<div class="t">
		<?php echo $this['codes']; ?>
		<span class="fg">|</span>
		<?php echo $lineCovered, '/', $lineCount; ?>
		<span class="fg">|</span>
		<?php echo $linePercent; ?>
		<pre class="fr"><?php echo $this['normal']; ?></pre>
		<pre class="uncovered fr"><?php echo $this['uncovered']; ?></pre>
		<pre class="covered fr"><?php echo $this['covered']; ?></pre>
	</div>
	<pre><ol><?php
	$i = 0;
	foreach ($codes as $code) {
		$status = $this->pick(++$i, $lines);
		if ($status === null) {
			echo '<li>', htmlspecialchars($code), '</li>';
			continue;
		}
		$class = $status > 0 ? 'covered' : 'uncovered';
		echo '<li class="', $class, '">', htmlspecialchars($code), '</li>';
	}
	?></ol></pre>
</div>
