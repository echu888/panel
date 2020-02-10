<?php require_once 'menu.php'; ?>
<?php
//this page can:
//DONE 1. create a new group
//DONE 2. edit details of an existing group
//DONE 3. add and remove people from a group
//          (use sliding panel for the "waiting placement", and don't engage dragula until the sliding panel comes out
//          (no drag example : https://github.com/bevacqua/dragula/issues/422 )
//
//     4. list groups by 
//        a. starting week
//        b. grouped by teacher
//DONE    c. in columns by teaching time (1pm - 7pm)
//     5. view student details and notes by clicking on their names
//     6. add notes for each group
//     7. show current number of groups, current number of students
//
//
//add a container for groups with NO STARTING DATE (for Fad to keep friends together)

$singleTeacherMode = ( $_GET[ 'mode' ] == 'my' && Role\isTeacher() );
?>

<link rel='stylesheet' type='text/css' href='group.css' />
<script defer type="text/javascript" charset="utf-8" src="utility_note.js" ></script>

<?php 
if ( Role\isGroupAdmin()  ) { 
?>
    <script type="text/javascript" charset="utf-8" src="dist/jquery.modal.min.js" ></script>
    <link rel="stylesheet" type='text/css' href="dist/jquery.modal.min.css" media="screen" />
    <link rel='stylesheet' type='text/css' href='dist/dragula.min.css' />
    <link rel='stylesheet' type='text/css' href='dist/rome.css' />
    <link rel="Stylesheet" href="dist/weekLine/styles/jquery.weekLine.css" />
<?php 
} 
?>


<!--script src="dist/moment.min.js"></script>
<script src="dist/jquery.selectric.min.js"></script>
<link rel="stylesheet" href="dist/selectric.css"-->



<h1 class='pageTitle'> <?php $singleTeacherMode ? TR( 'mygroups' ) : TR( 'groups' ); ?> </h1>


<div id="ajax-panel"> </div>





<main>

<div class="mainViewport">



    <div class="leftPlaceholder roundedContainer">
      <h2 id='leftPlaceholderTitle'> <? TR( 'waiting placement' );  ?> : <span id="numberWaitingPlacement"> </span></h2>

      <div id="ungroupedPeopleContainer">
        <div class="dragula-container" id="ungroupedPeople" data-group_id="ungrouped"> </div>
      </div>
    </div>
    
    
    <div class="roundedContainer" id='groupedPeopleContainer'> 


        <div class="options">
            <? TR( 'addstudentsswitch' ); ?>
            <label class="switch">
              <input id="allowDraggingSwitch" type="checkbox">
              <div class="slider round"></div>
            </label>
        </div>

        <div class="options">
            <? TR( 'showhidedetails' ); ?>
            <label class="switch">
              <input id="showHideStudentsSwitch" type="checkbox" >
              <div class="slider round"></div>
            </label>
        </div>


        <h2> <? TR( 'currentgroups' );  ?> : <span id="numberOfArrangedGroups"></span></h2>
        <h2> <? TR( 'studentcount' );   ?> : <span id="numberOfStudentsStudying"></span></h2>

        <div class="hourColumns">
            <div class='hourColumn'> 1:00 <div id='time_1300' > </div> </div> 
            <div class='hourColumn'> 1:30 <div id='time_1330' > </div> </div> 
            <div class='hourColumn'> 2:00 <div id='time_1400' > </div> </div>
            <div class='hourColumn'> 2:30 <div id='time_1430' > </div> </div>
            <div class='hourColumn'> 3:00 <div id='time_1500' > </div> </div>
            <div class='hourColumn'> 3:30 <div id='time_1530' > </div> </div>
            <div class='hourColumn'> 4:00 <div id='time_1600' > </div> </div>
            <div class='hourColumn'> 4:30 <div id='time_1630' > </div> </div>
            <div class='hourColumn'> 5:00 <div id='time_1700' > </div> </div>
            <div class='hourColumn'> 5:30 <div id='time_1730' > </div> </div>
            <div class='hourColumn'> 6:00 <div id='time_1800' > </div> </div>
            <div class='hourColumn'> 6:30 <div id='time_1830' > </div> </div>
        </div>
    
    
        <div id="teachersHoursColumns">
        </div>


        <h2> <? TR( 'newgroups' );     ?> : <span id="numberOfNewGroups"></span></h2>
        <h2> <? TR( 'studentcount' );  ?> : <span id="numberOfNewGroupsStudents"></span></h2>
        <h3> <? TR( 'newgroupsdesc' ); ?> </h3>

        <div id="newGroupsContainer"> 
        </div>



    </div>








    <div id="groupShellTemplate" class="groupShell" style="display:none;" >
    
        <div class="groupDetails">
             <div class="groupTeacher"> (No Teacher) </div>
             <div class="selectedDays">n/a</div> 
             <div class="StudyTime"> <span class="startTime">n/a</span> - <span class="endTime">n/a</span> </div>      

             <details>
             <summary> Students: <span class="studentCount">n/a</span>, <span class="Room">?</span> </summary>

                     <div> Start: <span class="StartDate"> n/a </span> <span class="TotalHours"> </span> </div>
                     <div class='Curriculum'></div>

<?php if ( Role\isGroupAdmin()  ) { ?>
                     <div class="groupToolbar"> 
                  <!--     <div class="icon donateEntry" title='Donation status'></div> -->
                           <div class="icon finishEntry" title='<? TR('finishEntry' ); ?>'></div>
                           <div class="icon printEntry"  title='<? TR('printEntry' ); ?>'></div>
                           <!--div class="icon noteEntry"   title='<? TR('noteEntry' ); ?>'></div-->
                           <div class="icon editEntry"   title='<? TR('editEntry' ); ?>'></div>
                  <!--         <div class="icon trashEntry"  title='Remove group'></div> -->
                     </div>
<?php } ?>

             <div class="dragula-container studentList" > </div>

             </details>
        </div>
         


    
    </div> <!--groupShellTemplate-->
 









      
