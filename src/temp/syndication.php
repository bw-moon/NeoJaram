<? 
// 
// 헤드라인 생중계용 php 스크립트 
// 만든이: 임 은재<eunjea@kldp.org> 
// 
// 사용법: 이 파일의 내용을 php 소스에 추가하거나 include()로 포함시키면 됩니다. 
// 

$link_prefix    =    "<li>"; 
$link_postfix    =    "<br/>\n"; 
$cache_file    =    "/tmp/kldpbbs.cache"; 
$cache_time    =    3600; //이 시간은 초단위이므로 짧게 지정하시면 자주 갱신합니다. 
$max_items    =    10; //가져올 헤드라인 갯수 
$target        =    "_new"; 

$backend    =    "http://bbs.kldp.org/rdf.php"; 

$items        =    0; 
$time        =    split(" ", microtime()); 

srand((double)microtime()*1000000); 
$cache_time_rnd    =    300 - rand(0, 600); 

if ( (!(file_exists($cache_file))) || ((filectime($cache_file) + $cache_time - $time[1]) + $cache_time_rnd < 0) || (!(filesize($cache_file))) ) { 

    $fpread = fopen($backend, 'r'); 
    if(!$fpread) { 
        echo "파일 읽기 오류<br>\n"; 
        exit; 
    } else { 

        $fpwrite = fopen($cache_file, 'w'); 
        if(!$fpwrite) { 
            echo "파일 쓰기 오류<br>\n"; 
            exit; 
        } else { 

            while(! feof($fpread) ) { 

                $buffer = ltrim(Chop(fgets($fpread, 256))); 

                if (($buffer == "<item>") && ($items < $max_items)) { 
                    $title = ltrim(Chop(fgets($fpread, 256))); 
                    $link = ltrim(Chop(fgets($fpread, 256))); 
                    $description = ltrim(Chop(fgets($fpread, 256))); 

                    $title = ereg_replace( "<title>", "", $title ); 
                    $title = ereg_replace( "</title>", "", $title ); 
                    $link = ereg_replace( "<link>", "", $link ); 
                    $link = ereg_replace( "</link>", "", $link ); 

                    fputs($fpwrite, "$link_prefix<A HREF=\"$link\" TARGET=\"$target\">$title</A>$link_postfix"); 

                    $items++; 
                } 


            } 
        } 
        fclose($fpread); 
    } 
    fclose($fpwrite); 
} 
if (file_exists($cache_file)) { 
    include($cache_file); 
} 
?>