=== Taxonomy Admin Filter ===
Contributors: jgrossi
Tags: taxonomy, admin, filter
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 1.0

Show a select field on posts list allowing to filter posts by taxonomy

== Description ==

This plugin allow you to filter posts by a specific taxonomy. Inside your functions.php file:

    new Tax_CTP_Filter(array(
        'book' => array('genre','author'),
        'movie' => array('genre','actors'),
        'task' => array('task_status'),
    ));

Based on http://en.bainternet.info/2013/add-taxonomy-filter-to-custom-post-type

== Installation ==

1. Upload `taxonomy-admin-filter` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to your functions.php file and call the class according to description section

== Frequently Asked Questions ==

Nothing for now :-)

== Changelog ==

* Plugin launch!