<?php 
// ==================================================================== ADMIN ROLE 
if ( Role\isGroupAdmin()  ) { 
?>

          <div class="circle" id="addGroup"> </div>

          <form id="groupForm" class="modal sanitized" style="display:none;" >
            <h1 id='formTitle'></h1>
          
            <div class='formLine'> <label> <? TR('teacher' ); ?> 
              <select class='formStaffId' id="staff_id" name="staff_id" > </select> </label> 
            </div>
            <div class='formLine'> Room:  <select name='Room'> 
                                                <option value='None'      >   None     </option>
                                                <option value="Room 1"    > Room 1     </option>
                                                <option value="Room 2"    > Room 2     </option>
                                                <option value="Room 3"    > Room 3     </option>
                                                <option value="Room 4"    > Room 4     </option>
                                                <option value="Room 5"    > Room 5     </option>
                                                <option value="Room 6"    > Room 6     </option>
                                                <option value="Room 7"    > Room 7     </option>
                                                <option value="Room 8"    > Room 8     </option>
                                                <option value="Room 9"    > Room 9     </option>
                                                <option value="Coffee Bar"> Coffee Bar </option>
                                                <option value="Other"     > Other      </option>
                                          </select>
            </div>
          
            <hr/>
          
            <div class='formLine'> Study Days: 
                 <span class='dayspicker'></span> 
                 <input type="hidden" name="M"  value="0"> </input>
                 <input type="hidden" name="Tu" value="0"> </input>
                 <input type="hidden" name="W"  value="0"> </input>
                 <input type="hidden" name="Th" value="0"> </input>
                 <input type="hidden" name="F"  value="0"> </input>
            </div>
            <div class='formLine'> Study Time:      <select id='formTimeSelector' name='StudyTime' > 
                                                           <option value="13:00:00"> 1:00pm </option>
                                                           <option value="13:30:00"> 1:30pm </option>
                                                           <option value="14:00:00" selected='selected'> 2:00pm </option>
                                                           <option value="14:30:00"> 2:30pm </option>
                                                           <option value="15:00:00"> 3:00pm </option>
                                                           <option value="15:30:00"> 3:30pm </option>
                                                           <option value="16:00:00"> 4:00pm </option>
                                                           <option value="16:30:00"> 4:30pm </option>
                                                           <option value="17:00:00"> 5:00pm </option>
                                                           <option value="17:30:00"> 5:30pm </option>
                                                           <option value="18:00:00"> 6:00pm </option>
                                                           <option value="18:30:00"> 6:30pm </option>
                                                    </select> 
            </div>
            <div class='formLine'> Study Duration:  <select id='formDuration' name='ClassMinutes'> 
                                                           <option value="60"> 1 hour   </option>
                                                           <option value="90"> 1.5 hours</option>
                                                           <option value="120">2 hours  </option>
                                                    </select>  
            </div>
            <hr/>
          
        <!--    <div class='formLine'> <label> <? TR( 'startdate'   ); ?> <input name="StartDate"   type="date"                                         > </label> </div> -->
            <div class='formLine'> <label> <? TR( 'startdate'   ); ?> <input name="StartDate"    id="StartDate"                                     > </label> </div>
            <div class='formLine'> <label> <? TR( 'totalhours'  ); ?> <input name="TotalHours"   type="number" min="1" max="20" value="20" required > </label> </div>
          
            <hr/>
            <div class='formLine'> Starting Book:   <select id='interchangeBook' class='studyBook' name='StartingBook'> 
                                                           <option value="0"> </option>
                                                           <option value="1">  </option>
                                                           <option value="2">  </option>
                                                           <option value="3">  </option>
                                                           <option value="99"> </option>
                                                    </select>  

<!--
            <div class='formLine'>          Unit:   <input type="number" class='interchangeCurriculum' name="StartingUnit"    min="1" max="16">
            </div>
            <div class='formLine'>          Section:   <input type="number" class='interchangeCurriculum' name="StartingSection" min="1" max="16">
            </div>

-->
                                            Unit:   <select                      class='studyUnit' name='StartingUnit'> 
                                                           <option value="0"></option> 
                                                           <option value="1"></option>
                                                           <option value="2"></option>
                                                           <option value="3"></option>
                                                           <option value="4"></option>
                                                           <option value="5"></option>
                                                           <option value="6"></option>
                                                           <option value="7"></option>
                                                           <option value="8"></option>
                                                           <option value="9"></option>
                                                           <option value="10"></option>
                                                           <option value="11"></option>
                                                           <option value="12"></option>
                                                           <option value="13"></option>
                                                           <option value="14"></option>
                                                           <option value="15"></option>
                                                           <option value="16"></option>
                                                    </select>  
            </div>

            <div class="formLine">
            <div id='continuingStudentList'></div>
            </div>
          
            <input type="hidden" name="group_id"> </input>
            <button id='formSubmitButton' type='submit'></button>
            <button type='button' class='cancel'>        <? TR( 'cancel'   ); ?> </button>
          </form>
          
          
          
          
          
          
          
          
          <form id="deleteGroup" class="modal" style="display:none;">
            <h1>Remove Group?</h1>
            <div>
              Are you sure you want to delete this group?  Any students in this group will be set to "waiting placement" again.
            </div>
          
            <hr/>
            <input type="hidden" name="group_id"> </input>
            <button type='submit' >Remove Groups - Students Don't Want to Study</button>
            <button type='submit' >Remove Groups - Students Still Want to Study</button>
            <button type='button' class='cancel'>Don't Delete this Group</button>
          </form>
          
          
          
          
          
          
          
          <form id="printDialog" class="modal" style="display:none;">
            <h1>Printing Forms for Groups</h1>
          
            <hr/>
            <input type="hidden" name="group_id"> </input>
            <button type="button"  name="printForm" value="print_attendance.php" > Print Attendance Sheet  </button>
            <button type="button"  name="printForm" value="print_donation.php"   > Print Donation Receipts </button>
            <button type='button' class='cancel'>Cancel </button>
          </form>
          
          
          
          <form id="finishGroup" class="modal" style="display:none;">
            <h1>Report Group Completion</h1>
          
       <!--     <div class='formLine'> <label> <? TR( 'actualfinishdate'   ); ?> <input name="ActualFinishDate"   type="date"                                > </label> </div> -->
            <div class='formLine'> <label> <? TR( 'actualfinishdate'   ); ?> <input name="ActualFinishDate"    id="ActualFinishDate"                             required > </label> </div>
          
            <div class='formLine'> Ending Book:   <select class='studyBook' name='EndingBook'> 
                                                           <option value="0"> </option>
                                                           <option value="1"> </option>
                                                           <option value="2"> </option>
                                                           <option value="3"> </option>
                                                           <option value="99"></option>
                                                    </select>  
                                            Unit:   <select class='studyUnit' name='EndingUnit'> 
                                                           <option value="0"> </option>
                                                           <option value="1">  </option>
                                                           <option value="2">  </option>
                                                           <option value="3">  </option>
                                                           <option value="4">  </option>
                                                           <option value="5">  </option>
                                                           <option value="6">  </option>
                                                           <option value="7">  </option>
                                                           <option value="8">  </option>
                                                           <option value="9">  </option>
                                                           <option value="10">  </option>
                                                           <option value="11">  </option>
                                                           <option value="12">  </option>
                                                           <option value="13">  </option>
                                                           <option value="14">  </option>
                                                           <option value="15">  </option>
                                                           <option value="16">  </option>
                                                    </select>  
            </div>
          
          
            <hr/>
            <div class='formLine'>
               <div> <? TR( 'continuingdesc' ); ?> 
                     <? TR( 'continuing'     ); ?> <span id='continuingStudentsAll'  > <? TR( 'allpeople' ); ?> </span> 
                                                   <span id='continuingStudentsNone' > <? TR( 'none'      ); ?> </span> </div>
 
               <div id='continuingStudents'> </div>
               <div id='students'></div>
              
            </div>
          
            <hr/>
            <input type="hidden" name="group_id"> </input>
            <input type="hidden" name="staff_id"> </input>
            <h3>Is this Group Finished?</h3>
            <button type='submit' >Yes </button>
            <button type='button' class='cancel'>Not finished yet </button>
          </form>
          
          
<!--       
          <form id="donateGroup" class="modal" style="display:none;">
            <h1>Donation Status</h1>
          
            <div id='donationList'> </div>
      
      
            <hr/>
            <input type="hidden" name="group_id"> </input>
            <button type="button"  name="printForm" value="print_donation.php"   > Print Donation Receipts </button>
            <button type='button' class='cancel'>Cancel </button>
          </form>
-->




          <!-- picture, level, phone number, birthdate: retrieve via ajax?--> 
          <!-- all notes, history: retrieve via ajax? --> 
          <div id="studentInfo" class="popupBubble" style="display:none;">
              <div id='studentInfoLoader'></div>
              <div id="personInfo" class="personInfo">
              </div>
          </div>



<?php 
// ================================================================================================================================
} ?>



