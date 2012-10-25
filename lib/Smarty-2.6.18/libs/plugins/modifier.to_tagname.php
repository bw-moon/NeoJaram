<?php
function smarty_modifier_to_tagname($tag_id)
{
	$tag = new JaramTag($tag_id);
    return $tag->getTagName();
}

?>
