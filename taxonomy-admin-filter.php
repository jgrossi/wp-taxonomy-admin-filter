<?php

/*
Plugin Name: Taxonomy Admin Filter
Plugin URI: http://github.com/jgrossi/taxonomy-admin-filter
Description: Wordpress plugin that allows to add custom taxonomy dropdown in admin grid view
Version: 1.0
Author: Junior Grossi
Author URI: http://juniorgrossi.com/
License: GPL
Copyright: Ohad Raz <admin@bainternet.info>
*/

/**
 * Tax CTP Filter Class
 * Simple class to add custom taxonomy dropdown to a custom post type admin edit list
 * @author Ohad Raz <admin@bainternet.info>
 * @version 0.1
 */
class TaxonomyAdminFilter
{
    /**
     * __construct
     * @author Ohad Raz <admin@bainternet.info>
     * @since 0.1
     * @param array $cpt [description]
     */
    function __construct($cpt = array()){
        $this->cpt = $cpt;
        // Adding a Taxonomy Filter to Admin List for a Custom Post Type
        add_action( 'restrict_manage_posts', array($this,'my_restrict_manage_posts' ));
    }

    /**
     * my_restrict_manage_posts  add the slelect dropdown per taxonomy
     * @author Ohad Raz <admin@bainternet.info>
     * @since 0.1
     * @return void
     */
    public function my_restrict_manage_posts() {
        // only display these taxonomy filters on desired custom post_type listings
        global $typenow;
        $types = array_keys($this->cpt);
        if (in_array($typenow, $types)) {
            // create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
            $filters = $this->cpt[$typenow];
            foreach ($filters as $tax_slug) {
                // retrieve the taxonomy object
                $tax_obj = get_taxonomy($tax_slug);
                $tax_name = $tax_obj->labels->name;

                // output html for taxonomy dropdown filter
                echo "<select name='".strtolower($tax_slug)."' id='".strtolower($tax_slug)."' class='postform'>";
                echo "<option value=''>$tax_name</option>";
                $this->generate_taxonomy_options($tax_slug,0,0,(isset($_GET[strtolower($tax_slug)])? $_GET[strtolower($tax_slug)] : null));
                echo "</select>";
            }
        }
    }

    /**
     * generate_taxonomy_options generate dropdown
     * @author Ohad Raz <admin@bainternet.info>
     * @since 0.1
     * @param  string  $tax_slug
     * @param  string  $parent
     * @param  integer $level
     * @param  string  $selected
     * @return void
     */
    public function generate_taxonomy_options($tax_slug, $parent = '', $level = 0,$selected = null) {
        $args = array('show_empty' => 1);
        if(!is_null($parent)) {
            $args = array('parent' => $parent);
        }
//        $terms = get_terms($tax_slug,$args);
        global $typenow;
        $terms = $this->getTermsByPostType($typenow, $tax_slug);
        $tab='';
        for($i=0;$i<$level;$i++){
            $tab.='--';
        }

        foreach ($terms as $term) {
            // output each select option line, check against the last $_GET to show the current option selected
            echo '<option value='. $term->slug, $selected == $term->slug ? ' selected="selected"' : '','>' .$tab. $term->name .' (' . $term->{"COUNT(*)"} .')</option>';
//            $this->generate_taxonomy_options($tax_slug, $term->term_id, $level+1,$selected);
        }

    }

    public function getTermsByPostType($postType, $taxonomy)
    {
        global $wpdb;

        $query = $wpdb->prepare( "SELECT t.*, COUNT(*) from $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id INNER JOIN $wpdb->term_relationships AS r ON r.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN $wpdb->posts AS p ON p.ID = r.object_id WHERE p.post_type IN('{$postType}') AND tt.taxonomy IN('{$taxonomy}') GROUP BY t.term_id");

        $results = $wpdb->get_results( $query );

        return $results;
    }
}//end class