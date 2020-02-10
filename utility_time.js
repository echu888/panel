var TimeUtility = {
  
  formatDate: function( date ) {
    if ( date == "0000-00-00" )
      return "no date";
    else 
      return date;
  },
  
  formatDays: function( M, Tu, W, Th, F ) {
    var finalString = "";
    if ( M && Tu && W && Th && F ) {
      finalString = "<span class='day'> M-F </span>";
    }
    else if ( M && Tu && W && Th && !F ) {
      finalString = "<span class='day'> M-Th </span>";
    }
    else if ( !M && Tu && W && Th && F ) {
      finalString = "<span class='day'> Tu-F </span>";
    }
    else if ( !M && !Tu && !W && !Th && !F ) {
      finalString = "(no days)";
    }
    else {
      if ( M  ) finalString += "<span class='day'> M  </span>";
      if ( Tu ) finalString += "<span class='day'> Tu </span>";
      if ( W  ) finalString += "<span class='day'> W  </span>";
      if ( Th ) finalString += "<span class='day'> Th </span>";
      if ( F  ) finalString += "<span class='day'> F  </span>";
    }
    return finalString;
  },
  
  twoDigitZeroPad: function( number ) 
  {
    return ( number < 10 ? "0" : ""  ) + number.toString() 
  },

  convertTime12to24: function( timeString ) 
  {
    console.log( 'convertTime12to24 on string [' + timeString + ']' );
    //  input: 02:00 pm
    // output: 14:00:00
    
    // verify input format before doing anything else
    if ( timeString.split( ":" ).length == 3 )
    {
      console.log( timeString + " is already in 24-hour format, MIRITE?" );
      return timeString;
    } 
    
    const HOURS_POSITION   = 0;
    const MINUTES_POSITION = 1;
    const AMPM_POSITION    = 1;
    
    var am            = timeString.split(' ')[ AMPM_POSITION ].toUpperCase() == "AM";
    var hoursMinutes  = timeString.split(':');
    var hours         = parseInt( hoursMinutes[ HOURS_POSITION   ] ) + ( am ? 0 : 12 ); 
    var minutes       = parseInt( hoursMinutes[ MINUTES_POSITION ] );
    var newTimeString = twoDigitZeroPad( hours ) + ":" + twoDigitZeroPad( minutes ) + ":00";
  
    console.log( "Converted '" + timeString + "' to '" + newTimeString + "'" );
    return newTimeString;
  },
  
  formatTime: function( timeString )
  {
    var arbitraryDate = "January 01, 2010";
    var time = new Date( arbitraryDate + " " + timeString );
    var pm = time.getHours() >= 12 ? true : false;  
    if ( pm ) {
      time.setHours( time.getHours() - 12 );
    }
    newString = time.toTimeString().split(' ')[0];
    var newStringComponents = newString.split(':');
  
    hours = Number( newStringComponents[ 0 ] ).toString(); // strip leading zero from hours
    minutes = newStringComponents[ 1 ];
  
    // typically display only the hour
    if ( minutes == "00" )
      return hours
    else 
      return hours + ":" + minutes;
  },
  
  calculateEndingTime: function( startTimeString, durationInMinutes ) {
    
    var arbitraryDate = "January 01, 2010";
    var startTime = new Date( arbitraryDate + " " + startTimeString );
    var endTime = startTime;
    
    // convert duration from hours to minutes, 
    // and then calculate the hours and minutes segments
    //var durationInMinutes = durationInHours * 60;
    var hoursToAdd = durationInMinutes / 60;
    var minutesToAdd = durationInMinutes % 60;
  
    endTime.setHours( startTime.getHours() + hoursToAdd );
    endTime.setMinutes( startTime.getMinutes() + minutesToAdd );
  
    var endTimeString = endTime.toTimeString().split(' ')[0];
    return endTimeString;
  }
  
};