</div>
</main>



<footer>  
  <div class='footerSpacer'></div>
</footer>


<script>


<?php if ( Role\isGroupAdmin()  ) { 
// ==================================================================== ADMIN ROLE 
?>
           
           $.modal.defaults = {
             escapeClose: true,
             clickClose: false,
             showClose: false,
           };
           
           
           
           
           // =========================== register ICON eventHandlers
           function setupForm( titleText, buttonText, action )
           {
               var element = $( 'form#groupForm' );
               //element.trigger( 'reset' );
               element[ 0 ].reset();
           
               element.attr( 'action', action );
               element.find( 'h1#formTitle'                ).html( titleText  );
               element.find( 'button#formSubmitButton', '' ).html( buttonText );;
           }
           
           $("body").on( 'click', '#addGroup', function() {
               var element = $( 'form#groupForm' );
               setupForm( "<? TR( 'addnewgroup' ); ?>", "<? TR( 'addgroup' ); ?>", 'action_save_group.php' );
               rome( StartDate ).options( { time: false } );

               element.modal();
           });
           
          
           
           $("#groupedPeopleContainer").on( 'click', '.editEntry', function() {
               // step 1 : populate the edit form
               // step 1 : display the modal 
               // step 1 : (if not canceled) submit the form 
               // step 1 : (if successful) update the DOM 
               // step 1 : (if NOT successful) display error message
               
               setupForm( "<? TR( 'editgroup' ); ?>", "<? TR( 'editgroup' ); ?>", 'action_update_group.php' );
           
               var element = $( 'form#groupForm' );
               var group_id         = $( this ).data( "group_id" );
               var groupElement     = $( "#group_" + group_id );
               var staff_id         = groupElement.data( 'staff_id' );
           
               element.find( "input[name=group_id]"      ).val( groupElement.data( 'group_id' ) );
               element.find( 'select[name=staff_id]'     ).val( staff_id );
               element.find( 'select[name=Room]'         ).val( groupElement.data( 'Room' ) );
               element.find( "input[name=TotalHours]"    ).val( groupElement.data( 'TotalHours' ) );
               //element.find( 'input[name=StartDate]'     ).val( groupElement.data( 'StartDate' ) );
               rome( StartDate ).options( { time: false, initialValue: groupElement.data( 'StartDate' ) } );
               $( '#formTimeSelector option[value="' + groupElement.data( 'StudyTime'    ) + '"]' ).prop( 'selected', true );
               $( '#formDuration option[value="'     + groupElement.data( 'ClassMinutes' ) + '"]' ).prop( 'selected', true );
               element.find( 'select[name=StartingBook]' ).val( groupElement.data( 'StartingBook' ) );
               element.find( 'select[name=StartingUnit]' ).val( groupElement.data( 'StartingUnit' ) );
               //element.find( 'input[name=StartingUnit]' ).val( groupElement.data( 'StartingUnit' ) );
           
               var days = [ 'M', 'Tu', 'W', 'Th', 'F' ];
               days.forEach( function( day, index ) {
                 if ( groupElement.data( day ) ) element.find( "a[data-index-number=" + index + "]" )   .addClass( "selectedDay" );
                                            else element.find( "a[data-index-number=" + index + "]" ).removeClass( "selectedDay" );
               });
           
               element.modal();
           });
           
           $("#groupedPeopleContainer").on( 'click', '.trashEntry', function() {
               //TODO: send AJAX request to server to "recycle bin" this corresponding person
           
               // step 1 : send AJAX request to server to "recycle bin" this person
               // step 1 : (if successful) update the DOM!
               // step 1 : (if NOT successful) display error message
           
               // pass the current table row context into the "delete" button
               // so that when it's pressed, we'll know which entry to delete
               console.log( 'Triggering delete on group #' + group_id );
           
               var element = $( 'form#deleteGroup' );
               var group_id = $( this ).data( "group_id" )
               element.find( "input[name=group_id]" ).val( group_id );
               element.modal();
           });
           
           
           $("#groupedPeopleContainer").on( 'click', '.printEntry', function() {
               var element = $( 'form#printDialog' );
               element[ 0 ].reset();
               var group_id = $( this ).data( "group_id" )
               element.find( "input[name=group_id]" ).val( group_id );
               element.modal();
           });
           
           $("#groupedPeopleContainer").on( 'click', '.finishEntry', function() {
               var element = $( 'form#finishGroup' );
               element[ 0 ].reset();
               //element.trigger( 'reset' );
           
               var group_id         = $( this ).data( "group_id" )
               var groupElement     = $( "#group_" + group_id );
               var studentList      = $( '.student_group_' + group_id );
               var staff_id         = groupElement.data( 'staff_id' );
               //console.log( groupElement );
           
               element.find( "input[name=group_id]" ).val( group_id );
               element.find( "input[name=staff_id]" ).val( staff_id );
           
               // use the starting dates to prepopulate the ending information
               var now = new Date();
               //var today = now.toISOString().substring( 0, 10 ); //< HACKY!
           
               //console.log ( today );
                
               //element.find( 'input[name=ActualFinishDate]' ).val( today );
               rome( ActualFinishDate ).options( { time: false, initialValue: now } );
               element.find( 'select[name=EndingBook]'      ).val( groupElement.data( 'StartingBook' ) ).change();
               element.find( 'select[name=EndingUnit]'      ).val( groupElement.data( 'StartingUnit' ) ).change();
           
               var studentList      = $( '.student_group_' + group_id );
               var continuingList   = $( 'div#continuingStudents' ).html( '' );
               var students         = $( 'div#students' ).html( '' );
               $.each( studentList, function( index, student ) {
              
                 //console.log( student );
           
                 var studentElement = jQuery( student );
           
                 var continuingEntry = $( '<div class="student_id_list_entry">' );
                 var student_id = studentElement.data( 'student_id' );
           
                 // maintain a list of all the students 
                 students.append( '<input name="students[]" value="' + student_id + '" type="hidden"></input>' );
           
                 // maintain a list for continuing students
                 continuingEntry.html( '<label><input name="student_ids[]" value="' + student_id + '"' 
                                       + ' class="continuingStudent" type="checkbox" checked="checked" >'
                                       + studentElement.find( '.displayName' ).html() ) + '</label>';
                 continuingList.append( continuingEntry );
               });
           
               //element.find( 'div#finishGroupStudents'         ).html( studentList );
           
               element.modal();
           });
           
           
           $( 'form#finishGroup' ).on( 'click', '#continuingStudentsAll', function() {
             $( '.continuingStudent' ).prop( 'checked', true );
           });
           
           $( 'form#finishGroup' ).on( 'click', '#continuingStudentsNone', function() {
             $( '.continuingStudent' ).prop( 'checked', false );
           });
           
           //$("#groupedPeopleContainer").on( 'click', '.donateEntry', function() {
           //    var element = $( 'form#donateGroup' );
           //    element.trigger( 'reset' );
           //
           //    var group_id = $( this ).data( "group_id" )
           //    element.find( "input[name=group_id]" ).val( group_id );
           //
           //    var groupElement     = $( "#group_" + group_id );
           //    var studentList      = $( '.student_group_' + group_id );
           //
           //    var donationList = $( 'div#donationList' );
           //    donationList.html( '' );
           //    $.each( studentList, function( index, student ) {
           //   
           //      console.log( student );
           //      //jQuery( student ).clone().appendTo( donationList );//donationList.clone().append( student );
           //
           //      var studentElement = jQuery( student );
           //
           //      // TODO make this into a table?
           //      var donationEntry = $( '<div>' );
           //      donationEntry.html( studentElement.find( '.displayName' ).html() + ' '
           //                          + '[' + studentElement.attr( 'data-recommended-donation' ) + '/' + studentElement.attr( 'data-actual-donation' ) + '] ' 
           //                          + studentElement.attr( 'data-donation-date' ) + ' ' 
           //                          + studentElement.attr( 'data-collected-by' )
           //                        );
           //
           //      donationList.append( donationEntry );
           //    });
           //
           //
           //    
           //// do we do an ajax call to get the donation data associated with this group?
           //
           //
           //
           //    element.modal();
           //});
           

           // clicking on a student brings up their information
           $( 'body' ).on( 'click', '.student', function( event ) {
               // using "tooltip" style instead of a modal 
               // https://stackoverflow.com/questions/625920/jquery-popup-bubble-tooltip

               var id = $( this ).data( 'student_id' ); 
               getStudentInfo( id );

               var element = $( '#studentInfo' );
               element.show();

               var tPosX = event.pageX;
               var tPosY = event.pageY - 90;
               element.css( {'position': 'absolute', 'top': tPosY, 'left': tPosX} );

           });
           
           // clicking anywhere else in the document hides the student information
           $( document ).mouseup( function(e) {
               hideStudentInfo( e );
           });
           $( document ).keyup( function( e ) {
               var ESC = 27;
               if ( e.keyCode == ESC ) {
                 hideStudentInfo( e );
               }
           });

           function hideStudentInfo( e ) {
               var element = $( "#studentInfo" );
           
               // if the target of the click isn't the container nor a descendant of the container
               if ( !element.is( e.target ) && element.has( e.target ).length === 0 ) 
               {
                 element.hide();
               }
           }
          
           
           //$( '#interchangeBook' ).on( 'change', function() {
           //
           //   if($(this).val() == 99 )
           //      $('.interchangeCurriculum').prop('disabled', true);
           //   else
           //      $('.interchangeCurriculum').prop('disabled', false);
           //});

           
           
           
           
           
           
           
           
           // =========================== register FORM eventHandlers
           function submitGroupForm( selector, action )
           {
                                var element = $( selector );
                                var selectedDays = $( '.dayspicker a.selectedDay' ).map( function() { return $( this ).data( 'indexNumber' ) }).get();
           
                                var days = [ 'M', 'Tu', 'W', 'Th', 'F' ];
                                days.forEach( function( day, index ) {
                                  element.find( "input[name=" + day +"]"  ).val( ( jQuery.inArray( index, selectedDays ) == -1 ) ? "0" : "1" );
                                });
                                //console.log( selectedDays );
                                performAction( selector, action );
           }
           
           
           $("form#groupForm") .on($.modal.OPEN, function( event, modal ) {
                                console.log( "groupForm focus event triggered" );
                           })
                           .submit( function() {
                                event.preventDefault();
                                var action = $( this ).attr( 'action' );
                                console.log( "groupForm submit : " + action );
                                submitGroupForm( 'form#groupForm', action );
                           });
           
//           $("form#deleteGroup").submit( function() {
//                                 event.preventDefault();
//                                 performAction( "form#deleteGroup", "action_delete_group.php" );
//                             });
           
           $("button[name=printForm]", "#printDialog" ).click( function() {
                                 var group_id = $("form#printDialog input[name=group_id]" ).val();
                                 var url = $( this ).val() + "?group_id=" + group_id;
           
                                 window.open( url, '_blank' );
           
                                 $.modal.close();
                             });
           



           $("form#finishGroup").submit( function() {
                                 event.preventDefault();
           
                                 var group_id = $("form#finishGroup input[name=group_id]" ).val();
                                 performAction( "form#finishGroup", "action_finish_group.php" );
           
           
           ///------ upon submit, create a new group (for continuing students)
                                // move the list of students from the last dialog (continuing students) into this dialog for the new class
                                 continuingStudentList     = $( '#continuingStudentList' );

                                 //continuingStudentList.html( "<div>Continuing students:</div>" );
                                 hasContinuingStudents = false;
                                 $( ".continuingStudent" ).each( function() {
                                   if ( $( this ).prop( 'checked' ) ) {
                                     continuingStudentList.append( this );
                                     hasContinuingStudents  = true;
                                   }
                                 });

                                 continuingStudentList.find( "input" ).attr( "type", "hidden" );

           /// if there are none, we won't try to continue 

                                 if ( !hasContinuingStudents ) 
                                   return;


           ///------ render the dialog for creating a new group
                                 setupForm( "<? TR( 'addcontinuinggroup' ); ?>", "<? TR( 'addgroup' ); ?>", 'action_continuing_group.php' );
           
                                 var previousGroup     = $( "#group_" + group_id );
                                 var staff_id          = previousGroup.data( 'staff_id' );
           
                                 var continuingGroup = $( 'form#groupForm' );
                                 continuingGroup.find( 'select[name=staff_id]'     ).val( staff_id );
                                 continuingGroup.find( 'select[name=Room]'         ).val( previousGroup.data( 'Room' ) );
                                 $( '#formTimeSelector option[value="' + previousGroup.data( 'StudyTime'    ) + '"]' ).prop( 'selected', true );
                                 $( '#formDuration option[value="'     + previousGroup.data( 'ClassMinutes' ) + '"]' ).prop( 'selected', true );
                                 continuingGroup.find( 'select[name=StartingBook]' ).val( previousGroup.data( 'StartingBook' ) );
                                 continuingGroup.find( 'select[name=StartingUnit]' ).val( previousGroup.data( 'StartingUnit' ) );
           
                                 var days = [ 'M', 'Tu', 'W', 'Th', 'F' ];
                                 days.forEach( function( day, index ) {
                                    if ( previousGroup.data( day ) ) continuingGroup.find( "a[data-index-number=" + index + "]" )   .addClass( "selectedDay" );
                                                                else continuingGroup.find( "a[data-index-number=" + index + "]" ).removeClass( "selectedDay" );
                                 });
           
                                 continuingGroup.modal();
           
                             });
           
           //$("form#donateGroup").submit( function() {
           //                      event.preventDefault();
           //                      //performAction( "form#donateGroup", "action_finish_group.php" );
           //                  });
           
           
           
           
           
           $("button.cancel").click( function(){ 
               $.modal.close();
           });
           



           var toggleDragula = [ disableDragula, enableDragula ];
           $("body").on( 'click', '#allowDraggingSwitch', function() {
             toggleDragula.reverse()[0](); 
           });
         

<?php 
// ================================================================================================================================
} 
?>

           var toggleStudents = [ showStudents, hideStudents ];
           var toggleDetails  = [ hideDetails, showDetails ];
           $("body").on( 'click', '#showHideStudentsSwitch', function () {
              toggleDetails.reverse()[0]();
           });
           










