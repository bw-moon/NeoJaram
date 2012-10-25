<!-- 메인 내용 끝 -->
</div> 
<!-- close #mainContent -->
<div class="loading" id="widget_loading" style="display:none;"></div>
<div id="sideBar" class="<?=($context_root_flag)?"rootPage":"subPage"?>">
<? include_once 'sideTool.inc.php' ?>
</div><!-- close #sideBar -->

</div><!-- close#bodyPart -->
</div><!-- close #textWrapper -->
<div id="footer" class="floatClear">
    <!-- 저작권 -->
     <div id="copyright">
	 ⓒ 1999 Jaram Homepage. All rights reserved.<br />
     <?=date("l, F j, Y")?>
	 </div>
   <!-- 저작권 -->
   <!-- 유효성 검사 -->
	<div id="validation" style="display:none">
		<ul>
			<li><a href="http://www.w3.org/Style/CSS/Buttons/"><img src="<?=WEB_ABS_PATH?>/images/valid/mwcos" alt="Made with Cascading Style Sheet" width="88" height="31" border="0"/></a>
			</li>
            <li><a href="http://jigsaw.w3.org/css-validator/validator?uri=http://neo.jaram.org/css/main.css"><img  src="<?=WEB_ABS_PATH?>/images/valid/vcss.gif" border="0" width="88" height="31" alt="Valid CSS!" /></a>
			</li>
			<li><a href="http://validator.w3.org/check/referer"><img src="<?=WEB_ABS_PATH?>/images/valid/valid-xhtml10.png" alt="Valid XHTML 1.0!" border="0" height="31" width="88" /></a></li>
		</ul>
	</div><!-- close #validation -->
</div><!-- close #footer -->

<?php
if (isset($_JARAM_DEBUG_MESSAGE)) {
	echo "<div id=\"debug\">{$_JARAM_DEBUG_MESSAGE}</div>";
}

$profiler = ZendDB::getDBO()->getProfiler();

if ($profiler) {
    $totalTime    = $profiler->getTotalElapsedSecs();
    $queryCount   = $profiler->getTotalNumQueries();
    $longestTime  = 0;
    $longestQuery = null;

    foreach ($profiler->getQueryProfiles() as $query) {
        if ($query->getElapsedSecs() > $longestTime) {
            $longestTime  = $query->getElapsedSecs();
            $longestQuery = $query->getQuery();
        }
    }

    $db_profiled_data =  "DB Profiled Data\n";
    $db_profiled_data .= 'Executed ' . $queryCount . ' queries in ' . $totalTime . ' seconds' . "\n";
    $db_profiled_data .= 'Average query length: ' . $totalTime / $queryCount . ' seconds' . "\n";
    $db_profiled_data .= 'Queries per second: ' . $queryCount / $totalTime . "\n";
    $db_profiled_data .= 'Longest query length: ' . $longestTime . "\n";
    $db_profiled_data .= "Longest query: \n" . $longestQuery . "\n";
    $logger->info($db_profiled_data);
}
?>
</div> <!-- close #container -->
</body>
</html>