<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterZim extends BaseAdapter
{
    public $adapterName = 'Zim';
    public $url = 'https://www.zim.com/tools/track-a-shipment';

    public function processToTracking()
    {
        $this->debug('containerNumber: ' . $this->containerNumber);

        $this->debug('wait 5 s');
        $this->page->waitFor(5000);

        $this->debug('button.accept-cookies-button');
        $this->page->click('button.accept-cookies-button');

        $this->debug('wait 2 s');
        $this->page->waitFor(2000);

        $this->debug('type #ConsNumber');
        $this->page->type('#ConsNumber', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 1 s');
        $this->page->waitFor(1000);


        $this->debug('click input.track-shipment-button');
        $this->page->click('input.track-shipment-button');

        $this->debug('wait 5 s');
        $this->page->waitFor(5000);

        $this->debug('h2.container-details: scroll into view');

        $this->page->evaluate(JsFunction::createWithBody("
            return document.querySelector('h2.container-details').scrollIntoView();
        "));

        $this->makeScreenshot();

    }

    public function  getData()
    {

        $this->debug('GET DATA');
        $data = $this->page->evaluate(JsFunction::createWithBody("
        
            let table = document.querySelector('table.track-shipment');

            let cells = table.querySelectorAll('tr:last-child > td');
    
            return [{
                type: document.querySelector('dl.dl-inline:not(.lg) dd').textContent.trim(),
                date: new Date(Date.parse(cells[3].textContent)).toDateString(),
                event: cells[1].textContent.trim(),
                place: cells[2].textContent.trim()
            }];

        "));

        return $this->appendAdapterName($data);
    }
}
