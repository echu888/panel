<?php require_once 'menu.php'; ?>
<!--
this page can:
-->


<script type="text/javascript" charset="utf-8" src="dist/jquery.modal.min.js" ></script>
<link rel="stylesheet" type='text/css' href="dist/jquery.modal.min.css" media="screen" />
<link rel='stylesheet' type='text/css' href='group.css' />
<link rel='stylesheet' type='text/css' href='donations.css' />


<?php if ( Role\isFinanceAdmin() ) { ?>
     <link rel="stylesheet" href="dist/poshytip-1.2/src/tip-yellowsimple/tip-yellowsimple.css" type="text/css" />
     <script type="text/javascript" charset="utf-8" src="dist/poshytip-1.2/src/jquery.poshytip.min.js" ></script>
     <link href="dist/jquery-editable/css/jquery-editable.css" rel="stylesheet"/>
     <script src="dist/jquery-editable/js/jquery-editable-poshytip.min.js"></script>
<?php } ?>

<!--script src="dist/moment.min.js"></script>
<script src="dist/jquery.selectric.min.js"></script>
<link rel="stylesheet" href="dist/selectric.css"-->

<!--
<div id="ajax-panel"> </div>
-->


<h1 class='pageTitle'> <? TR( 'donations' ); ?> </h1>








<main>

  <div id='teacherSelect'> </div>

<div class="mainViewport">

   
<div id="teacherList"> </div>

<div id="rootElement"> 

  
</div>

<div class="hidden templates"> 
     <div id="teacherSectionTemplate" class="levelOneContainer">
         <div class="teacherNameHeader"> </div>
     </div>


      
     <div id="classSectionTemplate" class="levelTwoContainer">
       <div class="flexContainer classDetails">
         <div class="flexItem startDate"> </div>
         <div class="flexItem status">    </div>
         <div class="flexItem daysTimes"> </div>
       </div>
     
         <table class="donationsTable">
         <tr> 
              <th scope='col'> Nickname             </th> 
              <th scope='col'> Name                 </th>
              <th scope='col'> Recommended<br/> Donation </th>
              <th scope='col'> Actual<br/> Donation      </th>
              <th scope='col'> Collected<br/> By         </th>
              <!--th> Finish<br/> Date          </th-->
              <th scope='col'> Actions                   </th>
         </tr>
         </table>
     </div>
     
</div>



<div id="forms">

        <form id="printDialog" class="modal" style="display:none;">
          <h1>Printing Forms for Groups</h1>
        
          <hr/>
          <input type="hidden" name="group_id"> </input>
          <button type="button"  name="printForm" value="print_attendance.php" > Print Attendance Sheet  </button>
          <button type="button"  name="printForm" value="print_donation.php"   > Print Donation Receipts </button>
          <button type='button' class='cancel'>Cancel </button>
        </form>
        


     
<!--
        <form id="donateGroup" class="modal" style="display:none;">
          <h1>Donation Status</h1>
        
          <div id='donationList'> </div>

          <div> Recommended Amount : <span id="RecommendedDonation"> </span>             </div>
          <div> Actual Amount :      <input type="text" name="ActualDonation"></input>   </div>
          <div> Donation Date :      <input type="date" name="DonationDate"</input>      </div>
    
          <hr/>
          <input type="hidden" name="group_id"> </input>
          <button type="button"  name="printForm" value="print_donation.php"   > Print Donation Receipts </button>
          <button type='button' class='cancel'>Cancel </button>
        </form>
-->
    
</div>









    </div>


<script>


function createTeacherSection( rootElement, line ) {
  var teacherSection = $( '#teacherSectionTemplate' ).clone( true );
  teacherSection.removeAttr( 'id' )
  teacherSection.removeClass( 'hidden' ); 
  teacherSection.find( '.teacherNameHeader' ).html( line.TeacherName );

  rootElement.append( teacherSection );
  
  return teacherSection;
}

function createGroupSection( teacherSectionElement, groupAttributes ) {
  var groupSection = $( '#classSectionTemplate' ).clone( true );
  groupSection.removeAttr( 'id' ).removeClass( 'hidden' ); 
  groupSection.find( '.daysTimes' ).html( groupAttributes[ "days" ] + " " + groupAttributes[ "times" ] ); 
  groupSection.find( '.startDate' ).html( 'Start Date: ' + groupAttributes[ "startDate" ] );
  groupSection.find( '.status'    ).html( 'Status: '     + groupAttributes[ "status" ] );

  teacherSectionElement.append( groupSection );

  return groupSection;
}

function getGroupAttributes( line )
{
  var groupAttributes = 
      { "days"      : TimeUtility.formatDays( line.M, line.Tu, line.W, line.Th, line.F ),
        "times"     : TimeUtility.formatTime( line.StudyTime ) + ' - ' 
                    + TimeUtility.formatTime( TimeUtility.calculateEndingTime( line.StudyTime, line.ClassMinutes ) ),
        "startDate" : line.StartDate,
        "status"    : line.Status
      }
  return groupAttributes;
}



