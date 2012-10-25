<?xml version="1.0" encoding="UTF-8" ?> 
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:content="http://purl.org/rss/1.0/modules/content/">
	<channel>
		<title>{$board_name}</title>
		<link>http://jaram.org/board/bbs.php?tableID={$tableID}</link>
		<description>{$board_note|truncate:100|escape:"html"}</description>
		<dc:language>EUC-KR</dc:language> 
		<dc:creator>webmaster@jaram.org</dc:creator> 
		<dc:rights>Copyright 2003</dc:rights> 
		<dc:date>{$nowTime}</dc:date> 
		<admin:generatorAgent rdf:resource="http://www.jaram.org/board" /> 
		<admin:errorReportsTo rdf:resource="mailto:webmaster@jaram.org" /> 
		<sy:updatePeriod>hourly</sy:updatePeriod> 
		<sy:updateFrequency>1</sy:updateFrequency> 
		<sy:updateBase>2000-01-01T12:00+00:00</sy:updateBase> 	
		{foreach item=tdData from=$listData}
		<item>
			<title>{$tdData->title|escape:"html"}</title>
			<link>http://jaram.org/board/view.php?tableID={$tableID}&amp;id={$tdData->id}</link>
			<description></description>
			<dc:date>{$tdData->date|date_format:"%Y-%m-%dT%H:%M:%S"}{$zone}</dc:date>
		</item>
		{/foreach}
	</channel>
</rss>
