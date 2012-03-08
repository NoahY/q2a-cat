<?php

	class qa_html_theme_layer extends qa_html_theme_base {
		
		function doctype() {
			qa_html_theme_base::doctype();
			if(qa_opt('category_plugin_enable') && $this->request == 'admin/categories') {
				if(qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN && qa_clicked('categorize_plugin_save')) {
					$cnt = 0;
					while(qa_post_text('cat_'.$cnt.'_id')) {
						$cnt2 = 1;
						while(qa_post_text('cat_'.$cnt.'_'.($cnt2+1)))
							$cnt2++;
						qa_db_query_sub('UPDATE ^posts SET categoryid=# WHERE postid=#', qa_post_text('cat_'.$cnt.'_'.$cnt2), qa_post_text('cat_'.$cnt.'_id'));
						
						$cnt++;
					}
					$uncat = qa_db_read_one_value(
						qa_db_query_sub("SELECT COUNT(postid) FROM ^posts WHERE type='Q' AND categoryid IS NULL")
					);
					qa_redirect(qa_request(), (int)$uncat?array('recalc' => 1,'categorize' => 'true'):array('recalc' => 1));
				}
				else if(isset($this->content['form']['fields']['allow_no_category']['error']))
					$this->content['form']['fields']['allow_no_category']['error'] = preg_replace('/HREF="[^"]*"/','HREF="'.qa_path_html(qa_request(),array('categorize'=>'true')).'"',$this->content['form']['fields']['allow_no_category']['error']);
			}
		}
	}

