<?php if ( !defined('IN_SITE' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
/**
 * RewardingLoyalty
 * - Language Management
 *
 *
 */

/**
 * Display Internationalisation (i18n)
 * Displayd the default language's translations, but if it does not exist, falls back to en-AU
 *
 *
 */
function i18n( $lang_key, $echo = true ) {
    global $LANG, $i18n;
    if ( isset($i18n) ) {
        if ( isset( $i18n[$lang_key] ) ) {
            # Return the matching language translation
            $lang_var = $i18n[$lang_key];
        } else {
            # Surround in curly braces when lang key is not found
            $lang_var = '{' . $lang_key . '}';
        }
    } else {
        # Surround in square brackets if $i18n doesn't exist yet
        $lang_var = '[' . $lang_key . ']';
    }
    if ( $echo ) { echo $lang_var; } else { return $lang_var; }
}
function i18n_r( $lang_key ) : string {
    return i18n( $lang_key, false );
}

/**
 * i18n Merge
 * Merges a language file with the global $i18n language
 */
function i18n_merge( $language = null ) : bool {
    global $i18n, $LANG;
    $translations = array();
    if ( is_null($language) ) { # Initial (re)loading of language into $i18n - Allow overwrite
        $i18n = array(); // Reset array to empty
        if ( isFile($LANG, LANGPATH, '.lang.php')){
            include ( tsl(LANGPATH) . $LANG . ".lang.php" );
            foreach ( $translations as $key => $value ) {
                $i18n[ $key ] = $value;
            }
            return true;
        }
    } else { # Merge additional language keys - No overwriting
        if ( isFile($language, LANGPATH, '.lang.php')){
            include ( tsl(LANGPATH) . $language . ".lang.php" );
            foreach ( $translations as $key => $value ) {
                if ( array_key_exists( $key, $i18n ) == false ) {
                    $i18n[ $key ] = $value;
                }
            }
            return true;
        }
    }
    return false;
}

if ( !isset( $LANG ) || $LANG == '' ) {
    $langfiles = glob( LANGPATH . "*.lang.php" );
    $langcount = count( $langfiles );
    if ( $langcount == 1 ) {
        # Assign LANG to only existing file
        $LANG = basename( $langfiles[0], ".lang.php" );
    } elseif ( $langcount > 1 && in_array( LANGPATH . "en-AU.lang.php", $langfiles ) ) {
        # Fallback and set LANG to en-AU if it exists
        $LANG = "en-AU";
    } elseif ( isset( $langfiles[0] ) ) {
        # Fallback and set LANG to first language found if any exist
        $LANG = basename( $langfiles[0], ".lang.php" );
    } else {
        # No languages installed : Null will falsify an IF check on the $LANG variable
        $LANG = null;
    }
}

if ( !isset( $i18n ) ) { $i18n = array(); global $i18n; }
i18n_merge( null ); // Load $LANG file into $i18n

// Merge in default lang to avoid empty lang tokens
if ( MERGELANG !== false ) {
    if ( ( MERGELANG === true ) && ( isFile("en-AU", LANGPATH, '.lang.php') ) ) {
        # Merge the default language file
        i18n_merge( "en-AU" );
    } elseif (
        ( !is_bool(MERGELANG) ) &&  ( MERGELANG != $LANG ) &&
        ( isFile( MERGELANG, LANGPATH, ".lang.php" ) )
    ) {
        # Merge defined custom language file if it exists and is not the same as &LANG
        i18n_merge( MERGELANG );
    }
}
