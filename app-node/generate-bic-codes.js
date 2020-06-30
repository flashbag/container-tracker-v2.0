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

  getSymbolsSequence(length) {

      switch (length) {
        case 2:
          for (let i = 0; i < this.symbols.length; i++) {
            this.prefixes.push(this.ownerCodePrefix + this.symbols[i]);
          }
          break;
        case 1:
          for (let i = 0; i < this.symbols.length; i++) {
            for (let j = 0; j < this.symbols.length; j++) {
                this.prefixes.push(
                  this.ownerCodePrefix +
                  this.symbols[i] +
                  this.symbols[j]
                );
            }

          }

          break;
        case 0:

        for (let i = 0; i < this.symbols.length; i++) {
          for (let j = 0; j < this.symbols.length; j++) {
            for (let n = 0; n < this.symbols.length; n++) {
              this.prefixes.push(
                this.symbols[i] +
                this.symbols[j] +
                this.symbols[n]
              );
            }
          }

        }

          break;
        default:
          break;
      }
  }

  calculateCombinations(ownerCodeLetters = []) {

    console.log('calculateCombinations');

    console.log('ownerCodeLetters:', ownerCodeLetters);

    this.ownerCodePrefix = ownerCodeLetters.join('');

    console.log('ownerCodePrefix:', this.ownerCodePrefix);

    if (!ownerCodeLetters.length) {
      // generate combinations of all three letters
      this.prefixes.push({ do: 'huja' });
    } else if (ownerCodeLetters.length >= 3) {
      // generate single combination
      this.prefixes.push(this.ownerCodePrefix);
    } else {
      this.getSymbolsSequence(ownerCodeLetters.length)
    }


    console.log(ownerCodeLetters);

    console.log('prefixes:', this.prefixes);

    // U for all freight containers;
    // J for detachable ment;
    // Z for trailers and chassis.
  }
}

module.exports = GenerateBicCodes;
