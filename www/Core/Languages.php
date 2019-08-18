<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
/**
 * MyMVC Framework Application
 * Languages Class : Manages the loading of language files and the translations of strings into the correct language
 */

class Languages
{
    public $language = '';          # The current language identifier, eg. en_AU
    private $i18n = array();        # The global language translations array
    
    /**
     * Languages constructor
     * Sets the current language and calls a load of the relevant language translations file
     */
    public function __construct ()
    {
        GLOBAL $CONFIG;
        $langfiles = glob( DOCROOT . 'Languages/*.lang.php' );
        if ( is_array( $langfiles ) ) { $langcount = count( $langfiles ); } else { $langcount = 0; }
        if ( $langcount == 1 ) {
            # Assign $language to only existing language file
            $this->language = basename( $langfiles[0], '.lang.php' );
        } elseif ( $langcount >= 2 ) {
            if (false /* Determine language from settings - check if exists */ ) {
                # Load the language set in configuration
            } elseif ( in_array( 'en_AU.lang.php', $langfiles ) ) {
                # Fallback and set language to en_AU if it exists
                $this->language = 'en_AU';
            } else {
                # Fallback and set language to first language found
                $this->language = basename( $langfiles[ 0 ], '.lang.php' );
            }
        } else {
            # No languages installed : Null should falsify an IF check on the $language variable
            $this->language = null;
        }
        
        # Build the primary language array
        if ( $this->language !== null ) {
            $this->i18n_merge( $this->language );
        } else {
            die( 'Languages could not be loaded' );
        }
        
        # Merge in default language to avoid empty language tokens
        if ( $CONFIG->getSetting('Languages','mergelang') !== false ) {
            if ( ( $CONFIG->getSetting('Languages','mergelang') === true ) &&  ( isFile('en_AU', DOCROOT .
                    'Languages/', '.lang.php') ) ) {
                # Merge the default language file
                $this->i18n_merge('en_AU');
            } elseif (
                ( !is_bool( $CONFIG->getSetting( 'Languages', 'mergelang' ) ) ) &&
                ( $CONFIG->getSetting( 'Languages', 'mergelang' ) != $this->language ) &&
                ( isFile( $CONFIG->getSetting( 'Languages', 'mergelang' ), DOCROOT . 'Languages/', '.lang.php' ) )
            ) {
                # Merge defined custom language file if it exists and is not the same as $language
                $this->i18n_merge( $CONFIG->getSetting( 'Languages', 'mergelang' ) );
            }
        }
    }
    
    /**
     * Internationalisation (i18n)
     * Displays or returns a translated string of text in the current language based on given language token
     *
     * @param string $lang_key  The Language Token referring to the string of text
     * @param bool   $echo      True: Echo the string to display, False: Return only the string for use
     * @return string           The translated string of text
     */
    public function i18n ( string $lang_key, bool $echo = true ) : string
    {
        if ( isset( $this->i18n[$lang_key] ) ) {
            $lang_var = $this->i18n[$lang_key];
        } else {
            $lang_var = '{' . $lang_key . '}';
        }
        if ( $echo ) { echo $lang_var; }
        return $lang_var;
    }
    
    /**
     * i18n Merge
     * Merges the given language file contents into the global language translations array
     *
     * @param string|null $language The language to merge into the global array
     * @return bool                 True if successful, False on failure
     */
    private function i18n_merge ( $language = null ) : bool
    {
        $translations = array();
        if ( is_null($language) ) { # Initial (re)loading of language into $i18n - Allow overwrite
            $this->i18n = array(); // Reset array to empty
            $language = $this->language;
            if ( isFile( $language, DOCROOT . 'Languages/', '.lang.php' ) ) {
                include_once ( DOCROOT . 'Languages/' . $language . '.lang.php');
                foreach ( $translations as $key => $value ) {
                    $this->i18n[ $key ] = $value;
                }
                return true;
            }
        } else { # Merge additional language keys - No overwriting
            if ( isFile( $language, DOCROOT . 'Languages/' . '.lang.php' ) ) {
                include_once ( DOCROOT . 'Languages/' . $language . '.lang.php' );
                foreach ( $translations as $key => $value ) {
                    if ( array_key_exists( $key, $this->i18n ) == false ) {
                        $this->i18n[ $key ] = $value;
                    }
                }
                return true;
            }
        }
        return false;
    }
    
    /**
     * i18n Merge for Modules
     * Merges the given modules language file contents into the global language translations array, using current lang.
     *
     * @param string $module    The Module to load the language file from
     * @return bool             True on success, False on failure
     */
    public function i18n_merge_module ( string $module )
    {
        $translations = array(); // Preload the variable in case of malformed module language - Prevents PHP errors
        if ( isFile( $this->language, DOCROOT . 'Modules/' . $module . '/Languages/', '.lang.php' ) ) {
            include_once ( DOCROOT . 'Modules/' . $module . '/Languages/' . $this->language . '.lang.php' );
            foreach ( $translations as $key => $value ) {
                $key = $module . '/' . $key;
                if ( array_key_exists( $key, $this->i18n ) == false ) {
                    $this->i18n[$key] = $value;
                }
            }
            return true;
        }
        return false;
    }
}
