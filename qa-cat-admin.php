<?php
	class qa_book_admin {

		function option_default($option) {
			
			switch($option) {
			case 'book_plugin_sort':
				return 0;
			case 'book_plugin_inc':
				return 0;
			case 'book_plugin_include_votes':
				return 5;
			case 'book_plugin_loc':
				return dirname(__FILE__).'/book.html';
			case 'book_plugin_request':
				return 'book';
			case 'book_plugin_css':
				return file_get_contents(dirname(__FILE__).'/book.css');
			case 'book_plugin_template':
				return file_get_contents(dirname(__FILE__).'/template.html');
			case 'book_plugin_template_front':
				return file_get_contents(dirname(__FILE__).'/front.html');
			case 'book_plugin_template_toc':
				return file_get_contents(dirname(__FILE__).'/toc.html');
			case 'book_plugin_template_back':
				return file_get_contents(dirname(__FILE__).'/back.html');
			case 'book_plugin_template_category':
				return file_get_contents(dirname(__FILE__).'/category.html');
			case 'book_plugin_template_questions':
				return file_get_contents(dirname(__FILE__).'/questions.html');
			case 'book_plugin_template_question':
				return file_get_contents(dirname(__FILE__).'/question.html');
			case 'book_plugin_template_answer':
				return file_get_contents(dirname(__FILE__).'/answer.html');
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
				
				if (qa_clicked('book_plugin_process')) {
			
					qa_opt('book_plugin_cats',(bool)qa_post_text('book_plugin_cats'));
					qa_opt('book_plugin_sort',(int)qa_post_text('book_plugin_sort'));
					qa_opt('book_plugin_inc',(int)qa_post_text('book_plugin_inc'));
					qa_opt('book_plugin_include_votes',(int)qa_post_text('book_plugin_include_votes'));

					qa_opt('book_plugin_static',(bool)qa_post_text('book_plugin_static'));
					qa_opt('book_plugin_loc',qa_post_text('book_plugin_loc'));
					
					qa_opt('book_plugin_request',qa_post_text('book_plugin_request'));
					
					qa_opt('book_plugin_css',qa_post_text('book_plugin_css'));
					
					qa_opt('book_plugin_template',qa_post_text('book_plugin_template'));
					qa_opt('book_plugin_template_front',qa_post_text('book_plugin_template_front'));
					qa_opt('book_plugin_template_back',qa_post_text('book_plugin_template_back'));
					qa_opt('book_plugin_template_toc',qa_post_text('book_plugin_template_toc'));
					qa_opt('book_plugin_template_category',qa_post_text('book_plugin_template_category'));
					qa_opt('book_plugin_template_questions',qa_post_text('book_plugin_template_questions'));
					qa_opt('book_plugin_template_question',qa_post_text('book_plugin_template_question'));
					qa_opt('book_plugin_template_answer',qa_post_text('book_plugin_template_answer'));
						
					if(qa_opt('book_plugin_static'))
						$ok = qa_book_plugin_createBook();
					else
						$ok = qa_lang('admin/options_saved');
				}
				else if (qa_clicked('book_plugin_reset')) {
					foreach($_POST as $i => $v) {
						$def = $this->option_default($i);
						if($def !== null) qa_opt($i,$def);
					}
					$ok = qa_lang('admin/options_reset');
				} 
			// Create the form for display
				
			$fields = array();
			
			$fields[] = array(
				'label' => 'Sort By Categories',
				'tags' => 'NAME="book_plugin_cats"',
				'value' => qa_opt('book_plugin_cats'),
				'type' => 'checkbox',
			);
			
			$sort = array(
				'votes on question',
				'votes on answer',
				'date',
			);
			
			$fields[] = array(
				'id' => 'book_plugin_sort',
				'label' => 'Sort questions by',
				'tags' => 'NAME="book_plugin_sort" ID="book_plugin_sort"',
				'type' => 'select',
				'options' => $sort,
				'value' => @$sort[qa_opt('book_plugin_sort')],
			);

			$include = array(
				'questions with their selected answer',
				'all answered questions + best answer',
				'answered questions having minimum number of votes',
				'questions with answers having minimum number of votes',
			);
			
			$fields[] = array(
				'id' => 'book_plugin_inc',
				'label' => 'Include',
				'tags' => 'onchange="if(this.selectedIndex>1) $(\'#book_plugin_include_votes\').show(); else $(\'#book_plugin_include_votes\').hide();" NAME="book_plugin_inc" ID="book_plugin_inc"',
				'type' => 'select',
				'options' => $include,
				'value' => @$include[qa_opt('book_plugin_inc')],
			);

			$fields[] = array(
				'value' => '<div id="book_plugin_include_votes" style="display:'.(qa_opt('book_plugin_inc')>1?'block':'none').'">Minimum votes: <input size="3" name="book_plugin_include_votes" value="'.qa_opt('book_plugin_include_votes').'"></div>',
				'type' => 'static',
			);

			$fields[] = array(
				'type' => 'blank',
			);

			$fields[] = array(
				'label' => 'Create Static Book',
				'note' => '<i>if this is unchecked, accessing the book page will recreate the book on every view</i>',
				'tags' => 'onclick="if(this.checked) $(\'#book_plugin_loc\').show(); else $(\'#book_plugin_loc\').hide();" NAME="book_plugin_static"',
				'value' => qa_opt('book_plugin_static'),
				'type' => 'checkbox',
			);
			$fields[] = array(
				'value' => '<div id="book_plugin_loc" style="display:'.(qa_opt('book_plugin_static')?'block':'none').'">Location (must be writable): <input name="book_plugin_loc" value="'.qa_opt('book_plugin_loc').'"></div>',
				'type' => 'static',
			);
			$fields[] = array(
				'type' => 'blank',
			);

			$fields[] = array(
				'label' => 'Book Permalink',
				'note' => '<i>the url used to access the book, either via static file, or on the fly</i>',
				'tags' => 'NAME="book_plugin_request"',
				'value' => qa_opt('book_plugin_request'),
			);
			$fields[] = array(
				'type' => 'blank',
			);

			$fields[] = array(
				'label' => 'Book CSS',
				'note' => '<i>book.css</i>',
				'tags' => 'NAME="book_plugin_css"',
				'value' => qa_opt('book_plugin_css'),
				'type' => 'textarea',
				'rows' => '10',
			);

			$fields[] = array(
				'type' => 'blank',
			);

			$fields[] = array(
				'label' => 'Book Template',
				'note' => '<i>template.html</i>',
				'tags' => 'NAME="book_plugin_template"',
				'value' => qa_opt('book_plugin_template'),
				'type' => 'textarea',
				'rows' => '10',
			);
			$fields[] = array(
				'label' => 'Front Cover Template',
				'note' => '<i>front.html</i>',
				'tags' => 'NAME="book_plugin_template_front"',
				'value' => qa_opt('book_plugin_template_front'),
				'type' => 'textarea',
				'rows' => '10',
			);
			$fields[] = array(
				'label' => 'Back Cover Template',
				'note' => '<i>back.html</i>',
				'tags' => 'NAME="book_plugin_template_back"',
				'value' => qa_opt('book_plugin_template_back'),
				'type' => 'textarea',
				'rows' => '10',
			);
			$fields[] = array(
				'label' => 'Table of Contents Template',
				'note' => '<i>toc.html</i>',
				'tags' => 'NAME="book_plugin_template_toc"',
				'value' => qa_opt('book_plugin_template_toc'),
				'type' => 'textarea',
				'rows' => '10',
			);
			$fields[] = array(
				'label' => 'Category Template',
				'note' => '<i>category.html - used when sorting by categories</i>',
				'tags' => 'NAME="book_plugin_template_category"',
				'value' => qa_opt('book_plugin_template_category'),
				'type' => 'textarea',
				'rows' => '10',
			);
			$fields[] = array(
				'label' => 'Questions Template',
				'note' => '<i>questions.html - used when not sorting by categories</i>',
				'tags' => 'NAME="book_plugin_template_questions"',
				'value' => qa_opt('book_plugin_template_questions'),
				'type' => 'textarea',
				'rows' => '10',
			);
			$fields[] = array(
				'label' => 'Question Template',
				'note' => '<i>question.html</i>',
				'tags' => 'NAME="book_plugin_template_question"',
				'value' => qa_opt('book_plugin_template_question'),
				'type' => 'textarea',
				'rows' => '10',
			);
			$fields[] = array(
				'label' => 'Answer Template',
				'note' => '<i>answer.html</i>',
				'tags' => 'NAME="book_plugin_template_answer"',
				'value' => qa_opt('book_plugin_template_answer'),
				'type' => 'textarea',
				'rows' => '10',
			);

			return array(		   
				'ok' => ($ok && !isset($error)) ? $ok : null,
					
				'fields' => $fields,
			 
				'buttons' => array(
					array(
						'label' => 'Process',
						'tags' => 'NAME="book_plugin_process"',
					),
                    array(
                        'label' => qa_lang_html('admin/reset_options_button'),
                        'tags' => 'NAME="book_plugin_reset"',
                    ),
				),
			);
		}
	}

