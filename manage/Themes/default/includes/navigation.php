<?php /* Build the navigation object */
    require_once ( DOCROOT . 'Modules/Default/DefaultNavigation.php');
    $navigation = new DefaultNavigation( array('name'=>'primaryNavigation') ); // Root Navigation item
    
    $CustomersItem = new DefaultNavigation(array(
        'name' => 'customers',
        'label' => 'Customers',
        'url' => '/customer/',
        'order' => 0
    )); $navigation->addChild( $CustomersItem );
    $BusinessesItem = new DefaultNavigation(array(
        'name' => 'business',
        'label' => 'Businesses',
        'url' => '/business/',
        'order' => 1
    )); $navigation->addChild( $BusinessesItem );
    $PosItem = new DefaultNavigation(array(
        'name' => 'pos',
        'label' => 'Point-Of-Sale',
        'url' => '/pos/',
        'order' => 2
    )); $navigation->addChild( $PosItem );
    $SupportItem = new DefaultNavigation(array(
        'name' => 'support',
        'label' => 'Support',
        'url' => '/support/',
        'order' => 1
    )); $navigation->addChild( $SupportItem );
?>
        <nav class="navbar navbar-expand-md site-header shadow-sm fixed-top">
            <div class="container-fluid w-75">
                <a class="navbar-brand" href="/">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" stroke="currentColor"
                         stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="align-top mr-2"
                         role="img"
                         viewBox="0 0 24 24" focusable="false">
                        <title>Product</title>
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M14.31 8l5.74 9.94M9.69 8h11.48M7.38 12l5.74-9.94M9.69 16L3.95 6.06M14.31 16H2.83m13.79-4l-5.74 9.94"></path>
                    </svg>
                    RewardingLoyalty
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <?php foreach ( $navigation->getChildren() as $menuItem ) { ?>
                            <li class="navbar-item">
                                <a class="nav-link" href="<?php echo $menuItem->get('url'); ?>">
                                    <?php echo $menuItem->get('label'); ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <form class="form-inline ml-5">
                        <a class="btn btn-info" style="width:150px;" href="/customer/login/">Login</a>
                    </form>
                </div>
            </div>
        </nav>
