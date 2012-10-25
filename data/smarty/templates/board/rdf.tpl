<?xml version="1.0" encoding="UTF-8" ?>
<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns="http://purl.org/rss/1.0/">
<channel rdf:about="http://www.jaram.org/board/">
	<title>{$board_name}</title>
	<link>http://jaram.org/board/bbs.php?tableID={$tableID}</link>
	<description>{$board_note|truncate:100|escape:"html"}</description>
	<dc:language>euc-kr</dc:language>
	<dc:creator>J@WEB</dc:creator>
	<dc:date>{$nowTime}</dc:date>
	<admin:generatorAgent rdf:resource="http://jaram.org/board" />
	<items>
	<rdf:Seq>
	{foreach item=tdData from=$listData}
		<rdf:li rdf:resource="http://jaram.org/board/view.php?tableID={$tableID}&amp;id={$tdData->id}" />
	{/foreach}
	</rdf:Seq>
	</items>
</channel>
{foreach item=tdData from=$listData}
<item rdf:about="http://jaram.org/board/view.php?tableID={$tableID}&amp;id={$tdData->id}">
	<title>{$tdData->title|escape:"html"}</title>
	<description></description>
	<link>http://jaram.org/board/view.php?tableID={$tableID}&amp;id={$tdData->id}</link>
	<dc:date>{$tdData->date|date_format:"%Y-%m-%dT%H:%M:%S"}{$zone}</dc:date>
</item>
{/foreach}
</rdf:RDF>
