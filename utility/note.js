

// date code fieldValue extraDetails
notesArray = new Array();

const STUDENT_FREENOTE       = 1000; notesArray[ STUDENT_FREENOTE       ] = '%1';                         //any text
const STUDENT_APPLIED        = 1100; notesArray[ STUDENT_APPLIED        ] = 'Added to system'; //'Applied, interviewed by %1'; //*staff 
const STUDENT_WAITING        = 1102; notesArray[ STUDENT_WAITING        ] = 'Waiting to study';           //*
const STUDENT_STARTED        = 1106; notesArray[ STUDENT_STARTED        ] = 'Started class : %1 ';        //*link to class
const STUDENT_FINISHED       = 1108; notesArray[ STUDENT_FINISHED       ] = 'Finished class : %1 ';       //link to class
//[teacher] Finished class : Interchange Book 3; Units 2.8 - 4.4 (details)
const STUDENT_CANCELED       = 1110; notesArray[ STUDENT_CANCELED       ] = 'Canceled class %1';          //link to class
const STUDENT_DONATED        = 1200; notesArray[ STUDENT_DONATED        ] = 'Donated : %1';                 //amt

// life events
const STUDENT_MAJOR          = 1300; notesArray[ STUDENT_MAJOR          ] = 'Majoring in %1';             //major
const STUDENT_GRADUATED_FROM = 1302; notesArray[ STUDENT_GRADUATED_FROM ] = 'Graduated from %1';          //University
const STUDENT_MOVED_TO       = 1304; notesArray[ STUDENT_MOVED_TO       ] = 'Moved to %1';                //place
const STUDENT_WORKING_AT     = 1306; notesArray[ STUDENT_WORKING_AT     ] = 'Working at %1';              //business


//staffNotes = new Array();
const STAFF_FREENOTE         = 2000; notesArray[ STAFF_FREENOTE         ] = '%1';
const STAFF_STARTED_CLASS    = 2106; notesArray[ STAFF_STARTED_CLASS    ] = 'Started class : %1 ';       //link
const STAFF_FINISHED_CLASS   = 2108; notesArray[ STAFF_FINISHED_CLASS   ] = 'Finished class : %1 ';      //link

const STAFF_COLLECT_DONATION = 2200; notesArray[ STAFF_COLLECT_DONATION ] = 'Collected donation : %1';
//[staff] Collected donation of 1200 (details)

const STAFF_RECEIVED_WP      = 2300; notesArray[ STAFF_RECEIVED_WP      ] = 'Received work permit';
const STAFF_CANCELED_WP      = 2302; notesArray[ STAFF_CANCELED_WP      ] = 'Canceled work permit';
const STAFF_STARTED_WORK     = 2304; notesArray[ STAFF_STARTED_WORK     ] = 'Started work';
const STAFF_RECEIVED_1YR_EXT = 2306; notesArray[ STAFF_RECEIVED_1YR_EXT ] = 'Received 1-year extension';


// in the database, we store the CODE and the DETAILS
//

var RecordNote = {

  record: function( id, code, reference ) 
  {
      $.ajax({
          type: "post",
          url:  "../action/save_studentnote.php",
          data: { student_id: id, Code: code, Reference: reference }, //$( form ).serialize(), 
          beforeSend:function(){
              //$('#ajax-panel').html('<div class="loading"><img src="images/loading.gif" alt="Loading..." /></div>');
          },
          success:function(data){
              //$('#ajax-panel').html('Action completed!');
              console.log(  'recordNote: success' );
              //getTableInstance().clear().destroy();
              //populateTable();
          },
          error:function(xhr, status, error){
              //$('#ajax-panel').html('Action error...');
              console.log( 'recordNote: error!' + xhr.responseText );
          }
      });
  }

};

// for testing
//RecordNote.record( 18, STUDENT_APPLIED, "Eric" );


var RecallNotes = {

  recall: function()
  {
  }

};



function Note( json ) {
  
  //member variables
  var code;
  var timestamp;
  var message;
  var reference;
  //var details;

  //constructor: 
  this.code      = json.Code;
  this.timestamp = json.NoteTimeStamp;
  this.reference = json.Reference;
  //this.details   = json.Details;
  this.message   = notesArray[ this.code ];
  

  this.getTimestamp = function()
  { 
    //return this.timestamp;
    var date = new Date( this.timestamp );
    var options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString( 'en-gb', options );
  }
  this.getCode = function() 
  { 
    return this.code;
  }
  this.getMessage = function()
  {
    var note = this.message;
    var reference = '';

    switch ( this.code ) {

        case STUDENT_STARTED:
        case STUDENT_FINISHED:
        case STUDENT_CANCELED:
        case STUDENT_FINISHED:
        case STAFF_STARTED_CLASS:
        case STAFF_FINISHED_CLASS: 
            reference = '<a href="../menu/view_group.php?id=' + this.reference + '" target="_blank"> view class </a>';
            break;

        case STUDENT_DONATED:
        case STAFF_COLLECT_DONATION:
            reference = '<a href="../menu/donations.php?id=' + this.reference + '" target="_blank"> view donation </a>';
            break;

        default:
            reference = this.reference;
    }

    return note.replace( "%1", reference );
  }

  return this;
};



function displayNotes( titleSelector, contentSelector, type, name, id, data ) {

    var title = '<div class="personInfoTitle">' + name + '</div>';
    title += "<img class='profilePic' src='../action/get_picture.php?" + type + "=" + id + "'>";

    var content = $( '<div class="personInfoContent"></div>' );
    $.each ( data, function( index, line ) {
      var note = new Note( line );
      var timestamp   = "<div class='personInfoTimestamp'>" + note.getTimestamp()     + "</div>";
      var noteContent = "<div class='personInfoNote'>"      + note.getMessage()       + "</div>";
      content.append  ( "<div class='personInfoRow'>"       + timestamp + noteContent + "</div>" );
    });
    if ( data.length == 0 ) {
      content += "<? TR( 'nonotes' ); ?>";
    }

    var personInfo = $( ".personInfo" );

    personInfo.empty();
    personInfo.append( title );
    personInfo.append( content );
    $( contentSelector ).html( content );
}



// for testing
//var string1 = {"studentnote_id":1,"student_id":18,"Code":1000,"Reference":"A really good student."                 ,"NoteTimeStamp":"2017-05-03 23:05:53"};
//var string2 = {"studentnote_id":2,"student_id":18,"Code":1000,"Reference":"Missed a lot of class this time around!","NoteTimeStamp":"2017-05-03 23:18:24"};
//var note1 = new Note( string1 );
//var note2 = new Note( string2 );
//document.write( note1.getTimestamp() + ' [' + note1.getCode() + ']: ' + note1.getMessage() + '<br/>');
//document.write( note2.getTimestamp() + ' [' + note2.getCode() + ']: ' + note2.getMessage() + '<br/>');
//document.write( note2.getTimestamp() + ' [' + note2.getCode() + ']: ' + note2.getMessage() + '<br/>');


