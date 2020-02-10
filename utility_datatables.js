
function getTableInstance() {
  return $('#peopleTable').DataTable({
    "retrieve": true
  });
}

function getCurrentRow( element ) {
  var dataTable = getTableInstance();
  var rowElement = $( element ).closest( 'tr' );
  return dataTable.row( rowElement );
}

function getCurrentRowData( element ) {
  return getCurrentRow( element ).data();
}


function populateTable( source ) {
// NOTE: dataTableize()  function should be a callback
//
// step 1 : get the data into a table
// step 2 : let DataTable work on the table

  getTableInstance().clear().destroy();

  $.ajax({
      url: source, // "action_get_students.php",
      dataType: "json",
      cache: false,
      beforeSend:function(){
        $('#ajax-panel').html('<div class="loading"><img src="images/loading.gif" alt="Loading..." /></div>');
      },
      success: function(response) {
          $('#ajax-panel').html('');

          var appendage = "";
          $.each(response.data, function(i, item){
            appendage += createTableRow( item );
          });
          $('#peopleTable > tbody').html( appendage );

          // this is where the DataTables magic happens:
          dataTableize();
      },
      error: function(response){
        console.log( 'action_get_students.php error: ' + response );
      }
    });
}



