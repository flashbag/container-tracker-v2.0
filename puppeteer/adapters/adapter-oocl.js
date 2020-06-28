module.exports = {
    adapterName: 'Oocl',
    url: 'https://www.oocl.com/eng/ourservices/eservices/cargotracking/Pages/cargotracking.aspx',

    goToUrl: async function (page) {
        return await page.goto(this.url, {waitUntil: 'networkidle2'});
    },

    processToTracking: async function (page, containerNumber) {

        console.log('----------------------------');
        console.log('--- PROCESS TO TRACKING ----');
        console.log('----------------------------');

        await page.waitFor(25000);

        await page.evaluate(function(){
            acceptCookiePolicy();
        });

        await page.click('#cargoTrackingDropBtn .dropdown-toggle');

        await page.waitFor(2000);

        await page.click('#cargoTrackingDropBtn .dropdown-menu.inner li[data-original-index="2"] a');

        await page.waitFor(2000);

        await page.type('#SEARCH_NUMBER', containerNumber, {
            'delay': 50
        });

        await page.click('#container_btn');

        await page.waitFor(10000);

        page.screenshot('oocl.png');
    },


    getData: async function (browser, page) {

        let self = this;

        console.log('----------------------------');
        console.log('-------   GET DATA  --------');
        console.log('----------------------------');

        const pages = await browser.pages();

        // console.log(pages);

        const popup = pages[pages.length - 1];

        await popup.waitFor(7000);

        const records = await popup.evaluate(function(){
            let table = document.querySelectorAll('table.groupTable')[0];
            let row = table.querySelectorAll('tr[class]')[0];

            let event = row.querySelectorAll('td')[5].textContent.replace(/(\\t|\\n)/g,'');
            let place = row.querySelectorAll('td')[6].textContent;
            let datetime = new Date(Date.parse(row.querySelectorAll('td')[7].textContent)).toDateString();

            return [{
                place: place,
                event: event.replace(/(\\t|\\n)/g,''),
                datetime: datetime,
            }];

        });

        console.log(records);

        browser.close();

    }
};
