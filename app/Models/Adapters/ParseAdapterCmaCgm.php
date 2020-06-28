<?php
/**
 * Created by PhpStorm.
 * User: flashbag
 * Date: 29.05.20
 * Time: 16:35
 */

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterCmaCgm extends BaseAdapter
{
    public $adapterName = 'CMA CGM';

    public $url = 'https://www.cma-cgm.com';

    public function processToTracking()
    {
        $this->debug('.main-wrapper: scroll into view');
        $this->page->evaluate(JsFunction::createWithBody("
            return document.querySelector('h1').scrollIntoView();
        "));


        $this->debug('wait 2 s');
        $this->page->waitFor(2000);

        $this->debug('click a[href="#track"]');
        $this->page->click('a[href="#track"]');

        $this->debug('type #track-number');
        $this->page->type('#track-number', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 2 s');
        $this->page->waitFor(2000);

        $this->debug('submit WebForm_DoPostBackWithOptions');
        $this->page->evaluate(JsFunction::createWithBody("
            document.querySelectorAll('button[type=\"submit\"]')[2].click();
        "));

        $this->debug('wait 10 s');
        $this->page->waitFor(10000);


        $this->makeScreenshot();

    }

    public function getData()
    {
        $this->debug('GET DATA');
        $data = $this->page->evaluate(JsFunction::createWithBody("
            let table = document.querySelector('table');

            let record = {};

            record.type = document.querySelector('abbr[class=\"o-container-type\"]').textContent;

            table.querySelectorAll('tr').forEach(function (row, indexRow) {

                if (indexRow === 1) {

                    let cells = row.querySelectorAll('td');

                    cells.forEach(function (cell, indexCell) {

                        if (indexCell === 0) {
                            record.date = new Date(Date.parse(cell.textContent)).toDateString();
                        }

                        if (indexCell === 2) {
                            record.event = cell.textContent.trim();
                        }

                        if (indexCell === 3) {
                            record.place = cell.textContent.trim();
                        }
                    });

                }
            });


            return [record];
        "));

        return $this->appendAdapterName($data);
    }
}
