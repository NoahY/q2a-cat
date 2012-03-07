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
			
					qa_opt('categories_plugin_limit',(int)qa_post_text('categories_plugin_limit'));
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
						'label' => 'Process',
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

