<?php
require_once 'CommonWidget.php';

class CustomMenuWidget extends CommonWidget {
	public function __construct($pref = array()) {
        $this->widget_name = 'Custom Menu';
        $this->widget_desc = '내가 만드는 메뉴';
        $this->widget_icon = 'layout';
        $this->widget_ver = '1.0';
        $this->widget_nickname = 'custommenu';
        $this->widget_content = '';
        parent::__construct($pref);
	}

	public function getContent() {
		// 커스텀 메뉴 표시
		if (is_login()) {
			dbconn();
			$custom_menu_result = mysql_query("SELECT p.main_menu, p.sub_menu, p.dir, c.pid, c.bid FROM jaram_programs AS p LEFT JOIN jaram_custom_menu AS c ON (p.pid = c.pid AND p.bid = c.bid) WHERE uid = '".$_SESSION["jaram_user_uid"]."' ORDER BY c.order_num ASC");

			if (@mysql_num_rows($custom_menu_result) > 0) {
				$DOCUMENT_CUSTOM_MENU = "\n";
				$DOCUMENT_CUSTOM_MENU .= "<ul>\n";
				while ($rows = mysql_fetch_array($custom_menu_result)) {
					$DOCUMENT_CUSTOM_MENU .= "<li>";
					if (empty($rows['sub_menu'])) {
						$DOCUMENT_CUSTOM_MENU .= "<a href=\"".WEB_ABS_PATH.$rows['dir']."\" class=\"small\">".$rows['main_menu']."</a>";
					} else {
						if ($rows['pid'] == 5100) {
							if ($BOARD_RECENT_COUNT[$rows['bid']] == 0) {
								$recent_article_count = "";
							} else {
								$recent_article_count = "&nbsp;<span class=\"small\">(".$BOARD_RECENT_COUNT[$rows['bid']].")</span>";
							}
						} else {
							$recent_article_count = "";
						}
						$DOCUMENT_CUSTOM_MENU .= "<a href=\"".WEB_ABS_PATH.$rows['dir']."\" class=\"small\">".$rows['sub_menu'].$recent_article_count."</a>";
					}
					$DOCUMENT_CUSTOM_MENU .= "</li>";
				}
				$DOCUMENT_CUSTOM_MENU .= "\n";
				$DOCUMENT_CUSTOM_MENU .= "</ul>\n";
			} else {
				$DOCUMENT_CUSTOM_MENU = "지정된 메뉴가 없습니다.";
			}
		}
		return $DOCUMENT_CUSTOM_MENU;
	}
}
