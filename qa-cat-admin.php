<?php
	class qa_cat_admin {

		function option_default($option) {
			
			switch($option) {
			case 'categories_plugin_limit':
				return 20;
			default:
				return null;				
			}
			
		}
		
		function allow_template($template)
		{
			return ($template!='admin');
		}	   
			
		function admin_form(&$qa_content)
		{					   
							
			// Process form input
				
				$ok = null;
				
				if (qa_clicked('categories_plugin_process')) {
			
					qa_opt('category_plugin_enable',(bool)qa_post_text('category_plugin_enable'));
					qa_opt('categories_plugin_limit',(int)qa_post_text('categories_plugin_limit'));

					$ok = qa_lang('admin/options_saved');
				}
				else if (qa_clicked('categories_plugin_reset')) {
					foreach($_POST as $i => $v) {
						$def = $this->option_default($i);
						if($def !== null) qa_opt($i,$def);
					}
					$ok = qa_lang('admin/options_reset');
				} 
			// Create the form for display
				
			$fields = array();
			
			$fields[] = array(
				'label' => 'Enable Categorizer',
				'tags' => 'NAME="category_plugin_enable"',
				'note' => 'replaces the link at admin/categories for questions without categories',
				'value' => qa_opt('category_plugin_enable'),
				'type' => 'checkbox',
			);
			$fields[] = array(
				'label' => 'Max number of posts to recategorize at once',
				'tags' => 'NAME="categories_plugin_limit"',
				'value' => qa_opt('categories_plugin_limit'),
				'type' => 'number',
			);

			return array(		   
				'ok' => ($ok && !isset($error)) ? $ok : null,
					
				'fields' => $fields,
			 
				'buttons' => array(
					array(
						'label' => qa_lang_html('admin/save_options_button'),
						'tags' => 'NAME="categories_plugin_process"',
					),
                    array(
                        'label' => qa_lang_html('admin/reset_options_button'),
                        'tags' => 'NAME="categories_plugin_reset"',
                    ),
				),
			);
		}
	}

