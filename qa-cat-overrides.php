<?php
		
	function qa_get_request_content() {
		$qa_content = qa_get_request_content_base();
		
		$requestlower=strtolower(qa_request());
		
		if(qa_get_logged_in_level()>=QA_USER_LEVEL_ADMIN && $requestlower == 'admin/categories' && (qa_get('missing') || qa_get('categorize'))) {
			
			$editcategoryid=qa_post_text('edit');
			if (!isset($editcategoryid))
				$editcategoryid=qa_get('edit');
			if (!isset($editcategoryid))
				$editcategoryid=qa_get('addsub');
			$categories=qa_db_select_with_pending(qa_db_category_nav_selectspec($editcategoryid, true, false, true));
			$qa_content['form']['fields'] = array();
			
			$postcount = qa_db_read_one_value(
				qa_db_query_sub(
					"SELECT COUNT(postid) FROM ^posts WHERE type='Q' AND categoryid IS NULL"
				), true
			);
			

			$ctxt = '<i>showing 1 - '.($postcount>qa_opt('categories_plugin_limit')?qa_opt('categories_plugin_limit'):$postcount).' of '.$postcount.' uncategorized posts:</i>';
			
			$qa_content['form']['fields'][] = array(
				'type' => 'static',
				'value' => $ctxt,
			);
			
			$posts = qa_db_query_sub(
				"SELECT BINARY title as title, BINARY content as content, postid FROM ^posts WHERE type='Q' AND categoryid IS NULL LIMIT #",
				qa_opt('categories_plugin_limit')
			);
			
			$count = 0;
			while ( ($post=qa_db_read_one_assoc($posts,true)) !== null ) {
				$qa_content['form']['fields']['cat_'.$count] = array(
					'label' => 'Categorize <a href="'.qa_q_path_html($post['postid'], $post['title']).'" title="'.qa_html(substr($post['content'],0,200)).'">'.$post['title'].'</a> as:',
					'loose' => true,
				);
				qa_set_up_category_field($qa_content, $qa_content['form']['fields']['cat_'.$count], 'cat_'.$count, $categories, @$editcategory['categoryid'], qa_opt('allow_no_category'), qa_opt('allow_no_sub_category'));
				$qa_content['form']['hidden']['cat_'.$count.'_id'] = $post['postid'];
				$count++;
			}
			
			$qa_content['form']['buttons'] = array(
				array(
					'label' => qa_lang_html('admin/save_options_button'),
					'tags' => 'NAME="categorize_plugin_save"',
				),
				array(
					'label' => qa_lang_html('main/cancel_button'),
					'tags' => 'NAME="categorize_plugin_cancel"',
				),
			);
		}
		return $qa_content;
	}
						
/*							  
		Omit PHP closing tag to help avoid accidental output
*/							  
						  

