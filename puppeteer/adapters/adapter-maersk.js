module.exports = {
    adapterName: 'Maersk',
    url: 'https://www.maersk.com/',

    goToUrl: async function (page) {
        return await page.goto(this.url, {waitUntil: 'networkidle2'});
    },

    processToTracking: async function (page, containerNumber) {

        // console.log('----------------------------');
        // console.log('--- PROCESS TO TRACKING ----');
        // console.log('----------------------------');
        //
        // await page.evaluate(function(){
        //     document.querySelector('.main-wrapper').scrollIntoView();
        // });
        //
        //
        // await page.waitFor(2000);
        //
        // await page.click('a[href="#track"]');
        //
        // await page.type(
        //     '#track-number',
        //     containerNumber, {
        //         'delay': 50
        //     }
        // );
        //
        // await page.waitFor(2000);
        //
        // await page.evaluate(function(){
        //     document.querySelectorAll('button[type="submit"]')[2].click();
        // });
        //
        // await page.waitFor(10000);

    },


    getData: async function (browser, page) {

        // console.log('----------------------------');
        // console.log('-------   GET DATA  --------');
        // console.log('----------------------------');
        //
        //
        // const records = await page.evaluate(function(){
        //     let table = document.querySelector('table');
        //
        //     let record = {};
        //
        //     record.type = document.querySelector('abbr[class="o-container-type"]').textContent;
        //
        //     table.querySelectorAll('tr').forEach(function (row, indexRow) {
        //
        //         if (indexRow === 1) {
        //
        //             let cells = row.querySelectorAll('td');
        //
        //             cells.forEach(function (cell, indexCell) {
        //
        //                 if (indexCell === 0) {
        //                     record.date = new Date(Date.parse(cell.textContent)).toDateString();
        //                 }
        //
        //                 if (indexCell === 2) {
        //                     record.event = cell.textContent.trim();
        //                 }
        //
        //                 if (indexCell === 3) {
        //                     record.place = cell.textContent.trim();
        //                 }
        //             });
        //
        //         }
        //     });
        //
        //
        //
        //     return [record];
        //
        // });
        //
        // console.log(records);
        //
        // browser.close();

    }
};
