const BaseAdapter = require('./base-adapter');

if (process.argv.length < 3) {
    console.error('Specify container number as third parameter');
    return false;
}

// const Adapter = require('./adapters/adapter-oocl');
// const Adapter = require('./adapters/adapter-msc');
const Adapter = require('./adapters/adapter-cma-cgm');

const containerNumber = process.argv[2];

let adapter = new BaseAdapter(containerNumber);

adapter
    .createBrowser()
    .then(function () {
        return adapter.createPage();
    })
    .then(async function () {
        return Adapter.goToUrl(adapter.page);
    })
    .then(async function () {
        return Adapter.processToTracking(adapter.page, containerNumber);
    })
    .then(async function () {
        return Adapter.getData(adapter.browser, adapter.page, containerNumber);
    })
    .then(async function () {

        console.log('closing browser');
        await adapter.browser.close();

    })
    .catch(function (e) {
        throw e;
    });
