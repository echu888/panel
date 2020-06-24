<?php require_once 'menu.php'; ?>

<script type="text/javascript" charset="utf-8" src="../dist/jquery.modal.min.js" ></script>
<link rel="stylesheet" type='text/css' href="../dist/jquery.modal.min.css" media="screen" />
<link rel="stylesheet" type='text/css' href="../dist/rome.css" media="screen" />

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.13/b-1.2.4/b-print-1.2.4/r-2.1.1/datatables.min.css"/>
<script       type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.13/b-1.2.4/b-print-1.2.4/r-2.1.1/datatables.min.js"></script>

<script       type="text/javascript" charset="utf-8" src="../dist/rome.js" ></script>
<script defer type="text/javascript" charset="utf-8" src="../utility/note.js" ></script>
<script async type="text/javascript" charset="utf-8" src="../utility/camera.js" ></script>
<script       type="text/javascript" charset="utf-8" src="../utility/datatables.js" ></script>


<link rel='stylesheet' type='text/css' href='../css/group.css' />
<link rel='stylesheet' type='text/css' href='../css/menu_students.css' />



<h1 class='pageTitle'> <? TR( 'students' ); ?> </h1>

<div id="ajax-panel"> </div>

<div id="people">
  <table id="peopleTable" class="compact responsive stripe row-border" style="width:100%"> 
    <thead><tr>
<!-- 0 -->  <th>ID</th>
<!-- 1 -->  <th class ='small-text'><? TR( 'queue'      ); ?></th>
<!-- 4 -->  <th><? TR( 'firstname'    ); ?></th>
<!-- 5 -->  <th><? TR( 'lastname'     ); ?></th>
<!-- 2 -->  <th><? TR( 'nickname'     ); ?></th>
<!-- 3 -->  <th><? TR( 'nicknamethai' ); ?></th>
<!-- 6 -->  <th><? TR( 'testedlevel'  ); ?></th>
<!-- 7 -->  <th><? TR( 'phonenumber'  ); ?></th>
<!-- 8 -->  <th class='small-text'><? TR( 'gender'       ); ?></th>
<!-- 9 -->  <th><? TR( 'birthdate'    ); ?></th>
<!--10 -->  <th class='small-text'><? TR( 'note'        ); ?></th> 
<!--11 -->  <th class='small-text'><? TR( 'editperson'  ); ?></th> 
<!--12 -->  <th class='small-text'><? TR( 'deleteperson'); ?></th>
    </tr> </thead>
    <tbody> </tbody>
  </table>
</div>

<div id="personInfo" class="personInfo">
   <? TR( 'clickforhistory' ); ?> 
</div>


