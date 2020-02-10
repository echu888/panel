<?php require_once 'menu.php'; ?>
<link rel='stylesheet' type='text/css' href='menu_students.css' />


<div style="display:none;"> <img id='original' src='action_get_picture.php?staff='> </div>
<?php require_once 'utility_camera.php'; ?>



<script src="utility_camera.js"></script>

<script>
    displayProfilePicture();
    $( ".camportal" ).show();
    activateCamera( 'action_save_staffpicture.php' );
</script>


<input id="profileImage" name="img" type='hidden' value="" ></input>