// =========================== 

function clearGroups() {
  $( "#newGroupsContainer" ).html( '' );
  
  var times = [ '1300', '1330', '1400', '1430', '1500', '1530', '1600', '1630', '1700', '1730', '1800', '1830' ];
  times.forEach( function( time ) { 
    $( "#time_" + time ).html( '' );
  });

  $( "#teachersHoursColumns" ).html( '' );
}


function timeDigitsOnly( timeIn ) {
  //input: 13:00
  //output: 1300
  var time = timeIn.slice( 0, 2 ) + timeIn.slice( 3, 5 ); 
  if ( ( Number( time ) >= 1300 ) && ( Number( time ) <= 1800 ) )  
    return time;
  else
    throw new Error( 'timeDigitsOnly() given an invalid value : [' + timeIn + ']' );
}


function displayGroup( group, studentCounter ) {
  console.log( 'Rendering group #' + group[ 'group_id' ]  );
  var newElement = $( "#groupShellTemplate" )
                        .clone( true )
                        .attr( 'id', 'group_' + group[ 'group_id' ] );
                        
  // use jQuery "data" to save representations of everything for easy editing later on
  $.each( group, function( index, value ) {
    if ( !$.isArray( value ) )
      newElement.data( index, value );
  });


  // each icon needs a reference to the appropriate group since we only use a single dialog for each icon
  newElement.find( "input[name=group_id]" ).val( group[ 'group_id' ] );
  newElement.find( ".donateEntry"         ).attr( 'data-group_id' , group[ 'group_id' ]   );
  newElement.find( ".printEntry"          ).attr( 'data-group_id' , group[ 'group_id' ]   );
  newElement.find( ".finishEntry"         ).attr( 'data-group_id' , group[ 'group_id' ]   );
  newElement.find( ".editEntry"           ).attr( 'data-group_id' , group[ 'group_id' ]   );
  newElement.find( ".trashEntry"          ).attr( 'data-group_id' , group[ 'group_id' ]   );

  // populate display data 
  newElement.find( ".groupTeacher"        ).html( group[ 'teacher' ]                      );
  newElement.find( ".StartDate"           ).html( TimeUtility.formatDate( group[ 'StartDate' ] )      );
  newElement.find( ".studentCount"        ).html( typeof group[ 'students' ] === 'undefined' ? '0' : group[ 'students' ].length );
  newElement.find( ".TotalHours"          ).html( '(' + group[ 'TotalHours' ] + ' hours)' );
  newElement.find( ".Room"                ).html( group[ 'Room' ]                         );
  newElement.find( ".startTime"           ).html( TimeUtility.formatTime( group[ 'StudyTime' ] )      );
  newElement.find( ".endTime"             ).html( TimeUtility.formatTime( TimeUtility.calculateEndingTime( group[ 'StudyTime' ], group[ 'ClassMinutes' ] ) ) );
  newElement.find( ".selectedDays"        ).html( TimeUtility.formatDays( group[ 'M' ], group[ 'Tu' ], group[ 'W' ], group[ 'Th' ], group[ 'F' ] ) );
  newElement.find( ".Curriculum"          ).html( Curriculum.getBookUnit( group[ 'StartingBook' ], group[ 'StartingUnit' ] ) );

  // add students into each group
  var dragula_container = newElement.find( ".dragula-container" );
  dragula_container.attr( 'data-group_id', group[ 'group_id' ] );
  if ( typeof group[ 'students' ] !== 'undefined' ) {
    studentCounter.increment( group[ 'students' ].length );
    var appendage = group[ 'students' ].join('');
    dragula_container.html( appendage );
  }

  newElement.addClass( "grid_" + group[ 'ClassMinutes' ] ).show();
  return newElement;
}

