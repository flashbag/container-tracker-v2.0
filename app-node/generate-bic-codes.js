const knex = require('./knex');
const helpers = require('./helpers');

class GenerateBicCodes {

  constructor(knex) {
    this.knex = knex;
    this.logger = helpers.makeLogger('generate-bic-codes');

    this.symbols = [
      'A','B','C','D','E','F','G',
      'H','I','J','K','L','M','N',
      'O','P','Q','R','S','T','U',
      'V','W','X','Y','Z'
    ];
    this.prefixes = [];
  }

  generateString(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   var charactersLength = characters.length;
   for ( let i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
  }

  calculateCombinations(ownerCodeLetters = []) {

    console.log('calculateCombinations');

    this.ownerCodePrefix = ownerCodeLetters.join();

    console.log('ownerCodePrefix:', this.ownerCodePrefix);

    if (!ownerCodeLetters.length) {
      // generate combinations of all three letters
      this.prefixes.push({ do: 'huja' });
    } else if (ownerCodeLetters.length >= 3) {
      // generate single combination
      this.prefixes.push(this.ownerCodePrefix);
    } else {

      let iterations = 3 - ownerCodeLetters.length;

      console.log('iterations:', iterations);

      switch (iterations) {
        case 2:
            for (let i = 0; i < 26; i++) {
              this.prefixes.push(this.ownerCodePrefix + this.generateString(1));
            }
        // ownerCodeLetters.length == 2
        // generate 26
          break;
        default:

      }


      // ownerCodeLetters.length == 0
      // generate 26 * 26 * 26

      // ownerCodeLetters.length == 1
      // generate 26 * 26


    }


    console.log(ownerCodeLetters);



    console.log('prefixes:', this.prefixes);

    // U for all freight containers;
    // J for detachable ment;
    // Z for trailers and chassis.
  }
}

module.exports = GenerateBicCodes;
