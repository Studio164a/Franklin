<?php
/**
 * Handles version to version upgrading
 * 
 * @author Eric Daams <eric@ericnicolaas.com>
 * @since Franklin 1.3
 */

class Sofa_Upgrade_Helper {

    /**
     * Perform upgrade
     *
     * @param int $current
     * @param int|false $db_version 
     * @static
     */
    public static function do_upgrade($current, $db_version) {
    	if ($db_version === false) {            
            self::upgrade_1_3();
        }
    }

    /**
     * Upgrade to version 1.3
     *
     * @since Franklin 1.3
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

        // foreach ( array( 'mod1', 'mod2', 'mod3', 'mod4', 'mod5', 'mod6'
        // ) as $key ) {
        //     set_theme_mod($key, 'set');
        // }
    }
}