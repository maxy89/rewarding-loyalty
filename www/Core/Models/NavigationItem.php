<?php  if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
/**
 * MyMVC Framework Application
 * NavigationItem Trait : Defines a menu item contained within a menu or a menu item container (root item)
 */

trait NavigationItem
{
    protected $name = '';           # Identifier name of this NavigationItem, useful as html class or id
    protected $label = '';          # Display Name of the NavigationItem
    protected $url = '';            # Link URL NavigationItem should point to
    protected $disabled = false;    # Link will be hidden when set to disabled ($disabled = true)
    protected $children = array();  # An array of child NavigationItem items, ie. sub-menus
    protected $level = -1;          # The level of the menu that this NavigationItem is at
    protected $order = 0;           # Position this item site in the order of NavigationItems
    protected $root = true;         # True if this is the top most NavigationItem
    protected $parent = false;      # The parent NavigationItem that this is a child of, false if root
    protected $badge = '';          # An optional badge displayed to the right of a menu item's label
    protected $icon = '';           # An optional icon displayed next to a menu item
    protected $headingHtml = null;  # Raw HTML to be rendered in a menu item's heading area
    protected $bodyHtml = null;     # Raw HTML to be rendered in a menu item's body area
    protected $footerHtml = null;   # Raw HTML to be rendered in a menu item's footer area
    
    /**
     * NavigationItem constructor
     * Constructs a navigation menu item from the given information
     *
     * @param array $menuItemArray An array containing the values required for the menu
     */
    public function __construct ( $menuItemArray = array() )
    {
        foreach ( $menuItemArray as $menuItemKey => $menuItemValue ) {
            if ( $menuItemKey == 'children' ) {
                $this->setChildren( $menuItemArray['children'] );
            } elseif ( $menuItemKey == 'headingHtml' || $menuItemKey == 'bodyHtml' || $menuItemKey == 'footerHtml' ) {
                $this->set( $menuItemKey, $menuItemValue, true );
            } else {
                $this->set( $menuItemKey, $menuItemValue );
            }
        }
        return $this;
    }
    
    /**
     * Getting Information
     * Functions used for obtaining information about the navigation menu item
     */
    public function get ( string $property, bool $html = false )
    {
        if ( isset( $property ) && !is_null( $property ) ) {
            if ( $html ) { return htmlspecialchars_decode( $this->$property ); }
            else { return $this->$property; }
        }
        
        return null;
    }
    
    /**
     * Has / Is Information
     * Functions to determine if a property has a particular variable
     */
    public function has ( string $property ) : bool
    {
        # Array of Properties that are valid for this check method
        $valid_properties = array( 'parent' );
        
        if (
            ( in_array( $property, $valid_properties ) ) &&     // Is this a valid property to check with this method?
            ( isset( $this->$property ) ) &&                    // Is this property actually setup?
            ( $this->$property !== false )                      // Does this property have a non-false value?
        ) {
            return true;
        } else {
            return false;
        }
    }
    
    public function is ( string $property ) : bool
    {
        # Array of Properties that are valid for this check method
        $valid_properties = array( 'root' );
        
        # Is this a valid Property for this check method and is it actually setup
        if ( in_array( $property, $valid_properties ) && isset( $this->$property ) ) {
            return $this->$property;
        }
        
        return false;
    }
    
    /**
     * Setting Information
     * Functions used for setting the properties of the navigation menu item
     */
    public function set ( string $property, $value, bool $html = false )
    {
        # Array of properties that are allowed to be setup
        $valid_properties = array( 'name', 'label', 'url', 'level', 'parent', 'disabled', 'children' );
        
        # Is this a Property we are allowed to set the value of?
        if ( in_array( $property, $valid_properties) ) {
            if ( $html ) { $this->$property = htmlspecialchars( $property ); }
            else { $this->$property = $value; }
            return $this->get( $property );
        }
        
        return null;
    }
    
    public function setRoot ( bool $root ) : bool {
        if ( ( $root ) && ( !$this->has('parent') ) ) { $this->root = true; }
        if ( ( !$root ) && ( $this->has('parent') ) ) { $this->root = false; }
        return $this->get( 'root' );
    }
    
    /**
     * Enable or Disable
     * Functions used to enable or disable menu items. Disabled items are hidden or not clickable
     */
    public function enable ( bool $enable = true ) : bool {
        if ( $enable ) { $this->set( 'disabled', false); }
        else { $this->set( 'disabled', true ); }
        return $this->isEnabled();
    }
    
    public function disable ( bool $disable = true ) : bool {
        if ( $disable ) { $this->set( 'disabled', true ); }
        else { $this->set( 'disabled', false ); }
        return $this->isDisabled();
    }
    
    public function isEnabled () : bool { return !$this->get( 'disabled' ); }
    public function isDisabled () : bool { return $this->get( 'disabled' ); }
    
    /**
     * Children
     * Functions used to manage the child navigation menu items associated with this item.
     */
    public function getChildren () : array { return $this->get( 'children' ); }
    
    public function setChildren ( array $children ) : array {
        foreach ( $children as $child ) { $this->addChild( $child ); }
        return $this->getChildren();
    }
    
    public function hasChildren() : bool {
        if ( ( is_array($this->children) ) && ( count($this->children) > 0 ) ) { return true; } return false;
    }
    
    public function addChild ( self $child ) : array {
        $child->set( 'parent', $this );           // Set child's parent to this item
        $child->set( 'level', $this->level + 1 );   // Set child's level to be 1 lower than this
        $child->setRoot( false );             // Child items cannot be root
        $this->children[] = $child;           // Add given child item to array
        return $this->get( 'children' );
    }
    public function removeChild ( string $name ) : array {
        foreach ( $this->children as $k => $child ) {
            if ( $child->getName() == $name ) {
                unset( $this->children[$k] );
                $this->children = array_values( $this->children );
            }
        }
        return $this->get( 'children' );
    }
    
    /**
     * @TODO: Add functions to allow for reordering of the menu items
     *      - sort( $sortChildren = true ) :
     *        - Sort the menu items by $order value,
     *        - $sortChildren - Sorts child items by their $order value,
     *      - reverseOrder() : Reverse the order of the menu items (ie. last becomes first, first becomes last)
     *      - moveUp() : Move this item up in the sort order, moving that item down
     *      - moveDown() : Move this item down in the sort order, moving that item up
     *      - moveToFront() : Move this menu item into the first position of the sort order
     *      - moveToBack() : Move this menu item into the last position of the sort order
     */
}
