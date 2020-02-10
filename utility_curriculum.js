
var Curriculum = {

  getBook: function( book ) 
  {
    switch ( Number( book ) ) {
      case 0: return "Intro";
      case 1: return "Book 1";
      case 2: return "Book 2";
      case 3: return "Book 3";
      case 99: return "Non-Interchange";
      default: throw 'Invalid index (' + book + ') supplied for curriculum book';
    }
  },
  
  getUnit: function( unit )
  {
    if ( unit == 0 ) return '';//'None';
    if ( unit >= 1  && unit <= 16 ) {
      return "Unit " + unit.toString();
    }  
    throw 'Invalid index (' + unit + ') supplied for curriculum unit';
  },
  
  getBookUnit: function( book, unit ) {
    if ( book == 99 )
      return this.getBook( book );
    else 
      return this.getBook( book ) + ", " + this.getUnit( unit );
  }

};

$( '.studyBook' ).find( 'option' ).each( function( element ){ $( this ).text( Curriculum.getBook( $( this ).val() ) ); });
$( '.studyUnit' ).find( 'option' ).each( function( element ){ $( this ).text( Curriculum.getUnit( $( this ).val() ) ); });

