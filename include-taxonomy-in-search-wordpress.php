<?php
/*
Author: https://www.rfmeier.net/include-category-and-post-tag-names-in-the-wordpress-search/
*/
class Simple_Taxonomy_Search {

    /**
     * Default constructor
     *
     * @since 1.0.0
     */
    public function __construct() {}

    /**
     * Append WordPress action and filter callbacks.
     *
     * @see https://developer.wordpress.org/reference/functions/add_action/
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function append_callbacks() {

        add_filter( 'posts_join',    array( $this, 'posts_join' ), 10, 2 );
        add_filter( 'posts_where',   array( $this, 'posts_where' ), 10, 2 );
        add_filter( 'posts_groupby', array( $this, 'posts_groupby' ), 10, 2 );

    }

    /**
     * Append a JOIN clause to include taxonomy terms for a WordPress search.
     *
     * Callback for WordPress 'posts_join' filter.
     *
     * @global $wpdb
     *
     * @uses is_main_query()
     * @see https://codex.wordpress.org/Function_Reference/is_main_query
     *
     * @uses is_search()
     * @see https://codex.wordpress.org/Function_Reference/is_search
     *
     * @since 1.0.0
     *
     * @param  string   $join  The SQL JOIN clause.
     * @param  WP_Query $query The current WP_Query object.
     * @return string          The SQL JOIN clause.
     */
    public function posts_join( $join, $query ) {

        global $wpdb;

        if ( is_main_query() && is_search() ) {

            $join .= "
                LEFT JOIN
                (
                    `{$wpdb->term_relationships}`
                    INNER JOIN
                        `{$wpdb->term_taxonomy}` ON `{$wpdb->term_taxonomy}`.term_taxonomy_id = `{$wpdb->term_relationships}`.term_taxonomy_id
                    INNER JOIN
                        `{$wpdb->terms}` ON `{$wpdb->terms}`.term_id = `{$wpdb->term_taxonomy}`.term_id
                )
                ON `{$wpdb->posts}`.ID = `{$wpdb->term_relationships}`.object_id ";

        }

        return $join;

    }

    /**
     * Append a WHERE clause for the current search to include category or
     * post_tag taxonomy term names.
     *
     * Callback for WordPress 'posts_where' filter.
     *
     * @see http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
     *
     * @global object $wpdb
     *
     * @since 1.0.0
     *
     * @param string  $where The current WHERE within the SQL clause
     * @param object  $query The current WP_Query object
     * @return string $where The possibly modified WHERE within the SQL clause
     */
    public function posts_where( $where, $query ) {

        global $wpdb;

        if ( is_main_query() && is_search() ) {

            $taxonomies = $this->posts_where_taxonomies();
            $taxonomies = implode( ', ', $taxonomies );

            $where .= " OR (
                            `{$wpdb->term_taxonomy}`.taxonomy IN( {$taxonomies} )
                            AND
                            `{$wpdb->terms}`.name LIKE '%" . esc_sql( get_query_var( 's' ) ) . "%'
                        )";

        }

        return $where;

    }

    /**
     * Set the GROUPBY clause for post IDs if within the main query and a search.
     *
     * Callback for WordPress 'posts_groupby' filter.
     *
     * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/posts_groupby
     *
     * @global $wpdb
     *
     * @since 1.0.0
     *
     * @param  string   $groupby The GROUPBY sql clause.
     * @param  WP_Query $query   The current WP_Query.
     * @return string            The GROUPBY sql clause.
     */
    public function posts_groupby( $groupby, $query ) {

        global $wpdb;

        if ( is_main_query() && is_search() ) {
            $groupby = "`{$wpdb->posts}`.ID";
        }

        return $groupby;

    }

    /**
     * Get an array of taxonomy names used within the posts_where clause.
     *
     * All taxonomies will be escaped with esc_sql().
     *
     * @uses esc_sql()
     * @see https://codex.wordpress.org/Function_Reference/esc_sql
     *
     * @since 1.0.0
     *
     * @return array An array of taxonomy names.
     */
    public function posts_where_taxonomies() {

        $taxonomies = array(
            'category',
            'post_tag',
        );

        /**
         * Filter 'sts_posts_where_taxonomies'.
         *
         * Alter the taxonomies for the post where clause before they are
         * formatted and escaped.
         *
         * @since 1.0.0
         *
         * @param array $taxonomies An array of taxonomy names.
         */
        $taxonomies = apply_filters( 'sts_posts_where_taxonomies', $taxonomies );

        foreach( $taxonomies as $index => $taxonomy ) {
            $taxonomies[ $index ] = sprintf( "'%s'", esc_sql( $taxonomy ) );
        }

        return $taxonomies;

    }

}
$stiwp = new Simple_Taxonomy_Search();
$stiwp->append_callbacks();