function insertEmptyCell() {
  //console.log( 'insertEmptyCell()' );
  var emptyCell = $( '<div>' ).addClass( 'grid' );
  return emptyCell;
}

function createGridRow( container, row, studentCounter ) {
  //console.log( 'createGridRow() ' );
  var element = $( '<div>' ).addClass( 'gridRow' );
  var HANDLED = 'handled';

  // we're gonna have to create a grid element for every time.  
  // but depending on the length of a particular class, we'll have to skip some, so that our
  // spacing adds up correctly.  do this by looping through the possible times.

  // let's ensure that the row is sorted by start time first
  row.sort( function( a, b ) {
      if ( a[ 'StudyTime' ] == b[ 'StudyTime' ] ) 
        return 0;
      if ( a[ 'StudyTime' ] > b[ 'StudyTime' ] ) 
         return 1;
      return -1;
  });
 
  var times = [ '1300', '1330', '1400', '1430', '1500', '1530', '1600', '1630', '1700', '1730', '1800', '1830' ];
  
  var rowNumber = 0;

  // if there are any overlapping entries, for example, two groups scheduled at the same time, or within 30 minutes
  // of each other, we'll keep those in an overflow row, and then call this function recursively to create a second
  // row and make sure we display everything.  we'll do this by duplicating the original into an overflow row, and 
  // then deleting every entry that we display.  any entries that were undisplayed will need to be arranged onto a 
  // second row.
  var overflowRow = row;

  for ( timeIterator = 0; timeIterator < times.length; timeIterator++ ) {
    //console.log( 'rowNumber = ' + rowNumber + ', row.length = ' + row.length );

    // we have no (more) entries so just insert blanks
    if ( rowNumber == row.length ) {
      element.append( insertEmptyCell() );
      continue;
    } 

    var thisEntry = row[ rowNumber ];
    //console.log( thisEntry );

    var StudyTime = timeDigitsOnly( thisEntry[ 'StudyTime' ] );

    if ( times[ timeIterator ] > StudyTime ) { 
      console.log( "current entry will be handled in new row; advancing to next entry" );
      rowNumber++;
      timeIterator--; //< re-do this loop and repeat the same time entry
      continue;
    }
    //console.log( 'times[timeIterator] = ' + times[ timeIterator ] + ', StudyTime = ' + StudyTime );

    // as we build this row, we'll either display an entry 
    // if it matches the time we're currently building, or we'll display a blank
    if ( times[ timeIterator ] ==  StudyTime ) {

      element.append( displayGroup( thisEntry, studentCounter ) );  //< don't miss it, this ADDS an entry!!
      overflowRow[ rowNumber ] = HANDLED; //< since this entry has been now been displayed, it won't be in the overflow row
      
      // depending on the length of the class, we'll skip a few entries.  
      // don't forget the for loop also increments this by one!
      var leap = ( thisEntry[ 'ClassMinutes' ] / 30 ) - 1; //< 60= 2-1+1,    90= 3-1+1,    120= 4-1+1
      timeIterator += leap;  
      //console.log( 'ClassMinutes is ' + thisEntry[ 'ClassMinutes' ] + ' and leap is ' + leap );

      // move on the next entry in this row
      rowNumber++;
    } 
    else { 
      element.append( insertEmptyCell() );
    }
  } //for timeIterator

  
  
  // this row has been proccessed and completed, we'll add it to the container
  container.append( element );

  // ... and we'll also handle anything that wasn't included on this row
  overflowRow = overflowRow.filter( function( entry ) { return entry != HANDLED; }); //< remove entries that were handled above
  if ( overflowRow.length != 0 ) {
    console.log( "createGridRow() handling overflow" );
    createGridRow( container, overflowRow, studentCounter );
  }
}


