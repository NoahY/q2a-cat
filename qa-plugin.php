<?php
        
/*              
        Plugin Name: Categorizer
        Plugin URI: https://github.com/NoahY/q2a-cat
        Plugin Update Check URI: https://github.com/NoahY/q2a-cat/raw/master/qa-plugin.php
        Plugin Description: Allows individual categorizing of uncategorized questions
        Plugin Version: 0.2
        Plugin Date: 2012-03-07
        Plugin Author: NoahY
        Plugin Author URI:                              
        Plugin License: GPLv2                           
        Plugin Minimum Question2Answer Version: 1.5
*/                      
                        
                        
        if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
                        header('Location: ../../');
                        exit;   
        }               

        qa_register_plugin_module('module', 'qa-cat-admin.php', 'qa_cat_admin', 'Categorizer Admin');
        
        qa_register_plugin_layer('qa-cat-layer.php', 'Recategorizer Layer');
       
		qa_register_plugin_overrides('qa-cat-overrides.php');

                        
/*                              
        Omit PHP closing tag to help avoid accidental output
*/                              
                          

