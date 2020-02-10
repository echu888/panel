describe("utility_time", function() {
  //var player;
  //var song;

  beforeEach(function() {
    //player = new Player();
    //song = new Song();
  });


  describe("formatDate", function() {
    it("handles input of 0000-00-00", function() {
      expect( TimeUtility.formatDate( "0000-00-00" ) ).toEqual( 'no date' );
    });
  });


  describe("formatDays", function() {
    it("handles input of 0", function() {
      expect( Curriculum.getUnit( 0 ) ).toEqual( '' );
    });
    it("handles input of 1-16", function() {
      expect( Curriculum.getUnit( 16 ) ).toEqual( 'Unit 16' );
    });
    it("throws errors on other input values", function() {
      expect( function() { Curriculum.getUnit( 99 ); }).toThrow();
    });
  });

  describe("twoDigitZeroPad", function() {
    it("handles input of 0, 0", function() {
      expect( Curriculum.getBookUnit( 0, 0 ) ).toEqual( 'Intro, ' );
    });
  });


  describe("convertTime12to24", function() {
    it("handles starting at Intro, Unit 1, and adding 4 units", function() {
    //  expect( Curriculum.calculateEndingBook( 0, 1, 4 ) ).toEqual( 0 );
    //  expect( Curriculum.calculateEndingUnit( 0, 1, 4 ) ).toEqual( 4 );
    });
  });


  describe("formatTime", function() {
    it("handles input of 0, 0", function() {
      expect( Curriculum.getBookUnit( 0, 0 ) ).toEqual( 'Intro, ' );
    });
  });


  describe("calculateEndingTime", function() {
    it("handles input of 0, 0", function() {
      expect( Curriculum.getBookUnit( 0, 0 ) ).toEqual( 'Intro, ' );
    });
  });

});
