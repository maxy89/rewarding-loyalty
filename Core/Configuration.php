<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
/**
 * MyMVC Framework Application
 * Configuration Class : Manages the configuration settings of the Core and all Modules
 */

class Configuration
{
    private $CONFIG = array();              # Array of Core configuration settings
    private $MODULE_CONFIGS = array();      # Array of Module configuration settings
    
    /**
     * Configuration constructor
     * Builds the initial configuration settings arrays for the Core configuration and for configuration of each Module,
     * taking into account the configuration override files in the Config directory.
     */
    public function __construct ()
    {
        # Get default configuration file and parse it into the $CONFIG array.
        $this->CONFIG = parse_ini_file( DOCROOT . 'Core/Includes/default_config.php', true, INI_SCANNER_TYPED);
        
        # Get default configuration files of each module and parse them into the $MODULE_CONFIGS array.
        foreach ( $this->getAllModuleConfigFiles() as $module_config_file ) {
            $parsed_ini = parse_ini_file( $module_config_file, true, INI_SCANNER_TYPED );
            $module = ''; // <!-- Calculate module this file came from
            $this->MODULE_CONFIGS[$module] = $parsed_ini;
        }
        
        # Get all the configuration override files ('Config/*.ini') and parse them into the arrays.
        foreach ( $this->getAllCoreConfigFiles() as $config_file ) {
            $parsed_ini = parse_ini_file( $config_file, true, INI_SCANNER_TYPED );
            if ( isset( $parsed_ini['module'] ) ) { # This config belongs to a module
                foreach ( $parsed_ini as $section => $values ) {
                    if ( array_key_exists( $section, $this->MODULE_CONFIGS[$parsed_ini['module']] ) ) {
                        foreach ( $values as $key => $value ) {
                            if ( array_key_exists( $key, $this->MODULE_CONFIGS[$parsed_ini['module']][$section] ) ) {
                                $this->MODULE_CONFIGS[$parsed_ini['module']][$section][$key] = $value;
                            }
                        }
                    }
                }
            } else { # This config belongs to the core
                foreach ( $parsed_ini as $section => $values ) {
                    if ( array_key_exists( $section, $this->CONFIG ) ) {
                        foreach ( $values as $key => $value ) {
                            if ( array_key_exists( $key, $this->CONFIG[$section] ) ) {
                                $this->CONFIG[$section][$key] = $value;
                            }
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Get Setting
     * Returns the value of the requested Core configuration setting
     *
     * @param string $section   The configuration section the setting should be found in
     * @param string $setting   The setting that is being searched for
     * @return mixed            The value of the requested setting
     */
    public function getSetting( string $section, string $setting )
    {
        if ( isset( $this->CONFIG[$section] ) ) {
            if ( isset( $this->CONFIG[$section][$setting] ) ) {
                if ( !empty( $this->CONFIG[$section][$setting] ) ) {
                    return $this->CONFIG[ $section ][ $setting ];
                } else {
                    throw new \LengthException( "Requested setting has empty value" );
                }
            } else {
                throw new \OutOfRangeException("Request for unknown setting value");
            }
        } else {
            throw new \OutOfRangeException( "Request for unknown setting section" );
        }
    }
    
    /**
     * Get Module Setting
     * Returns the value of a Module's configuration setting
     *
     * @param string $module    The module the requested setting belongs to
     * @param string $section   The configuration section the setting should be found in
     * @param string $setting   The setting that is being searched for
     * @return mixed            The value of the requested setting
     */
    public function getModuleSetting( string $module, string $section, string $setting )
    {
        if ( isset( $this->MODULE_CONFIGS[$module] ) ) {
            if ( isset( $this->MODULE_CONFIGS[ $section ] ) ) {
                if ( isset( $this->MODULE_CONFIGS[ $section ][ $setting ] ) ) {
                    if ( !empty( $this->MODULE_CONFIGS[ $section ][ $setting ] ) ) {
                        return $this->MODULE_CONFIGS[ $section ][ $setting ];
                    } else {
                        throw new \LengthException( "Requested module setting has empty value" );
                    }
                } else {
                    throw new \OutOfRangeException( "Request for unknown module setting value" );
                }
            } else {
                throw new \OutOfRangeException( "Request for unknown module setting section" );
            }
        } else {
            throw new \OutOfRangeException( "Request for setting value of unknown module" );
        }
    }
    
    /**
     * Set a Setting
     * Sets the value for a configuration setting, can be either an existing setting (overwrite) or a new setting
     * (create). This function does not currently save settings to file, so changes will not persist.
     *
     * @param string $section       The configuration section to set the setting in
     * @param string $setting       The setting key that is being set
     * @param mixed  $value         The value of the setting to be set
     * @return mixed                The new value of the setting (see `getSetting()`)
     */
    public function setSetting( string $section, string $setting, $value )
    {
        $this->CONFIG[$section][$setting] = $value;
        return $this->getSetting( $section, $setting );
    }
    
    /**
     * Set a Module's Setting
     * Sets the value of a configuration setting for a Module. Can either be an existing setting (overwrite) or a new
     * setting (create). This function does not currently save settings to file, so changes will not persist.
     *
     * @param string $module        The this setting belongs to
     * @param string $section       The configuration section to set this setting in
     * @param string $setting       The setting key that is being set
     * @param mixed  $value         The value of the setting to be set
     * @return mixed                The new value of the setting (see `getModuleSetting()`)
     */
    public function setModuleSetting( string $module, string $section, string $setting, $value )
    {
        $this->MODULE_CONFIGS[$module][$section][$setting] = $value;
        return $this->getModuleSetting( $module, $section, $setting );
    }
    
    /**
     * Get All Module Configuration Files
     * Returns an array of all default configuration files found within each Module
     *
     * @return array    Array of default_config.ini files
     */
    private function getAllModuleConfigFiles () : array
    {
        $config_files = glob( DOCROOT . 'Modules/*/default_config.ini' );
        return $config_files;
    }
    
    /**
     * Get All Core Configuration Files
     * Returns an array of all configuration override files found within the Config directory
     *
     * @return array    Array of .ini configuration files
     */
    private function getAllCoreConfigFiles () : array
    {
        $config_files = glob( DOCROOT . 'Config/*.ini' );
        return $config_files;
    }
}