function Counter() { 
     this.count      = 0;
     this.increment = function( addends = 1 ) { this.count += addends;      };
     this.reset     = function()              { this.count = 0;             };
     this.get       = function()              { return this.count;          };
}

function displayNewGroups( groups, studentCounter ) {
  console.log( '********* displayNewGroups()' );

  var container = $( "#newGroupsContainer" );

  groups.forEach( function( group ) {
    var newGroupsElement = $( '<div>' );
    newGroupsElement.addClass( 'newGroupsElement' )
                    .append( displayGroup( group, studentCounter ) )
                    .find( 'details' ).attr( 'open', true );     
    container.append( newGroupsElement );
  });

  
}

function displayGroupsGrid( groups, studentCounter ) {
  console.log( '******** displayGroupsGrid()' );
  
  var container = $( "#teachersHoursColumns" );
  
  groups.forEach( function( row ) {
    createGridRow( container, row, studentCounter );
  });
}




function addStudent( item ) {
// example output:     ▢ Nam (Supapit Sirisuk, Pre B)

   console.log ( item );
   var student = $( '<div>' );
   student.addClass( 'draggable student' );
   student.addClass( 'student_group_' + item.group_id );
   student.attr( 'id',                       'student_' + item.student_id );
   student.attr( 'data-student_id',           item.student_id );
   student.attr( 'data-recommended-donation', item.RecommendedDonation );
   student.attr( 'data-actual-donation',      item.ActualDonation );
   student.attr( 'data-donation-date',        item.DonationDate );
   student.attr( 'data-collected-by',         item.CollectedBy );
   //student.attr( 'data-tested-level',         item.TestedLevel );

   //var studentString = '<div class="draggable student" data-student_id="' + item.student_id +'">';
  
       var studentString = '<div class="donationStatus">';
       if ( item.RecommendedDonation > 0 ) { 
         var boxImage = ( item.ActualDonation > 0 )
                      ? 'images/box_y2.png'
                      : 'images/box_n.png';
         studentString += '<img src="' + boxImage + '"/>'; 
         //uncomment to display recommended donation amount:
         //studentString += '[' + item.RecommendedDonation + '] ';
       }
       studentString+='</div>';
    
       studentString += '<div class="displayName">';
       studentString += item.NickName;
       studentString += ' (' + item.FirstName + ' ' + item.LastName;
       studentString += ', ' + item.TestedLevel + ') ';
       studentString += '</div>';

   //studentString += '</div>';
   student.append( studentString );
   return student.prop( 'outerHTML' );  //< stringify jquery object
}


