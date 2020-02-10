

$( "body" ).on( 'click', '#takePortrait', function() {
      Camera.activate();
});


var Camera = ( function() {



  return {

       clearProfilePic: function()
       {
         var canvas          = document.getElementById( 'canvas' );
         var context         = canvas.getContext( '2d' );
         context.clearRect(0, 0, canvas.width, canvas.height);
       },
       
       displayProfilePic: function( imageUrl )
       {
         var canvas          = document.getElementById( 'canvas' );
         var context         = canvas.getContext( '2d' );
         var tmpImg = new Image() ;
         tmpImg.src = imageUrl;
         tmpImg.onload = function() {
             context.drawImage( tmpImg, 0, 0 );
         };
       },
     
       activate: function() {
   
         var capture_width   = 240;
         var capture_height  = 240;
         
         var video           = document.getElementById( 'video' );
       
         var canvas          = document.getElementById( 'canvas' );
         var context         = canvas.getContext( '2d' );
         var canvasSnapshot  = canvas.cloneNode();
         canvasSnapshot.getContext( '2d' ).drawImage( canvas, 0, 0 );
       
         var constraints     = { audio: false, video: { width: capture_width, height: capture_height } }; 
         var track;

         $( ".camportal" ).show();

         function hideCamera() 
         {
                 track.stop();
                 $( "#video"        ).hide();
                 $( "#canvas"       ).show();
       
                 $( "#cancel"       ).hide();
                 $( "#snap"         ).hide();
                 $( "#reset"        ).hide();
                 $( "#upload"       ).hide();
       
                 $( "#takePortrait" ).show();
                 $( ".cambuttons"   ).hide();
         }
         function showCamera()
         {
                 $( "#video"        ).fadeIn();
                 $( "#canvas"       ).hide();
       
                 $( "#cancel"       ).show();
                 $( "#snap"         ).show();
                 $( "#reset"        ).hide();
                 $( "#upload"       ).hide();
       
                 $( "#takePortrait" ).hide();
                 $( ".cambuttons"   ).show();
         }
         function showPreview()
         {
                 $( "#video"        ).hide();
                 $( "#canvas"       ).fadeIn();
       
                 $( "#cancel"       ).show();
                 $( "#snap"         ).hide();
                 $( "#reset"        ).show();
                 $( "#upload"       ).show();
         }
       
       
             
             if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia ) {
                 navigator.mediaDevices.getUserMedia( constraints )
                 .then( function( stream ) {
                     video.srcObject = stream;
                     video.play();
                     track = stream.getTracks()[0];  // we save this off in order to STOP the camera activity later
                
                     showCamera();
                 })
                 .catch( function( err ) {
                     console.log("Video capture error: ", err.code); 
                 });
             }
       
             // Cancel
             document.getElementById("cancel").addEventListener("click", function() {
                 hideCamera();
                 context.drawImage( canvasSnapshot, 0, 0 );
             });
             
             // Get-Save Snapshot - image 
             document.getElementById("snap").addEventListener("click", function() {
                 $( '.flash' ).show() 
                              .fadeOut('slow');
       
                 context.drawImage( video, 0, 0, capture_width, capture_height );
                 showPreview();
             });
       
             // reset - clear - to Capture New Photo
             document.getElementById("reset").addEventListener("click", function() {
                 showCamera();
             });
       
       
             //function uploadAjax( dataUrl, id )
             //{
             //    $("#uploading").show();
             //    $.ajax({
             //        type: "POST",
             //        url: action,
             //        data: { 
             //           imgBase64: dataUrl,
             //           id: id
             //        }
             //    }).done(function(msg) {
             //        console.log("saved");
             //        $("#uploading").hide();
             //        $("#uploaded").show();
             //    });
             //}

             function saveToInputField( dataUrl )
             {
                 document.getElementById( 'profileImage' ).value = dataUrl;
             }

             document.getElementById("upload").addEventListener("click", function(){
       
                 hideCamera();
             
                 var dataUrl = canvas.toDataURL( "image/jpeg", 0.85 ); // ( image type, image quality )
       
                 // TEST: sending data to server using two different ways.  1. ajax call,  2. form post
                 //uploadAjax( dataUrl, id );
                 saveToInputField( dataUrl );
                 
             });
       }
  };
})();




