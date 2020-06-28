<?php
/**
 * Created by PhpStorm.
 * User: flashbag
 * Date: 29.05.20
 * Time: 16:35
 */

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterMsc extends BaseAdapter
{
    public $adapterName = 'MSC';

    public $url = 'https://www.msc.com/track-a-shipment?link=68987f15-a8b0-44d2-99c8-d62bdf59c921';

    public function processToTracking()
    {
        $this->debug('wait 2 s');
        $this->page->waitFor(2000);

        $this->debug('click .countryDetectSelection');
        $this->page->click('.countryDetectSelection');

        $this->debug('wait 10 s');
        $this->page->waitFor(10000);

        $this->debug('click .button-secondary');
        $this->page->click('.button-secondary');

        $this->debug('wait 2 s');
        $this->page->waitFor(2000);

        $this->debug('h1: scroll into view');
        $this->page->evaluate(JsFunction::createWithBody("
            return document.querySelector('h1').scrollIntoView();
        "));

        $this->debug('type #ctl00_ctl00_plcMain_plcMain_TrackSearch_txtBolSearch_TextField');
        $this->page->type('#ctl00_ctl00_plcMain_plcMain_TrackSearch_txtBolSearch_TextField', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 2 s');
        $this->page->waitFor(2000);


        $this->debug('submit WebForm_DoPostBackWithOptions');
        $this->page->evaluate(JsFunction::createWithBody('
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
        '));

        $this->debug('wait 10 s');
        $this->page->waitFor(10000);


        $this->makeScreenshot();

    }

    public function getData()
    {
        $this->debug('GET DATA');
        $data = $this->page->evaluate(JsFunction::createWithBody("
            let tableStats = document.querySelector('table.containerStats');
            let tableResults = document.querySelector('table.resultTable');

            let record = {};

            tableStats.querySelectorAll('tr').forEach(function (row, indexRow) {

                if (indexRow === 1) {

                    let cells = row.querySelectorAll('td');

                    cells.forEach(function (cell, indexCell) {

                        if (indexCell === 0) {
                            record.type = cell.textContent.trim();
                        }
                    });
                }

            });

            tableResults.querySelectorAll('tr').forEach(function (row, indexRow) {

                if (indexRow === 1) {

                    let cells = row.querySelectorAll('td');

                    cells.forEach(function (cell, indexCell) {

                        if (indexCell === 0) {
                            record.place = cell.textContent.trim();
                        }

                        if (indexCell === 1) {
                            record.event = cell.textContent.trim();
                        }

                        if (indexCell === 2) {
                            record.date = new Date(Date.parse(cell.textContent)).toDateString();
                        }
                    });

                }
            });



            return [record];
        "));

        return $this->appendAdapterName($data);
    }
}