// =========================== actions
function performAction( form, action ) {

    console.log( $( form ) );
    console.log( form + ": " + $( form ).serialize() ); 

    $.ajax({
      type: "post",
      url:  action,
      data: $( form ).serialize(), 
      beforeSend:function(){
        $('#ajax-panel').html('<div class="loading"><img src="images/loading.gif" alt="Loading..." /></div>');
      } 
    })

    .done( function(data){
        $('#ajax-panel').html('Action completed!');
        console.log( action + ': success' );
        clearGroups();
        getGroups();
    })

    .fail( function(xhr, status, error){
        $('#ajax-panel').html('Action error...');
        console.log( action + ': error!' + xhr.responseText );
    });

    $.modal.close();
}

function getGroupedPeople() {
  return $.ajax({
      url: "action_get_grouped.php",
      dataType: "json",
  });
}

function getUngroupedPeople() {
  return $.ajax({
      url: "action_get_ungrouped.php",
      dataType: "json",
      success:function(data){
          $('#ungroupedPeople').empty();
          var numberWaitingPlacement = 0;
          var appendage = "";
          $.each(data.data, function(i, item){
            appendage += addStudent( item );
            numberWaitingPlacement++;
          });
          $( '#numberWaitingPlacement' ).html( numberWaitingPlacement );
          $( '#ungroupedPeople' ).html( appendage );
      }
  });
}

function getTeachers() {
  return $.ajax({
      url: "action_get_teachers.php",
      data: { type: "staff" },
      dataType: "json",
  });
}

function getStudentInfo( student_id ) {
  $.ajax({
      type: "get",
      url: "action_get_studentnotes.php",
      data: { student_id: student_id },
      dataType: "json",
      beforeSend: function() 
      {
         $( '#personInfo'   ).empty();
         $( '#studentInfoLoader' ).html('<div class="loading"><img src="images/loading.gif" alt="Loading..." /></div>');
          
      }
  })
  .done( function( data ) {
      displayNotes( '#personInfoTitle', '#personInfoContent', 'student', '', student_id, data );
      console.log( data );
  })
  .always( function() { 
      $( '#studentInfoLoader' ).empty();
  });
}


<?php if ( Role\isGroupAdmin()  ) { 
// ==================================================================== ADMIN ROLE 
?>
        function addStudentToGroup( student, group ) {
          return $.ajax({
              url: "action_student_group.php",
              type: "post",
              dataType: "json",
              data: { type: "add", 
                      group_id: group, 
                      student_id: student 
                    }
          });
        }
        
        function removeStudentFromGroup( student, group ) {
          return $.ajax({
              url: "action_student_group.php",
              type: "post",
              dataType: "json",
              data: { type: "remove", 
                      group_id: group, 
                      student_id: student 
                    }
          });
        }
        
        function moveStudentToGroup( student, old_group, new_group ) {
          return $.ajax({
              url: "action_student_group.php",
              type: "post",
              dataType: "json",
              data: { type: "move", 
                      old_group_id: old_group, 
                      group_id: new_group, 
                      student_id: student 
                    }
          });
        }
        
        function deleteGroup() {
          return $.ajax({
              url: "action_delete_group.php",
              type: "post",
              dataType: "json",
              data: { group_id: group_id  },
          });
        }

<?php 
// ==================================================================== ADMIN ROLE 
} 
?>

// should we do separate queries, get groups first, then get the people in the groups?
function getGroups() {

  // these two queries are done asynchronously...
  var teacherPromise = getTeachers();
  var studentPromise = getGroupedPeople();

  // but the final query is deferred until the previous two are completed.  this uses Javascript promises.
  $.when( teacherPromise, studentPromise )
  .then( function( teacherData, studentData ){

    //update every dialog which has a list of teachers
    var teacherSelect        = $( '.formStaffId' );
    var teachersHoursColumns = $( '#teachersHoursColumns' );
    var teachers = [];
    $( teacherSelect ).html( '' );
    teacherPromise.done( function( data ) {

            $( "<option />" ).val( '' ).text( "<? TR( 'none' ); ?>" ).appendTo( teacherSelect );

            console.log( "getTeachers() done" );

            $.each(data.data, function(i, item){
              teachers[ item.staff_id ] = item.FirstName;
              $( "<option />" ).val( item.staff_id ).text( item.FirstName ).appendTo( teacherSelect );

              //var times = [ '1300', '1330', '1400', '1430', '1500', '1530', '1600', '1630', '1700', '1730', '1800', '1830' ];
              //var row = "<div class='gridRow'>";
              //times.forEach( function( time, index ) {
              //  row += '<div class="grid" id="staff_' + item.staff_id + '_time_' + time + '"> </div>'; //staff_1_time_13
              //});
              //row += "</div>";
              //teachersHoursColumns.append( row );
            });
    }).fail( function(data) {
      console.log( "getTeachers() failed!" );
    });

    var students = [];
    studentPromise.done(function(data){
            console.log( "getGroupedPeople() done" );
            $.each(data.data, function(i, item){

              if (typeof students[ item.group_id ] === 'undefined') {
                students[ item.group_id ] = [];
              }
              students[ item.group_id ].push( addStudent( item ) );

            });
    }).fail( function(data) {
      console.log( "getGroupedPeople() failed!" );
    });


    $.ajax({
        url: "action_get_groups.php",
        dataType: "json",
<?php if ( $singleTeacherMode ) { ?>
        data: { staff_id: "<?php SignIn\getAuthorizedStaffId() ?>" },
<?php } ?>
    })
    .done( function(data){
        console.log( "action_get_groups.php done" );
        var newGroups = [];
        var arrangedGroups = [];
        var numberOfArrangedGroups = 0;
        var numberOfNewGroups      = 0;
        $.each( data.data, function( i, item ) {
  

            // we're just using this as a general "data" object 
            var group =  { 'group_id':         item.group_id, 
                           'staff_id':         item.staff_id, 
                           'teacher':          teachers[ item.staff_id ], 
                           'Room':             item.Room,
                           'StartDate':        item.StartDate, 
                           'TotalHours':       item.TotalHours, 
                           'StudyTime':        item.StudyTime, 
                           'ClassMinutes':     item.ClassMinutes, 
                           'M':                item.M, 
                           'Tu':               item.Tu, 
                           'W':                item.W, 
                           'Th':               item.Th, 
                           'F':                item.F, 
                           'StartingBook':     item.StartingBook, 
                           'StartingUnit':     item.StartingUnit, 
                           'students':         students[ item.group_id ] };

            //console.log( "staff id = " + item.staff_id );
            // here, check .. if staff_id ==0, or students ==0, or Room ==0, or then keep in a "new group" container
            if ( group[ 'staff_id' ]              == 0  
              || group[ 'Room' ]                  == 'None' 
              || group[ 'StudyTime' ].length      == 0 
              || typeof students[ item.group_id ] === 'undefined' 
              || ( !group[ 'M' ] && !group[ 'Tu' ] && !group[ 'W' ] && !group[ 'Th' ] && !group[ 'F' ] ) )
            {
              // this is a NEW GROUP
              newGroups.push( group );
              numberOfNewGroups++;
          
            }
            else {

              //debugging wonky groups .. there is a possibility of groups going live with no teachers, we need to display these too
              if ( item.staff_id == null ) item.staff_id = 0;

              // this is an ARRANGED GROUP
              if (typeof arrangedGroups[ item.staff_id ] === 'undefined') {
                arrangedGroups[ item.staff_id ] = new Array();
              }
              arrangedGroups[ item.staff_id ].push( group );  
              numberOfArrangedGroups++;
              //console.log( "Arranged Group " + group[ 'group_id' ] + " staff_id: " + group[ 'staff_id' ] );

            }
        });

        var studentCounter = new Counter();

        displayGroupsGrid( arrangedGroups, studentCounter );
        $( '#numberOfArrangedGroups'   ).html( numberOfArrangedGroups );
        $( '#numberOfStudentsStudying' ).html( studentCounter.get()   );

        studentCounter.reset();

        displayNewGroups( newGroups, studentCounter );
        $( '#numberOfNewGroups'         ).html( numberOfNewGroups    );
        $( '#numberOfNewGroupsStudents' ).html( studentCounter.get() );
    })
    .fail( function(data) {
        console.log( "action_get_groups.php failed!" );
    });

  });

}

