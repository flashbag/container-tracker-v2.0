module.exports = {
    adapterName: 'Oocl',
    url: 'https://www.oocl.com/eng/ourservices/eservices/cargotracking/Pages/cargotracking.aspx',

    processToTracking: async function (browser, page, containerNumber) {

        let self = this;

        console.log('----------------------------');
        console.log('--- PROCESS TO TRACKING ----');
        console.log('----------------------------');

        await page.goto(self.url, {waitUntil: 'networkidle2'});

        await page.waitForSelector('#cargoTrackingDropBtn',[
//            'visible' => true
        ]);

        await page.waitFor(10000);

        await page.click('button.btn.dropdown-toggle');

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


    getData: async function (browser, page, containerNumber) {

        let self = this;

        console.log('----------------------------');
        console.log('-------   GET DATA  --------');
        console.log('----------------------------');

    }
};
