const puppeteer = require('puppeteer');

function BaseAdapter(containerNumber) {

    this.url = '';
    this.adapterName = '';
    this.containerNumber = containerNumber;

}


BaseAdapter.prototype.createBrowser = async function() {

    this.browser = await puppeteer.launch({
        headless: false,
        defaultViewport: {
            width: 1200,
            height: 555
        }
    });

    return this.browser;
};

BaseAdapter.prototype.createPage = async function () {
    this.page = await this.browser.newPage();
    return this.page;
};


module.exports = BaseAdapter;
