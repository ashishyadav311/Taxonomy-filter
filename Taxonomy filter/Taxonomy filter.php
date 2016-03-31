<?php
/*
Plugin Name: Taxonomy filter
Plugin URI: http://www.idonthaveawebsiteyet.net
Description: This plugin is used to filter the post on the basis of tag and website taxonomy
Author: Ashish Yadav
Version: 1.0
Author URI: http://www.idonthaveawebsiteyet.net
*/
function order_init()
{
    
    //defining the filter that will be used to select posts by tags
    function add_post_tags_filter_to_post_administration()
    {
        
        //execute only on the 'post' content type
        global $post_type;
        if ($post_type == 'post') {
            
            $post_formats_args = array(
                'show_option_all' => 'All Tags',
                'orderby' => 'DATE',
                'order' => 'DESC',
                'name' => 'tagId',
                'taxonomy' => 'post_tag'
            );
            
            //if we have a tag already selected, ensure that its value is set to be selected
            if (isset($_GET['tagId'])) {
                $post_formats_args['selected'] = sanitize_text_field($_GET['tagId']);
            }
            
            wp_dropdown_categories($post_formats_args);
            
        }
    }
    //defining the filter that will be used to select posts by website
    function add_post_website_filter_to_post_administration()
    {
        
        //execute only on the 'post' content type
        global $post_type;
        if ($post_type == 'post') {
            
            $post_formats_args = array(
                'show_option_all' => 'All Website',
                'orderby' => 'DATE',
                'order' => 'DESC',
                'name' => 'websiteId',
                'taxonomy' => 'website'
            );
            
            //if we have a website already selected, ensure that its value is set to be selected
            if (isset($_GET['websiteId'])) {
                $post_formats_args['selected'] = sanitize_text_field($_GET['websiteId']);
            }
            
            wp_dropdown_categories($post_formats_args);
            
        }
    }
    add_action('restrict_manage_posts', 'add_post_website_filter_to_post_administration');
    add_action('restrict_manage_posts', 'add_post_tags_filter_to_post_administration');
    
    
    //restrict the posts by the chosen tag
    function add_post_tags_filter_to_posts($query)
    {
        
        global $post_type, $pagenow;
        
        //if we are currently on the edit screen of the post type listings
        if ($pagenow == 'edit.php' && $post_type == 'post') {
            if (isset($_GET['tagId'])) {
                
                //get the desired tagId
                $post_format = sanitize_text_field($_GET['tagId']);
                //if the tagId is not 0 (which means all)
                if ($post_format != 0) {
                    
                    $query->query_vars['tax_query'] = array(
                        array(
                            'taxonomy' => 'post_tag',
                            'field' => 'ID',
                            'terms' => array(
                                $post_format
                            )
                        )
                    );
                    
                }
            }
        }
    }
    //restrict the posts by the chosen website
    function add_post_website_filter_to_posts($query)
    {
        
        global $post_type, $pagenow;
        
        //if we are currently on the edit screen of the post type listings
        if ($pagenow == 'edit.php' && $post_type == 'post') {
            if (isset($_GET['websiteId'])) {
                
                //get the desired websiteId
                $post_format = sanitize_text_field($_GET['websiteId']);
                //if the websiteId is not 0 (which means all)
                if ($post_format != 0) {
                    
                    $query->query_vars['tax_query'] = array(
                        array(
                            'taxonomy' => 'website',
                            'field' => 'ID',
                            'terms' => array(
                                $post_format
                            )
                        )
                    );
                    
                }
            }
        }
    }
    add_action('pre_get_posts', 'add_post_tags_filter_to_posts');
    add_action('pre_get_posts', 'add_post_website_filter_to_posts');
}

add_action('plugins_loaded', 'order_init');

?>
