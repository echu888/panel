
// elementName is styled by         '.selectContainer'
// individual items are styled by   '.selectItem'
// selected item is styled by       '.selectedItem'

function select( elementName, items, initial ) 
{
  var element = $( elementName ); 
  element.addClass( 'selectContainer' );
 
  var elementTemplate = $( '<div>', { 
                           class: 'selectItem' 
                        });

  for ( i = 0; i < items.length; i++ ) {
    
    /// create an individual select element 
    var thisElement = elementTemplate.clone();
    thisElement.html( items[ i ].text );

    /// add 'click' handler to deselect all other items, select current item, and fire the handler
    thisElement.on( 'click', items[ i ].handler );
    thisElement.on( 'click', function(){                
        $( ".selectItem" ).removeClass( 'selectedItem' );
        $( this ).addClass( 'selectedItem' );           
      });

    /// fire the handler if this is the default 
    if ( i == initial ) { 
      thisElement.trigger( 'click' );
    }

    element.append( thisElement );
  }
}

