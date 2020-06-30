let knex = require('./knex');

const GenerateBicCodes = require('./generate-bic-codes');

let generateBicCodes = new GenerateBicCodes(knex, []);

generateBicCodes.calculateCombinations(['A']);