<?php if ( Role\isStudentAdmin()  ) { ?>

   <div class="circle" id="addEntry" > </div>
   
   
     
   <form id="personForm" class="modal sanitized" style="display:none;">
     <h1 id='formTitle'></h1>

       <input type="hidden" name='profileImage' id='profileImage'> </input>
<?php require_once '../utility/camera.php' ?>
       <hr/> 

       <div class='formLine'> <label> <? TR( 'firstname'   ); ?> <input name="FirstName"    type="text" placeholder="First name only"  required > </label> </div>
       <div class='formLine'> <label> <? TR( 'lastname'    ); ?> <input name="LastName"     type="text" placeholder="Last name only"   required > </label> </div>
       <div class='formLine'> <label> <? TR( 'nickname'    ); ?> <input name="NickName"     type="text"                                         > </label> </div>
       <div class='formLine'> <label> <? TR( 'nicknamethai'); ?> <input name="NickNameThai" type="text"                                         > </label> </div>
       <hr/>

       <div class='formLine'> <label> <? TR( 'phonenumber' ); ?> <input name="PhoneNumber"  type="tel"  placeholder="Eg. 0885559999"   required > </label> </div>
       <div class='formLine'> <label> <? TR( 'gender'      ); ?> <select name="Gender"  required > 
                                                                               <option value="M"> <? TR( 'male' ); ?> </option>
                                                                               <option value="F"> <? TR( 'female' ); ?> </option>
                                                                 </select></label> </div>
     <!--
       <div class='formLine'> <label> <? TR( 'birthdate'   ); ?> <input name="Birthdate"    type="date"                                required > </label> </div>
     -->
       <div class='formLine'> <label> <? TR( 'birthdate'   ); ?> <input name="Birthdate"    id="Birthdate"                             required > </label> </div>

           <div class='formLine'> <label> <? TR( 'testedlevel' ); ?> <select name="TestedLevel"  required > 
                                                                               <option value="Not Tested"> <? TR( 'nottested' ); ?> </option>
                                                                               <option value="Pre A"> Pre A </option>
                                                                               <option value="Pre B"> Pre B </option>
                                                                               <option value="1A"> 1A </option>
                                                                               <option value="1B"> 1B </option>
                                                                               <option value="2A"> 2A </option>
                                                                               <option value="2B"> 2B </option>
                                                                               <option value="3A"> 3A </option>
                                                                               <option value="3B"> 3B </option>
                                                                 </select></label> </div>
   
       <hr/>
       <input type="hidden" name="student_id">
       <button type='submit'><? TR( 'save' ); ?> </button>
       <button type='button' class='cancel'> <? TR( 'cancel' ); ?></button>
   </form>
   
   
     
   
   <form id="deletePerson" class="modal" style="display:none;">
     <h1>Delete Person?</h1>
     <div>
       Are you sure you want to delete this person?  They will be moved to the "recycle bin."
     </div>
     <input name="student_id"          type="hidden" >
   
     <hr/>
     <button type='submit' >Delete</button>
     <button type='button' class='cancel'>Cancel</button>
   </form>
   
   
   
   <form id="addNote" class="modal" style="display:none;">
     <h1>Add Note <span id='notePerson'></span></h1>
      
       <div> <label> <? TR( 'note'    ); ?> <input name="Reference"     type="text" size="50"                              > </label> </div>
   
     <input name="student_name"        type="hidden" >
     <input name="student_id"          type="hidden" >
     <input name="Code"                type="hidden" value="1000">
   
     <hr/>
     <button type='submit' >Add Note</button>
     <button type='button' class='cancel'>Cancel</button>
   </form>


<?php } ?>




<script>
function createTableRow( item ) {
  var content = '<tr class="studentRow">' 
              + '<td>' + item.student_id   + '</td> ' 
              + '<td><div class="icon queueEntry' + ( ( item.WaitingPlacement ) ? '' : ' dimmed' ) + '">' 
                                                  + ( ( item.WaitingPlacement ) ? '1' : '0' ) + '</div>  </td> ' 
              + '<td>' + item.FirstName    + '</td> ' 
              + '<td>' + item.LastName     + '</td> ' 
              + '<td>' + item.NickName     + '</td> ' 
              + '<td>' + item.NickNameThai + '</td> ' 
              + '<td>' + item.TestedLevel  + '</td> ' 
              + '<td>' + item.PhoneNumber  + '</td> ' 
              + '<td>' + item.Gender       + '</td> ' 
              + '<td>' + item.Birthdate    + '</td> ' 
              + '<td><div class="icon noteEntry" ></div></td> ' 
              + '<td><div class="icon editEntry" ></div></td> ' 
              + '<td><div class="icon trashEntry"></div></td> ' 
              + '</tr>';
  return content;
}

function dataTableize() {
          $('#peopleTable').DataTable({

              //paging  : false,
              //processing  : true,
              //responsive :  true,
              //deferRender  : true,
              //info  : true,

<?php if ( $_SESSION[ 'lang' ] == 'th' ) { ?>
              language: { url: '//cdn.datatables.net/plug-ins/1.10.13/i18n/Thai.json' },
<?php } ?> 

              order: [[ 1, "desc" ], [ 0, "desc" ]], //order by "waiting list" and then by "newest students"

              columnDefs: [
                { "targets": [  7                 ], "render"   : function( data, type, full, meta ) {
                                                                   var phone = data.substr(0, 3) + "-" 
                                                                             + data.substr(3, 3) + "-" 
                                                                             + data.substr(6); //Create format with substrings

                                                                   return ( data.length == 10 ) ? phone : data;
                                                               }
                },


                { "targets": [  1                 ], "sWidth": "18px"       },

                { "targets": [  0, -1, -2, -3     ], "orderable": false,  //don't need sorting on edit & delete columns
                                                     "sWidth": "18px"       } 
              ]
          })

<?php if ( !Role\isStudentAdmin()  ) { ?>
          .columns( [ 1, -1, -2, -3 ] ).visible( false )
<?php } ?>
          .column( 0 ).visible( false );

         //$('.dataTables_filter').addClass('pull-left');
}



