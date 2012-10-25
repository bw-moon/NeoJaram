// Notice: The simple theme does not use all options some of them are limited to the advanced theme
tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	save_callback : "customSave"
});

function customSave(id, content) {
	$('comment_note').value = content;
}