function getActualDonationCell( line ) {
<?php if ( Role\isFinanceAdmin() ) { ?>

         return "<a href='#' class='actualDonation editable-click' "
              + "data-name='" + line.student_id 
              + "' data-pk='" + line.studentgroup_id + "'>" 
                              + line.ActualDonation + "</a>";

<?php } else {?>

         return line.ActualDonation;

<?php } ?>

}


function processLine( element, line ) {
          var collector = line.Collector != null 
                        ? line.Collector + " on " + line.DonationDate  
                        : "n/a";

          var row = "<tr data-studentgroup_id='" + line.studentgroup_id + "'>"
                  +   "<td data-label='Nick Name' >" + line.NickName                        + "</td>"
                  +   "<td data-label='Name'>" + line.FirstName + ' ' + line.LastName + "</td>"
                  +   "<td data-label='Recommended Donation'>" + line.RecommendedDonation             + "</td>"
                  +   "<td data-label='Actual Donation'>" + getActualDonationCell( line )        + "</td>"
                  +   "<td data-label='Collected By'>" + collector                            + "</td>"
                  //+   "<td>" + line.ActualFinishDate                + "</td>"
                  +   "<td data-label='Actions'>    <div class='icon printEntry'  title='Print forms'    data-group_id='" + line.group_id + "'></div>   </td>"
                  + "</tr>";
          //element.append( JSON.stringify( line )  );
          element.append( row );
}

function processDonations( rootElement, data ) {

        var currentTeacherName = '';
        var currentTeacherSection = '';
        var currentTeacherTable = '';
        var currentGroupAttributes = '';
        var currentGroupSection = '';
      
     
        $.each ( data, function( index, line ) {
          if ( currentTeacherName != line.TeacherName ) {
            currentTeacherName = line.TeacherName;
            currentTeacherSection = createTeacherSection( rootElement, line );
          }
          var nextGroupAttributes = getGroupAttributes( line );
          if ( !isEquivalent( currentGroupAttributes, nextGroupAttributes ) ) {
            currentGroupAttributes = nextGroupAttributes;
            currentGroupSection = createGroupSection( currentTeacherSection, currentGroupAttributes ); 
            currentTeacherTable = currentGroupSection.find( 'table' );
          }
          processLine( currentTeacherTable, line );
        });
}

function getDonations( id ) {

    var element = $( '#rootElement' );
    element.empty();

    var url = "../action/get_donations.php";

    if ( id != '' ) 
      url += "?id=" + id;

    $.ajax({
      type: "get",
      url:  url,
      dataType: "json",
      beforeSend:function(){
        $('#ajax-panel').html('<div class="loading"><img src="images/loading.gif" alt="Loading..." /></div>');
      } 
    })

    .done( function(data){
        $('#ajax-panel').html('Action completed!');
        //console.log( ': success' );
        //$( '#donations' ).html( JSON.stringify( data ) );
        //console.log( data );
        processDonations( element, data );
        //} 
        
    })

    .fail( function(xhr, status, error){
        $('#ajax-panel').html('Action error...');
        console.log( ': error!' + xhr.responseText );
    });
}

function teacherHandler( id ) {
  return function() {
    getDonations( id );
  }
}

<?php if ( Role\isFinanceAdmin() ) { ?>

          function bindInlineEditor() {
            $.fn.editable.defaults.mode = 'popup';
          
            $( 'table' ).editable({
                selector: 'a',
                type: 'text',
                title: 'Donation Amount:',
                url: '../action/update_donation.php'
            });
          }

<?php } ?>


function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};



function getTeachers() {
  return $.ajax({
       url: "../action/get_teachers.php",
       data: { type: "staff" },
       dataType: "json",
  });
}



function populateTeachersList() {

    var teacherPromise = getTeachers();
    teacherPromise.then( function( response ){

        var teachers = [];
        teachers.push( { text: "All", handler: teacherHandler( '' )  } );

        var id = getUrlParameter( 'id' );
        $.each( response.data, function( i, item ) {

            teachers.push( { text: item.FirstName, handler: teacherHandler( item.staff_id )  } );
        }); 

        select( '#teacherSelect', teachers, 0 );
    });
}





$(function(){  //on document ready
      populateTeachersList();
    
      //$( "#teacherList" ).change( function() {
      //    if ( this.value != 0 ) {
      //        window.location.href = window.location.href.split('?')[0] + "?id=" + this.value;
      //    }

      //});

      //select( '#teacherSelect', teachers, 0 );



<?php if ( Role\isFinanceAdmin() ) { ?>

      bindInlineEditor();

<?php } ?>

});

</script>




</div>
</main>



<footer>  
  <div class='footerSpacer'></div>
</footer>


<script>


$.modal.defaults = {
  escapeClose: true,
  clickClose: false,
  showClose: false,
};



