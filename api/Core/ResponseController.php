<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }


class ResponseController
{
    private $content = '';
    private $variables = array();
    private $replacements = array();
    
    public function __construct ( string $content = '', array $variables = array(), array $replacements = array() )
    {
        GLOBAL $LANG, $CONFIG;
        
        # Construct the ViewController with the given content
        if ( is_string( $content ) ) { $this->content = $content; }
        
        # Initially setup the replacements array
        if ( !is_multiArray( $replacements ) ) {
            $this->replacements = $replacements;
        }
        
        # Initiallt setup the variables array
        if ( !is_multiArray( $variables ) ) {
            $this->variables = $variables;
        }
    }
    
    public function renderOutput ()
    {
        # Called when we are ready to output content to the page
        ob_start( [$this,'replaceStrings'] );
        ob_end_flush();
    }
    
    public function renderContent ()
    {
        ob_start();
        print $this->content;
        ob_end_flush();
    }
    
    public function prepareOutput ( string $viewTemplate, array $replaceVars = array() )
    {
        $viewContents = ''; // Create an empty view
        ob_start(); // Create an output buffer to load the template into
        
        # Bring in the View Template ready for further processing
        $viewContents = file_get_contents ( DOCROOT . 'Modules/' . $viewTemplate );
        
        # Get path to view template directory, add 'Layouts', this will be layouts dir for this view template
        $viewTemplatePathinfo = pathinfo( 'Modules/' . $viewTemplate );
        $layoutsDir = $viewTemplatePathinfo['dirname'] . '/Layouts/';
        
        # Find the replacement string for the layouts and replace it with the $layoutTemplate
        preg_match_all( '#{%layout\=([A-Za-z\d]+)%}#i', $viewContents, $layoutMatches );
        foreach ( $layoutMatches[1] as $layout_match ) {
            $layoutContent = file_get_contents( $layoutsDir . $layout_match . '.html' ) ?: '';
            $viewContents = str_replace( "{%layout=$layout_match%}", $layoutContent, $viewContents );
        }
        
        # Add output variable replacements to the Class array ready for processing later
        foreach ( $replaceVars as $before => $after ) {
            $this->replacements[ $before ] = $after;
        }
        
        # Get the output buffer contents and put them into the Class $content variable
        print $viewContents;
        $this->content = ob_get_clean();
    }
    
    public function processContent ( callable $callback ) : bool
    {
        $processedContent = call_user_func( $callback, $this->content );
        if ( is_string( $processedContent ) ) {
            $this->content = $processedContent;
            return true;
        }
        return false;
    }
    
    public function processVariables ( callable $callback ) : bool
    {
        $processedVars = call_user_func( $callback, $this->variables );
        if ( !is_multiArray( $processedVars ) ) {
            $this->variables = $processedVars;
            return true;
        }
        return false;
    }
    
    public function getVar( string $variable, bool $echo = true ) : string
    {
        if ( $echo ) { echo $this->variables[ $variable ]; }
        return $this->variables[ $variable ];
    }
    
    public function setVar ( string $variable, string $value ) : string
    {
        return $this->variables[ $variable ] = (string) $value ?: '';
    }
    
    private function replaceStrings ( string $outputBuffer ) : string
    {
        foreach ( $this->replacements as $before => $after ) {
            $outputBuffer = str_replace( '{%' . $before . '%}', $after, $outputBuffer );
        }
        return $outputBuffer;
    }
}
