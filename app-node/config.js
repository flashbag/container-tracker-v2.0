const convict = require('convict')
// const convict_format_with_validator = require('convict-format-with-validator')
// const convict_format_with_moment = require('convict-format-with-moment')
const json5 = require('json5')

// Use this only if you use the "email", "ipaddress" or "url" format
// convict.addFormats(convict_format_with_validator)

// Use this only if you use the "duration" or "timestamp" format
// convict.addFormats(convict_format_with_moment)

// Use this only if you have a .json configuration file in JSON5 format
// (i.e. with comments, etc.).
convict.addParser({extension: 'json', parse: json5.parse})

// Define a schema
const config = convict({
    env: {
        doc: "The application environment.",
        format: ["production", "development", "stage"],
        default: "development",
        env: "NODE_ENV"
    }
});

// Load environment dependent configuration
const env = config.get('env');
const path = require('path');

config.loadFile( path.resolve(__dirname + '/config/config-' + env + '.json5'));

// Perform validation
// config.validate({});

module.exports = config;