function bindDayspicker( element, themeColor ) {
  element.find( ".dayspicker" ).weekLine({
         dayLabels: ["M", "Tu", "W", "Th", "F" ],
         theme: themeColor
  });
}


function disableDragula()
{
  $( '.leftPlaceholder' ).hide();
  $( '.dragula-container' ).find( '.draggable' ).addClass('no-dnd');
  //hideStudents();
}

function enableDragula() 
{
  $( '.leftPlaceholder' ).show( 'slow' );
  $( '.dragula-container' ).find( '.draggable' ).removeClass('no-dnd');
  //showStudents();
}

function showStudents() { $( '.studentList' ).show( 'fast' ); }
function hideStudents() { $( '.studentList' ).hide(); } 
function showDetails() { $( 'details' ).attr( 'open', true); }
function hideDetails() { $( 'details' ).removeAttr( 'open' ); }




function activatePageModeSelector() 
{
   $( "#pageMode" ).change( function(){
        var url = this.value;
        console.log( url );
        window.location.href = url;
      //if(status=="1")
      //  $("#icon_class, #background_class").hide();// hide multiple sections
   });
} 



// the following is for ensuring the ungrouped student list says on the screen at all times (scrolls up but not off the screen)

    $.fn.followTo = function ( horizontalPosition, fixedWidth ) {
        var $this = this; 
        var $window = $(window);
    
        $window.scroll( function (e) {
            if ( $window.scrollTop() > horizontalPosition ) {
                $this.css({ position: 'fixed',   width: fixedWidth,     top: 0 });
              
            } else {
                $this.css({ position: 'relative' });
    
            }
        });
    };
    
    
//    var calculatedHorizontalPosition = $( 'div#menuBar'           ).outerHeight( true ) 
//                                     + $( 'h1.pageTitle'          ).outerHeight( true ) 
//                                     + $( '#leftPlaceholderTitle' ).outerHeight( true );

    var calculatedHorizontalPosition = $( 'div#ungroupedPeople' ).offset().top; 
    var containingWidth              = $( '.leftPlaceholder'    ).width(); 
    
    $( '#ungroupedPeopleContainer' ).followTo( calculatedHorizontalPosition, 
                                               containingWidth );



// =========================== 

$(function(){  //on document ready
  activatePageModeSelector();

  disableDragula();
  showStudents();
  //hideStudents();

  // get data from the database
  getUngroupedPeople();
  getGroups();



<?php if ( Role\isGroupAdmin()  ) 
{ 
// ==================================================================== ADMIN ROLE 
?>

  bindDayspicker( $( "#groupForm" ), "dark" );
  //enable dragula
  var drake = dragula({
    revertOnSpill: true,
    isContainer: function (el) {
      return el.classList.contains( 'dragula-container' );
    },
    accepts: function(el, target) { //< to toggle drag and drop capability
      return  !el.classList.contains( 'no-dnd' );
    },
    moves: function(el, container, handle) { //< to toggle drag and drop capability
      return  !el.classList.contains( 'no-dnd' );
    }
  })

  // handle various drag scenarios
  .on( 'drop', function ( element, target, source, sibling ) {
    var student_id = $( element ).data( 'student_id' );
    var origin     = $( source ) .data( 'group_id' );
    var destination = $( target ).data( 'group_id' );
    var action = "";

    if ( origin == destination ) {
      action = "none";

    } else if ( origin == "ungrouped" ) { 
      action = "insert";
      addStudentToGroup( student_id, destination );

    } else if ( destination == "ungrouped" ) { 
      action = "delete";
      removeStudentFromGroup( student_id, origin );

    } else  {
      action = "update";
      moveStudentToGroup( student_id, origin, destination );
    }

    console.log( "student_id " + student_id + " moved from group_id " + origin + " into group_id " + destination  );
    console.log( "ACTION: " + action );
  });
<?php 
// ========================================================
} 
?>


});

</script>

<?php if ( Role\isGroupAdmin() ) { ?>
<script src='dist/dragula.min.js'></script>
<script src='dist/rome.js'></script>
<script src="dist/weekLine/scripts/jquery.weekLine.min.js"></script>
<?php } ?>

<script async type="text/javascript" charset="utf-8" src="utility_time.js" ></script>
<script       type="text/javascript" charset="utf-8" src="utility_curriculum.js" ></script>
</html>




