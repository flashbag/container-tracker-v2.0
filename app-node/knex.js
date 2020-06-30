let config = require('./config.js');

console.log('knex');

let knex = require('knex')({
    client: 'mysql2',
    connection: {
        host : config.get('db').host.default,
        user : config.get('db').user.default,
        password : config.get('db').password.default,
        database : config.get('db').name.default
    },
    debug: false,
    pool: {
        min: 1,
        max: 50,
    },
    migrations: {
        tableName: 'migrations'
    }
});

// console.log(knex);

module.exports = knex;
