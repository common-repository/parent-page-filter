<?php
/*
Plugin Name: Parent Page Filter
Description: This plugin allows you to filter by parent pages in the admin dashboard.
Version: 1.0.2
Author: WebHostingHero.com
Author URI: https://www.webhostinghero.com/wp-plugins/filter-pages-by-parent/
Author Email: info@webhostinghero.com
*/


class Parent_page_filter {

    public function __construct(){
        add_action('restrict_manage_posts',array($this, 'filter_parent_pages'));
        add_filter('parse_query',array($this,'filter_the_parent_pages'));
    }
    
    public function filter_parent_pages(){
        global $pagenow;
        
            if(isset($_GET['post_type'])) 
            { 
                $post_type = $_GET['post_type']; 
            }
            else
            {
                $post_type = 'post';
            }
        
	    if ($pagenow=='edit.php' && $_GET['post_type']=='page') {
	        
	        if (isset($_GET['parentId'])) {
		        $dropdown_options = array(
		            'show_option_none' => __( ' All Parent Pages ' ),
		            'depth' => 0,
		            'hierarchical' => 0,
		            'post_type' => $post_type,
		            'sort_column' => 'title',
		            'selected' => $_GET['parentId'],
		            'name' => 'parentId'
		        );
	        } else {
		        $dropdown_options = array(
		            'show_option_none' => __( ' All Parent Pages ' ),
		            'depth' => 0,
		            'hierarchical' => 0,
		            'post_type' => $post_type,
		            'sort_column' => 'title',
		            'name' => 'parentId'        
		        );
	        }
	        
	        wp_dropdown_pages( $dropdown_options );   
        }
    } //End Function filter_parent_pages
    
    public function filter_the_parent_pages($query) {
    
        if (isset($_GET['parentId'])) {
	        global $pagenow;
    
	        $childPages = get_pages(
	            array(
	                'child_of' => $_GET['parentId'],
	                'post_status' => array('publish','draft','trash')
	                )
	             );
	        
	        $filteredPages = array($_GET['parentId']);
	        
	        foreach($childPages as $cp){
	        	array_push($filteredPages, $cp->ID);
	        }
	        
	        $qv = &$query->query_vars;
	        if ($pagenow=='edit.php' && $qv['post_type']=='page') {
	            $qv['post__in'] = $filteredPages;
	        }
        
        }
    
    } //End Function filter_the_parent_pages
    
} //End Class

if(isset($_GET['post_type'])) 
{ 
    $post_type = $_GET['post_type']; 
}
else
{
    $post_type = 'post';
}

if(is_post_type_hierarchical($post_type)){
    $parent_page_filter = new Parent_page_filter();
}

add_action('admin_enqueue_scripts','custom_parent_page_filter_script');
function custom_parent_page_filter_script(){
    wp_enqueue_script( 
        'custom_page_filter_js', //unique handle
        plugin_dir_url(__FILE__).'/js/custom-parent-page-filter-script.js', //location
        array('jquery')  //dependencies
     );
}

?>
