<?php
$action = $this->target[Flow::ACTION];
### 测试对象
$testee = $this->pick(Test::TTESTEE, $action);
### 源代码
$codes = file($this->pick(Test::TSOURCE, $action), true);
### 测试结果
$results = $this->pick(Test::TRESULT, $action);
### 代码覆盖
$lines = $this->pick(Test::TLINES, $action);
### 项目统计
$counts = $this->pick(Test::TCOUNT, $action);
### 通过的测试
$resultCount = $this->pick(Test::TRESULT, $counts);
### 覆盖的代码
$lineCount   = $this->pick(Test::TLINES, $counts);
?>
<style type="text/css">
.covered {background-color:#f0fff0;}
.uncovered {background-color:#fff0f0;}
</style>
<div class="box">
    <div class="t">
        <?php echo $testee; ?>
        <span class="fg">|</span>
        <?php echo $resultCount, '/', count($results); ?>
    </div>
    <div class="text"><?php echo implode('', $results); ?></div>
    <div class="t">
        <?php echo $this['code']; ?>
        <span class="fg">|</span>
        <?php echo $lineCount, '/', count($lines); ?>
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
