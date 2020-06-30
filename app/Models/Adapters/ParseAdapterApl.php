<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterApl extends BaseAdapter
{
    public $adapterName = 'APL';
    public $url = 'https://www.apl.com/ebusiness/tracking';

    public function processToTracking()
    {
        $this->debug('containerNumber: ' . $this->containerNumber);

        $this->debug('type #Reference');
        $this->page->type('#Reference', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 1 s');
        $this->page->waitFor(1000);


        $this->debug('click #btnTracking');
        $this->page->click('#btnTracking');

        $this->debug('wait 7 s');
        $this->page->waitFor(10000);

        $this->debug('.c-endtoend--table table: scroll into view');

        $this->page->evaluate(JsFunction::createWithBody("
            return document.querySelector('.c-endtoend--table table').scrollIntoView();
        "));

        $this->makeScreenshot();

    }

    public function  getData()
    {

        $this->debug('GET DATA');
        $data = $this->page->evaluate(JsFunction::createWithBody("
        
            let record = {};
            let table = document.querySelector('.c-endtoend--table table');
            
            if (!table) {
                return [record];
            }

            let cells = table.querySelectorAll('tr[class=\"is-current is-open\"] td');
            
            if (!cells.length) {
                return [record];
            }
    
            return [{
                type: document.querySelector('.o-container-type').textContent.trim(),
                date: new Date(Date.parse(cells[0].textContent)).toDateString(),
                event: cells[2].textContent.trim(),
                place: cells[3].textContent.trim()
            }];

        "));

        return $this->appendAdapterName($data);
    }
}
