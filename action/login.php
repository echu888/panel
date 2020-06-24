<?php
session_start();
// login validation:
// 
//   1. asks Google for this person's credentials based upon a Javascript-requested token
//  
//   2. checks if the user is allowed to login via:
//      - domain         : any user who is in the proper domain is allowed to login automatically
//      - user whitelist : non-domain users can still login, if they are included in the user whitelist
//
//   3. save associated staff information if found (using email address as the connecting information)
//
//   4. the credentials are saved into a session variable so that they don't have to reverified constantly


require_once '../vendor/autoload.php'; //< for Google API 
require_once '../utility/debug.php';
require_once '../database.php';


class GoogleSignin 
{
    private $GOOGLE_CLIENT_ID = '330113539831-caf2itcjuf6gamro05pq27il51srfgr0.apps.googleusercontent.com';

    private $domainWhitelist = array ( 'thecentrethailand.org' );
        
    /// use this table in our database with at least these three columns : sub, email, approved
    // this could also be an object
    private $userWhitelist = array( //'115239337120146999153' => array( 'email' => 'chu.eric@gmail.com',             'approved' => true  )
                                  );
    

    private $response;

    public function __construct( $id_token ) 
    {
        //MSG( 'constructing GoogleSignin object with id_token ' . $id_token, __FILE__ );
          MSG( "---------------------------------------------------------------------", basename( __FILE__ ) );
                               $this->setDefaults();
        $googleResponse      = $this->checkWithGoogle( $id_token );
        $databaseInformation = $this->associateStaffInformation( $googleResponse );
                               $this->checkWhiteList( $googleResponse, $databaseInformation );
        //$databaseInformation = $this->associateStaffInformation( $googleResponse );
                               $this->saveSessionInformation( $googleResponse, $databaseInformation );
    }

    public function getResponse()
    {
        return json_encode( $this->response );
    }
   
    private function setDefaults()
    {
        $_SESSION[ 'signedIn'    ] = false;
        $_SESSION[ 'verified'    ] = false;
        $_SESSION[ 'verifiedVia' ] = "none";
        $this->response[ 'success' ] = false;
        $this->response[ 'message' ] = '';
    }
    
    private function checkWithGoogle( $javascriptToken )
    {
        $googleClient     = new Google_Client( [ 'client_id' => $this->GOOGLE_CLIENT_ID ] );
        $googleResponse   = $googleClient->verifyIdToken( $javascriptToken );
    
        if ( isset( $googleResponse[ 'sub' ] ) ) {
          $_SESSION[ 'signedIn' ] = true;
          MSG( "Asking Google whoami after client-side sign-in, Google's response => " . $googleResponse[ 'email' ], basename( __FILE__ ) );
        }
        return $googleResponse;
    }
    
    
    private function associateStaffInformation( $googleResponse )
    {
        //MSG( 'Google Response: ' . print_r( $googleResponse, true ), __FILE__ );

        $connection = getConnection();
        $statement  = $connection->prepare( "SELECT * FROM StaffGoogleLogin AS sg
                                             LEFT JOIN Staff      AS s  ON sg.staff_id = s.staff_id
                                             LEFT JOIN StaffRoles AS sr ON sg.staff_id = sr.staff_id
                                             WHERE sg.Email = :Email" );
        $statement->bindValue( ':Email',  $googleResponse[ 'email' ]  ); 
        $results    = $statement->execute();
        $row        = $statement->fetch( PDO::FETCH_ASSOC );

        //MSG( 'login associated with ' . print_r( $row, true ), __FILE__ );
        if ( is_array( $row ) && array_key_exists( 'Email', $row ) ) 
            MSG( 'login successfully associated with staff member: ' . $row[ 'FirstName' ] . ' ' . $row[ 'LastName' ], basename( __FILE__ ) );
        else
            MSG( 'login NOT associated with a staff member (email not found in database)', basename( __FILE__ ) );

        return $row;
    }

    private function checkWhitelist( $googleResponse, $databaseInformation )
    {
        // validation possibilities:
        //   1. user email exists in database as a staff member
        //   2. user email is in the domain 
        //   3. user email is on a special whitelist 

        if ( ( $databaseInformation[ 'Email' ] == $googleResponse[ 'email' ] ) && ( $databaseInformation[ 'active' ] === 1 ) ) {
          $_SESSION[ 'verified'    ] = true;
          $_SESSION[ 'verifiedVia' ] = "staff member";
        } 
        else if ( array_key_exists( 'hd', $googleResponse )  && in_array( $googleResponse[ 'hd' ], $this->domainWhitelist ) )  {
          $_SESSION[ 'verified'    ] = true;
          $_SESSION[ 'verifiedVia' ] = "domain account";
        } 
        else if ( array_key_exists( $googleResponse[ 'sub' ], $this->userWhitelist ) && $this->userWhitelist[ $googleResponse[ 'sub' ] ][ 'approved' ] ) {
          $_SESSION[ 'verified'    ] = true;
          $_SESSION[ 'verifiedVia' ] = "whitelisted account";
        } 




        if ( $_SESSION[ 'verified' ] ) {
          $this->response[ 'message' ] = $googleResponse[ 'email' ] . " is a " . $_SESSION[ 'verifiedVia' ]; 
          $this->response[ 'success' ] = true;
        }
        else {
          $this->response[ 'message' ] = "Oops! " . $googleResponse[ 'email' ] . " doesn't have access to the panel.";
          $this->response[ 'success' ] = false;
        }
     
    }

    
    private function saveSessionInformation( $googleResponse, $databaseInformation )
    {
        if ( is_array( $googleResponse ) ) {
            foreach ( $googleResponse as $key => $item ) {
                $_SESSION[ $key ] = $item;
            }
        } 

        if ( is_array( $databaseInformation ) ) {
            foreach ( $databaseInformation as $key => $item ) {
                $_SESSION[ $key ] = $item;
            }
        }
    }
}


$id_token = $_POST[ 'id_token' ];
$signin = new GoogleSignin( $id_token );
print( $signin->getResponse() );


//MSG( "session variable contains [ ");
//MSG( print_r( $_SESSION, true ) );
//MSG( "]");
//MSG( "This is session save path : " . session_save_path() . "\n" );
//MSG( "This is session id: " . session_id() . "\n" );


?>
