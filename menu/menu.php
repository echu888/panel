<?php require_once '../auth/authenticate.php'; ?>
<?php require_once '../utility/role.php'; ?>
<?php require_once '../utility/lang.php'; ?>
<!doctype html>
<html>

<head>
<title>The Centre Panel</title>
<link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
<link rel="icon" href="../images/favicon.png" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#e25f00">
<link rel="stylesheet" type="text/css" href="../css/menu.css">
<link rel="stylesheet" type="text/css" href="../css/styles.css">
<?php if ( $_SESSION[ 'lang' ] == 'th' ) { ?> <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet"> <? } ?>
<?php if ( $_SESSION[ 'lang' ] == 'en' ) { ?> <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet"> <? } ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>


<header>
<div id='menuBar'> 
    
      <nav>
      <div id="leftMenu navigation">
          <div class='dropdown-item'> <button class='dropdown-button'> <? TR( 'groups' ); ?> </button>
          <div class='dropdown-content'>
<?php if ( Role\isTeacher() ) { ?>
                      <a href="menu/group.php?mode=my">  <? TR( 'mygroups'       ); ?> </a>
<?php } ?>
                      <a href="menu/group.php">          <? TR( 'groups'         ); ?> </a>
          </div>
          </div>
              
          <div class='dropdown-item'> <button class='dropdown-button'> <? TR( 'people' ); ?> </button>
          <div class='dropdown-content'>
                      <a href="menu/birthdays.php"> <? TR( 'birthdays'      ); ?> </a>
                      <a href="menu/students.php">  <? TR( 'students'       ); ?> </a>
                      <a href="menu/staff.php">     <? TR( 'staff'          ); ?> </a>
          </div>
          </div>
              
          <div class='dropdown-item'> <button class='dropdown-button'> <? TR( 'reports' ); ?> </button>
          <div class='dropdown-content'>
                      <a href="menu/donations.php"> <? TR( 'donations'      ); ?> </a>
                      <a href="menu/monthly.php">   <? TR( 'monthly report' ); ?> </a>    
                      <a href="menu/annual.php">    <? TR( 'annual report'  ); ?> </a>
          </div>
          </div>

      </div>
      </nav>
      
      <div id="rightMenu">

          <div class='rightside-item'>
          <div id="authorizedLogin" data-staff_id="<? SignIn\getAuthorizedStaffId(); ?>">
               <img id="authorizedLoginImage" src="<? SignIn\getAuthorizedImage();   ?>"> </img>
          </div> 

                <div class='rightside-content'>
                        <span class="languageSelector" data-language="en"><img src='images/flag_gb.png'></span>
                        <span class="languageSelector" data-language="th"><img src='images/flag_th.png'></span>
                        <img id="authorizedLoginImage" src="<? SignIn\getAuthorizedImage(); ?>"> </img>

                        <div>
                        <span id="authorizedLoginName">  <? SignIn\getAuthorizedName(); ?>  </span>
                        <span id="authorizedLoginEmail"> <? SignIn\getAuthorizedEmail(); ?> </span>
                         <a class="buttonlink" href="signout.php"><? TR( 'sign out' ); ?></a> 
                        </div>
                </div>
          </div>
      </div>
    
</div>


<script>
$( '.languageSelector' ).on( 'click', function() {
  var path = window.location.pathname + '?lang=' + $( this ).data( 'language' );
  window.location.replace( path );
});
</script>


</header> 


<!-- div id='ajax-panel'>
</div -->



