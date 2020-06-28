module.exports = {
    adapterName: 'Msc',
    url: 'https://www.msc.com/track-a-shipment?link=68987f15-a8b0-44d2-99c8-d62bdf59c921',

    goToUrl: async function (page) {
        return await page.goto(this.url, {waitUntil: 'networkidle2'});
    },

    processToTracking: async function (page, containerNumber) {

        console.log('----------------------------');
        console.log('--- PROCESS TO TRACKING ----');
        console.log('----------------------------');

        await page.waitFor(2000);

        await page.click('.countryDetectSelection');

        await page.waitFor(10000);

        await page.click('.button-secondary');

        await page.waitFor(2000);

        await page.evaluate(function(){
            document.querySelector('h1').scrollIntoView();
        });

        await page.type(
            '#ctl00_ctl00_plcMain_plcMain_TrackSearch_txtBolSearch_TextField',
            containerNumber, {
                'delay': 50
            }
        );

        await page.waitFor(1500);

        await page.evaluate(function(){
            WebForm_DoPostBackWithOptions(
                new WebForm_PostBackOptions(
                    "ctl00$ctl00$plcMain$plcMain$TrackSearch$hlkSearch",
                    "",
                    true,
                    "BolSearchPage",
                    "",
                    false,
                    true
                )
            )
        });


        await page.waitFor(10000);

    },


    getData: async function (browser, page) {

        let self = this;

        console.log('----------------------------');
        console.log('-------   GET DATA  --------');
        console.log('----------------------------');

        // const pages = await browser.pages();
        //
        // // console.log(pages);
        //
        // const popup = pages[pages.length - 1];
        //
        // await popup.waitFor(7000);
        //
        const records = await page.evaluate(function(){
            let tableStats = document.querySelector('table.containerStats');
            let tableResults = document.querySelector('table.resultTable');

            let record = {};

            tableStats.querySelectorAll('tr').forEach(function (row, indexRow) {

                if (indexRow === 1) {

                    let cells = row.querySelectorAll('td');

                    cells.forEach(function (cell, indexCell) {

                        if (indexCell === 0) {
                            record.type = cell.textContent;
                        }
                    });
                }

            });

            tableResults.querySelectorAll('tr').forEach(function (row, indexRow) {

                if (indexRow === 1) {

                    let cells = row.querySelectorAll('td');

                    cells.forEach(function (cell, indexCell) {

                        if (indexCell === 0) {
                            record.place = cell.textContent;
                        }

                        if (indexCell === 1) {
                            record.event = cell.textContent;
                        }

                        if (indexCell === 2) {
                            record.date = new Date(Date.parse(cell.textContent)).toDateString();
                        }
                    });

                }
            });



            return [record];

        });

        console.log(records);

        browser.close();

    }
};
