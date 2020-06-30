const fs = require('fs');
const path = require('path');

// const Serialize = require('php-serialize');
const lodash = require('lodash');
const moment = require('moment');
const WebSocket = require('ws');

const { createLogger, format, transports } = require('winston');
const { combine, timestamp, label, printf } = format;

const myFormat = printf(({ level, message, label, timestamp }) => {
    return `${timestamp} [${label}] ${level}: ${message}`;
});

const logsDir = path.resolve(__dirname + '/logs/');

const helpers = {

    makeLogger: function(name) {

        return createLogger({
            format: combine(
                label({ label: name }),
                timestamp(),
                myFormat
            ),
            transports: [
                new transports.Console({
                    level: 'debug'
                }),
                new transports.File({
                    filename: logsDir  + '/' + name + '.log',
                    level: 'info'
                })
            ]
        });
    },

    getTickersArrayFromPairs: function(pairs) {
        return lodash.map(pairs, 'ticker');
    },

    getCurrIdsArrayFromPairs: function(pairs) {
        return lodash.map(pairs, 'cur_id');
    },

    findPairOriginal: function(pairsArray, pair) {
        return lodash.filter(pairsArray, function (p) {
            return p.ticker === pair;
        })[0];
    },

    _getExchangeRow: function(knex, exchangeName) {
        return knex('arb_dump_stocks').where('dstock_name', exchangeName);t
    },

    _getExchangePairs: function(knex, dstockId) {
        let todayMidnightTs = moment().set({'hour': 0, 'minute': 0, 'second': 0}).unix();

        console.log({
            'dstock_id': dstockId,
            'check_mainconditions': 1
        });

        return knex('arb_dump_condcheck').where({
            'dstock_id': dstockId,
            'check_mainconditions': 1
        })
        .select('cur_id', 'cur_pair', 'check_mintrade');
    },

    compileFileFromPair: function(pairTextRow) {
        return path.resolve(__dirname + '/OHLCV/') + '/' + pairTextRow.cur_id + '_30mintrades.txt';
    },

    serializeFormattedOrder: function(order) {
        // return phpSerialize.serialize(order);
        // return Serialize.serialize(order);
        return order;
    },

    fetchExchangePairs: function(knex, exchangeName) {
        return helpers._getExchangeRow(knex, exchangeName)
            .then(function (rowsExchanges) {
                console.log(rowsExchanges);
                return helpers._getExchangePairs(knex, rowsExchanges[0].dstock_id);
            });
    },

    chunkExchangePairs: function(pairs, concurrentCount) {
        return lodash.chunk(pairs, Math.ceil(pairs.length / concurrentCount));
    },

    waitSocketOpen: function (webSocket, callback) {

        if (webSocket.readyState === WebSocket.OPEN) {
            return callback();
        } else {
            let interval = setInterval(function () {
                if (webSocket.readyState === WebSocket.OPEN) {
                    clearInterval(interval);
                    callback();
                }
            }, 100);
        }
    },

};

module.exports = helpers;
