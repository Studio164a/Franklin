<?php
/**
 * Handles version to version upgrading.
 * 
 * @author  Eric Daams
 * @since   1.3.0
 */

class Franklin_Upgrade_Helper {

    /**
     * Perform upgrade
     *
     * @param   int $current
     * @param   int|false $db_version 
     * @static
     */
    public static function do_upgrade($current, $db_version) {
    	if ($db_version === false) {            
            self::upgrade_1_3();
        }
    }

    /**
     * Upgrade to version 1.3.0.
     *
     * @since   1.3.0
     */
    protected static function upgrade_1_3() {
        $mods = get_theme_mods();

        $mods['campaign_sharing_text'] = __( 'Spread the word about this campaign by sharing this widget. Copy the snippet of HTML code below and paste it on your blog, website or anywhere else on the web.', 'franklin' );
        $mods['campaign_share_twitter'] = 1;
        $mods['campaign_share_twitter'] = 1;
        $mods['campaign_share_facebook'] = 1;
        $mods['campaign_share_googleplus'] = 1;
        $mods['campaign_share_linkedin'] = 1;
        $mods['campaign_share_pinterest'] = 1;
        $mods['campaign_share_widget'] = 1;

        $theme = get_option( 'stylesheet' );
        update_option("theme_mods_$theme", $mods);
    }

    /**
     * Upgrade to the new Hide Meta plugin. 
     *
     * The key thing that needs to happen is that the old meta key, `_franklin_hide_post_meta*` 
     * has to be updated to become `_hide_meta`. 
     * 
     * @since   1.6.0
     */
    public static function do_hide_meta_upgrade() {
        $to_update = new WP_Query( array(
            'post_type'     => array( 'post', 'page' ),
            'meta_key'      => '_franklin_hide_post_meta' 
        ) );

        if ( $to_update->have_posts() ) { 
            while ( $to_update->have_posts() ) {
                $to_update->the_post();

                $value = get_post_meta( get_the_ID(), '_franklin_hide_post_meta', true );

                update_post_meta( get_the_ID(), '_hide_meta', $value );
            }
        }
    }
}