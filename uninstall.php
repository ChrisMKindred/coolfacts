<?php

/*
 * Check to make sure this file is being called on deactivation.
 * Directly calling this file will fail.
/**/
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	wp_die( __( "You're doin' it wrong", 'coolfacts' ) );

/*
 * Delete the Cool Facts Transient
/**/
delete_transient( '_cool_facts' );

