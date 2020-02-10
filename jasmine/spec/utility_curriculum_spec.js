describe("utility_curriculum", function() {
  //var player;
  //var song;

  beforeEach(function() {
    //player = new Player();
    //song = new Song();
  });


  describe("getBook", function() {
    it("handles input of 0", function() {
      expect( Curriculum.getBook( 0 ) ).toEqual( 'Intro' );
    });
    it("handles input of 1", function() {
      expect( Curriculum.getBook( 1 ) ).toEqual( 'Book 1' );
    });
    it("handles input of 2", function() {
      expect( Curriculum.getBook( 2 ) ).toEqual( 'Book 2' );
    });
    it("handles input of 3", function() {
      expect( Curriculum.getBook( 3 ) ).toEqual( 'Book 3' );
    });
    it("handles input of 99", function() {
      expect( Curriculum.getBook( 99 ) ).toEqual( 'Non-Interchange' );
    });
    it("throws errors on other input values", function() {
      expect( function() { Curriculum.getBook( 50 ); }).toThrow();
      expect( function() { Curriculum.getBook( 40 ); }).toThrow();
    });
  });


  describe("getUnit", function() {
    it("handles input of 0", function() {
      expect( Curriculum.getUnit( 0 ) ).toEqual( '' );
    });
    it("handles input of 1-16", function() {
      expect( Curriculum.getUnit( 1 ) ).toEqual( 'Unit 1' );
      expect( Curriculum.getUnit( 2 ) ).toEqual( 'Unit 2' );
      expect( Curriculum.getUnit( 3 ) ).toEqual( 'Unit 3' );
      expect( Curriculum.getUnit( 4 ) ).toEqual( 'Unit 4' );
      expect( Curriculum.getUnit( 5 ) ).toEqual( 'Unit 5' );
      expect( Curriculum.getUnit( 6 ) ).toEqual( 'Unit 6' );
      expect( Curriculum.getUnit( 7 ) ).toEqual( 'Unit 7' );
      expect( Curriculum.getUnit( 8 ) ).toEqual( 'Unit 8' );
      expect( Curriculum.getUnit( 9 ) ).toEqual( 'Unit 9' );
      expect( Curriculum.getUnit( 10 ) ).toEqual( 'Unit 10' );
      expect( Curriculum.getUnit( 11 ) ).toEqual( 'Unit 11' );
      expect( Curriculum.getUnit( 12 ) ).toEqual( 'Unit 12' );
      expect( Curriculum.getUnit( 13 ) ).toEqual( 'Unit 13' );
      expect( Curriculum.getUnit( 14 ) ).toEqual( 'Unit 14' );
      expect( Curriculum.getUnit( 15 ) ).toEqual( 'Unit 15' );
      expect( Curriculum.getUnit( 16 ) ).toEqual( 'Unit 16' );
    });
    it("throws errors on other input values", function() {
      expect( function() { Curriculum.getUnit( 17 ); }).toThrow();
      expect( function() { Curriculum.getUnit( 99 ); }).toThrow();
    });
  });

  describe("getBookUnit", function() {
    it("handles input of 0, 0", function() {
      expect( Curriculum.getBookUnit( 0, 0 ) ).toEqual( 'Intro, ' );
    });

    it("handles input of 0, 1", function() {
      expect( Curriculum.getBookUnit( 0, 1 ) ).toEqual( 'Intro, Unit 1' );
    });
    it("handles input of 0, 2", function() {
      expect( Curriculum.getBookUnit( 0, 2 ) ).toEqual( 'Intro, Unit 2' );
    });
    it("handles input of 1, 4", function() {
      expect( Curriculum.getBookUnit( 1, 4 ) ).toEqual( 'Book 1, Unit 4' );
    });
    it("handles input of 2, 8", function() {
      expect( Curriculum.getBookUnit( 2, 8 ) ).toEqual( 'Book 2, Unit 8' );
    });
    it("handles input of 3, 12", function() {
      expect( Curriculum.getBookUnit( 3, 12 ) ).toEqual( 'Book 3, Unit 12' );
    });
  });


  describe("calculateEndingUnit", function() {
    //it("handles starting at Intro, Unit 1, and adding 4 units", function() {
    //  expect( Curriculum.calculateEndingBook( 0, 1, 4 ) ).toEqual( 0 );
    //  expect( Curriculum.calculateEndingUnit( 0, 1, 4 ) ).toEqual( 4 );
    //});
  });

});
