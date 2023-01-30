<?php

class Authentication
{

    /**
     * To login user
     */
    public static function login( $email, $password )
    {
        $user_id = false;

        $user = DB::connect()->select(
            'SELECT * FROM users where email = :email',
            [
                'email' => $email
            ]
        );

        // if $user is valid, then return $user array
        if ( $user ) {
            // proceed to verify password
            if ( password_verify( $password, $user['password'] ) ) {
                $user_id = $user['id'];
            }
        }

        // make sure to return the user's ID
        return $user_id;
    }

    /**
     * To sign up user
     */
    public static function signup( $name, $email , $password )
    {

        return DB::connect()->insert(
            'INSERT INTO users (name,email,password)
            VALUES (:name, :email, :password)',
            [
                'name' => $name,
                'email' => $email,
                'password' => password_hash( $password, PASSWORD_DEFAULT ),
            ]
        );

    }

    /** 
     * To logout user
     */
    public static function logout()
    {
        unset( $_SESSION['user'] );
    }

    /**
     * check if user is logged in or not
     */
    public static function isLoggedIn() 
    {
        return isset( $_SESSION['user'] );
    }

    /**
     * assign user's session
     */
    public static function setSession( $user_id )
    {
        // load the user data from database based on $user_id provided
        $user = DB::connect()->select(
            'SELECT * from users where id = :id',
            [
                'id' => $user_id
            ]
        );

        // assign it to $_SESSION['user']
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    }

    /**
     * Retrieve the user's role
     */
    public static function getRole()
    {
        if ( self::isLoggedIn()){
            return $_SESSION['user']['role'];
        }
        return false;
    }

    /**
     * Check if the current logged in user is admin
     */
    public static function isAdmin()
    {
        return self::getRole() == 'admin';
    }

    /**
     * Check if the current logged in user is editor
     */
    public static function isEditor()
    {
        return self::getRole() == 'editor';
    }

        /**
     * Check if the current logged in user is user
     */
    public static function isUser()
    {
        return self::getRole() == 'user';
    }


    /**
     * to control user's access
     * 
     * $role can be 'admin', 'editor' or 'user';
     */
    public static function whoCanAccess($role)
    {
        // make sure user is logged in
        if( self::isLoggedIn() ){
            switch ($role) {
                // if the $role is admin
                case 'admin':
                    return self::isAdmin();
                case 'editor':
                    return self::isEditor() || self::isAdmin();
                case 'user':
                    return self::isUser() || self::isEditor() || self::isAdmin();
           } // end - switch
        }
        // if no condition met, we'll return false
        return false;
    }

    public static function callAPI( $api_url = '', $method = 'GET', $formdata = [], $headers = [] ) {

        // init curl
        $curl = curl_init();
    
        // assign it to curl props
        $curl_props = [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_CUSTOMREQUEST => $method
        ];
    
        // if $formdata is not empty, then we'll add in a new key called "CURLOPT_POSTFIELDS"
        if ( !empty( $formdata ) ) {
            $curl_props[ CURLOPT_POSTFIELDS ] = json_encode( $formdata );
        }
    
        // if $headers is not empty, then we'll add in a new key called "CURLOPT_HTTPHEADER"
        if ( !empty( $headers ) ) {
            $curl_props[ CURLOPT_HTTPHEADER ] = $headers;
        }
    
        // setup curl
        curl_setopt_array( $curl, $curl_props );
    
        // execute curl
        $response = curl_exec( $curl );
    
        // catch error
        $error = curl_error( $curl );
    
        // close curl
        curl_close( $curl );
    
        if ( $error )
            return 'API not working';
    
        return json_decode( $response );
    }
    
}