// =========================== register ICON eventHandlers
$("#groupedPeopleContainer").on( 'click', '.editEntry', function() {
    // step 1 : populate the edit form
    // step 1 : display the modal 
    // step 1 : (if not canceled) submit the form 
    // step 1 : (if successful) update the DOM 
    // step 1 : (if NOT successful) display error message
    
    var element = $( 'form#groupForm' );
    element.trigger( 'reset' );

    element.attr( 'action', '../action/update_group.php' );
    element.find( 'h1#formTitle'                ).html( "<? TR( 'editgroup' ); ?>" );
    element.find( 'button#formSubmitButton', '' ).html( " <? TR( 'editgroup' ); ?> " );;

    var group_id         = $( this ).data( "group_id" );
    var groupElement     = $( "#group_" + group_id );
    var staff_id         = groupElement.data( 'staff_id' );

    //element.find( "input[name=staff_id]"      ).val( groupElement.data( 'staff_id' ) );
    //$( '#editFormDuration option[value="'     + groupElement.data( 'ClassMinutes' ) + '"]' ).prop( 'selected', true );
    //element.find( 'select[name=ClassMinutes]' ).val( groupElement.data( 'ClassMinutes' ) );

    element.find( "input[name=group_id]"      ).val( groupElement.data( 'group_id' ) );
    element.find( 'select[name=staff_id]'     ).val( staff_id );
    element.find( 'select[name=Room]'         ).val( groupElement.data( 'Room' ) );
    element.find( "input[name=TotalHours]"    ).val( groupElement.data( 'TotalHours' ) );
    element.find( 'input[name=StartDate]'     ).val( groupElement.data( 'StartDate' ) );
    $( '#formTimeSelector option[value="' + groupElement.data( 'StudyTime'    ) + '"]' ).prop( 'selected', true );
    $( '#formDuration option[value="'     + groupElement.data( 'ClassMinutes' ) + '"]' ).prop( 'selected', true );
    element.find( 'select[name=StartingBook]' ).val( groupElement.data( 'StartingBook' ) );
    element.find( 'select[name=StartingUnit]' ).val( groupElement.data( 'StartingUnit' ) );

    var days = [ 'M', 'Tu', 'W', 'Th', 'F' ];
    days.forEach( function( day, index ) {
      if ( groupElement.data( day ) ) element.find( "a[data-index-number=" + index + "]" )   .addClass( "selectedDay" );
                            else element.find( "a[data-index-number=" + index + "]" ).removeClass( "selectedDay" );
    });

    element.modal();
});

$("#rootElement").on( 'click', '.printEntry', function() {
    var element = $( 'form#printDialog' );
    var group_id = $( this ).data( "group_id" )
console.log( $( this ) );
    element.find( "input[name=group_id]" ).val( group_id );
    element.modal();
});

//$("#groupedPeopleContainer").on( 'click', '.finishEntry', function() {
//    var element = $( 'form#finishGroup' );
//    var group_id = $( this ).data( "group_id" )
//    element.find( "input[name=group_id]" ).val( group_id );
//    element.modal();
//});

//$("#rootElement").on( 'click', '.donateEntry', function() {
//    var element = $( 'form#donateGroup' );
//    element.trigger( 'reset' );
//
//    var group_id = $( this ).data( "group_id" )
//    element.find( "input[name=group_id]" ).val( group_id );
//
//    //element.find( 'RecommendedDonation' ).val( $( this ).data( 'recommended' )'' );  
//   
//
////    var groupElement     = $( "#group_" + group_id );
////    var studentList      = $( '.student_group_' + group_id );
////
////    var donationList = $( 'div#donationList' );
////    donationList.html( '' );
////    $.each( studentList, function( index, student ) {
////   
////      console.log( student );
////      jQuery( student ).clone().appendTo( donationList );//donationList.clone().append( student );
////
////    });
////
////
////    
////var donationEntry = 'Name: ' ;//+ groupElement.data( );
//// do we do an ajax call to get the donation data associated with this group?
//
//
//
//    element.modal();
//});












// =========================== register FORM eventHandlers

$("button[name=printForm]", "#printDialog" ).click( function() {
                      var group_id = $("form#printDialog input[name=group_id]" ).val();
                      var url = $( this ).val() + "?group_id=" + group_id;

                      window.open( url, '_blank' );

                      $.modal.close();
                  });


$("button.cancel").click( function(){ 
    $.modal.close();
});

// =========================== actions
//function performAction( form, action ) {
//
//    console.log( $( form ) );
//    console.log( form + ": " + $( form ).serialize() ); 
//
//    $.ajax({
//      type: "post",
//      url:  action,
//      data: $( form ).serialize(), 
//      beforeSend:function(){
//        $('#ajax-panel').html('<div class="loading"><img src="images/loading.gif" alt="Loading..." /></div>');
//      } 
//    })
//
//    .done( function(data){
//        $('#ajax-panel').html('Action completed!');
//        console.log( action + ': success' );
//        clearGroups();
//        getGroups();
//    })
//
//    .fail( function(xhr, status, error){
//        $('#ajax-panel').html('Action error...');
//        console.log( action + ': error!' + xhr.responseText );
//    });
//
//    $.modal.close();
//}





</script>

<script type="text/javascript" charset="utf-8" src="../utility/general.js" ></script>
<script type="text/javascript" charset="utf-8" src="../utility/time.js" ></script>
<script type="text/javascript" charset="utf-8" src="../utility/select.js" ></script>

</html>