<?php if ( Role\isStudentAdmin()  ) { 
// ==================================================================== ADMIN ROLE 
?>

        function performAction( form, action, redrawTable = true ) {
            event.preventDefault();
        
            $.ajax({
              type: "post",
              url:  action,
              data: $( form ).serialize(), 
              beforeSend:function(){
                $('#ajax-panel').html('<div class="loading"><img src="images/loading.gif" alt="Loading..." /></div>');
              },
              success:function(data){
                $('#ajax-panel').html('Action completed!');
                console.log( action + ': success' );
                if ( redrawTable ) {
                  //getTableInstance().clear().destroy();
                  populateTable( '../action/get_students.php' );
                }
              },
              error:function(xhr, status, error){
                $('#ajax-panel').html('Action error...');
                console.log( action + ': error!' + xhr.responseText );
              }
            });
        
            $.modal.close();
        }
        
        $.modal.defaults = {
          escapeClose: true,
          clickClose: false,
          showClose: false,
        };
        
        
        // =========================== register FORM eventHandlers
        $("form#personForm") 
                        .on($.modal.OPEN, function( event, modal ) {
                             console.log("new/edit person focus event triggered");
                             $( this ).find( "input" ).first().focus();
                        })
                        .on($.modal.CLOSE, function( event, modal ) {
                             rome( Birthdate ).hide();
                        })
                        .submit( function() {
                             var action = $( this ).attr( 'action' );
                             console.log( "personForm submit : " + action );
                             performAction( "form#personForm", action );
                        });



        $("form#deletePerson").submit( function() {
                              performAction( "#deletePerson", "../action/delete_person.php" );
                          });
        
        $("form#addNote").submit( function() {

                             //0. fill out portions of the form
                             var name    = $( '#authorizedLoginName' ).html(); 
                             var details = $( this ).find( "input[name=Reference]"    ).val();
                             details = '[' + name + '] ' + details;  
                             $( this ).find( "input[name=Reference]"  ).val( details );

                             //1. do an Ajax submit, bu don't redraw the table 
                             performAction( "#addNote",      "../action/save_studentnote.php",  false );

                             //2. redisplay the notes
                             student_id   = $( this ).find( "input[name=student_id]"   ).val();
                             student_name = $( this ).find( "input[name=student_name]" ).val();
                             retrieveNotes( student_id, student_name );

                             //3. clear the form
                             $( '#addNote' )[ 0 ].reset();
 
                          });
        
        
        $("button.cancel").click( function(){ 
            $.modal.close();
        });
        
        // do a little clean-up on form inputs:
        $("input" , "form.sanitized")
                        .blur( function() {
                             // 1. trim leading and trailing whitespace
                             var cleanString = $.trim( $( this ).val() );
        
                             // 2. do proper capitalization
                             cleanString = cleanString.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                                 return letter.toUpperCase();
                             }); 
        
                             $( this ).val( cleanString );
                        });
        
        
        // =========================== register ICON eventHandlers
        function setupForm( titleText, action )
        {
            var element = $( 'form#personForm' );
            element[0].reset();//.trigger( 'reset' );
        
            element.attr( 'action', action );
            element.find( 'h1#formTitle' ).html( titleText  );
        }
        
        $("body").on( 'click', '.noteEntry', function() {
            var rowData = getCurrentRowData( this );
            var element = $( "form#addNote" );
            element[0].reset();//.trigger( 'reset' );
        
            element.find( "input[name=student_id]"   ).val( rowData[ 0 ] );
            element.find( "input[name=student_name]" ).val( rowData[ 2 ] );
            element.find( "#notePerson"              ).html( "(" + rowData[ 2 ] + ")" );
        
            element.modal();
        });
 
        $("body").on( 'click', '#addEntry', function() {
            Camera.clearProfilePic();
            setupForm( "<? TR( 'addnewperson' ); ?>", '../action/save_student.php' );

            var element = $( 'form#personForm' );
            rome( Birthdate ).options( { time: false } );

            element.modal();
        });
       
        $("table").on( 'click', '.editEntry', function() {
            // step 1 : populate the edit form
            // step 1 : display the modal 
            // step 1 : (if not canceled) submit the form 
            // step 1 : (if successful) update the DOM 
            // step 1 : (if NOT successful) display error message
            Camera.clearProfilePic();
            setupForm( "<? TR( 'editperson' ); ?>", '../action/update_student.php' );
          
            var element = $( 'form#personForm' );
            var rowData = getCurrentRowData( this );
        
            element.find( "input[name=student_id]"  ).val( rowData[ 0 ] );
            element.find( "input[name=FirstName]"   ).val( rowData[ 2 ] );
            element.find( "input[name=LastName]"    ).val( rowData[ 3 ] );
            element.find( "input[name=NickName]"    ).val( rowData[ 4 ] );
            element.find( "input[name=NickNameThai]").val( rowData[ 5 ] );
            element.find( "select[name=TestedLevel]").val( rowData[ 6 ] );
            element.find( "input[name=PhoneNumber]" ).val( rowData[ 7 ] );
            element.find( "select[name=Gender]"     ).val( rowData[ 8 ] );
            //element.find( "input[name=Birthdate]"   ).val( rowData[ 9 ] );
            rome( Birthdate ).options( { time: false, initialValue: rowData[ 9 ] } );
        
            Camera.displayProfilePic( '../action/get_picture.php?student=' + rowData[ 0 ] );
            element.modal();

        });
        
        $("table").on( 'click', '.trashEntry', function() {
            //TODO: send AJAX request to server to "recycle bin" this corresponding person
        
            // step 1 : send AJAX request to server to "recycle bin" this person
            // step 1 : (if successful) update the DOM!
            // step 1 : (if NOT successful) display error message
        
            // pass the current table row context into the "delete" button
            // so that when it's pressed, we'll know which entry to delete
            var rowData = getCurrentRowData( this );
        
            $( "#deletePerson input[name=student_id]" ).val( rowData[ 0 ] );
        
            $( '#deletePerson' ).modal();
        
        });
        
        $("table").on( 'click', '.queueEntry', function() {
            var rowData = getCurrentRowData( this );
            var element = $( this );
            var student_id = rowData[ 0 ];
        
         // toggle the display 
            element.toggleClass( 'dimmed' );
            var action  = element.hasClass( 'dimmed' ) ? "unqueue" : "queue";

         // update datatables for proper sorting on this column
            element.html( ( action == "queue" ) ? "1" : "0" ); 
            var cell = getTableInstance().cell( element.parent() );
            cell.data( element[0].outerHTML );

         // send the request to the server
            $.ajax({
              type: "POST",
              url:  "../action/queue_student.php",
              data: { student_id:student_id, action:action },
            })
            .done( function( data ) { 
                console.log( '../action/queue_student.php : success' );
            })
            .fail( function( jqXHR, textStatus, errorThrown ) { 
            }) 
            .always( function() { 
              //alert("complete"); 
            });

        

        
        });




<?php 
// ================================================================================================================================
} ?>


function retrieveNotes( student_id, name ) {

    $.ajax({
      type: "get",
      url:  "../action/get_studentnotes.php",
      cache: false,
      data: { student_id:student_id },
      dataType: "json"
    })
    .done( function( data ) { 
       // var history = data;
       // $( '#personInfo' ).html( history );
        displayNotes( '#personInfoTitle', '#personInfoContent', 'student', name, student_id, data );
        console.log( ': success' );
    })
    .fail( function( jqXHR, textStatus, errorThrown ) { 
    }) 
    .always( function() { 
      //alert("complete"); 
    });


}

//$('#peopleTable' ).on( 'click mouseover', '.studentRow', function() {
$('#peopleTable' ).on( 'click', '.studentRow', function() {

    var rowData    = getCurrentRowData( this );
    var student_id = rowData[ 0 ];
    var name       = rowData[ 2 ];

    retrieveNotes( student_id, name );
});





//on document ready
$(function(){
  populateTable( '../action/get_students.php' );
});


</script>
</html>

