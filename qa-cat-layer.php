<?php

	class qa_html_theme_layer extends qa_html_theme_base {
		
		function doctype() {
			qa_html_theme_base::doctype();
			if($this->request == 'admin/categories') {
				if( qa_clicked('categorize_plugin_save')) {
					$cnt = 0;
					while(qa_post_text('cat_'.$cnt.'_id')) {
						$cnt2 = 1;
						while(qa_post_text('cat_'.$cnt.'_'.($cnt2+1)))
							$cnt2++;
						qa_db_query_sub('UPDATE ^posts SET categoryid=# WHERE postid=#', qa_post_text('cat_'.$cnt.'_'.$cnt2), qa_post_text('cat_'.$cnt.'_id'));
						
						$cnt++;
					}
					qa_redirect(qa_request(), array('recalc' => 1));
				}
				else if(isset($this->content['form']['fields']['allow_no_category']['error']))
					$this->content['form']['fields']['allow_no_category']['error'] = preg_replace('/HREF="[^"]*"/','HREF="'.qa_path_html(qa_request(),array('categorize'=>'true')).'"',$this->content['form']['fields']['allow_no_category']['error']);
			}
		}
	}

