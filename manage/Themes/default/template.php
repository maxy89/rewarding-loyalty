<?php if ( !defined('IN_APP' ) ) { header( "Location: /"); die("You cannot access this file directly."); }
require_once ( 'includes/header.html' );
require_once ( 'includes/navigation.php' ) ?>

    <main id="main-content" style="margin-top:56px;">
        <?php $this->renderContent(); ?>
        
    </main>

<?php require_once ( 'includes/footer.html' ); ?>
