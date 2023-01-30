<?php

// static class
class CSRF
{
    /**
     * Generate CSRF token
     */
    public static function generateToken( $prefix = '')
    {   
        /**
         * let's say the $prefix = signup_form
         * Then $_SESSION['signup_form_csrf_token']
         */
        if ( !isset( $_SESSION[ $prefix . '_csrf_token' ] ) ) {
            $_SESSION[ $prefix . '_csrf_token' ] = bin2hex( random_bytes(32) );
        }
    }

    /**
     * Verify CSRF token - make sure it's match with the one provided in form data
     */
    public static function verifyToken( $formToken, $prefix = '')
    {
        if ( isset( $_SESSION[ $prefix . '_csrf_token' ] ) && $formToken === $_SESSION[ $prefix . '_csrf_token'] ) {
            return true; 
        }
        return false;
    }

    /**
     * Retrieve existing CSRF token ( if available )
     */
    public static function getToken( $prefix = '')
    {
        if ( isset( $_SESSION[ $prefix . '_csrf_token' ] ) ) {
            return $_SESSION[$prefix . '_csrf_token'];
        }
        return false;
    }

    /**
     * Remove CSRF token
     */
    public static function removeToken( $prefix = '')
    {
        if ( isset( $_SESSION[ $prefix . '_csrf_token' ] ) ) {
            unset( $_SESSION[ $prefix . '_csrf_token' ] );
        }
    }
}