<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterHapagLloyd extends BaseAdapter
{
    public $adapterName = 'Hapag Lloyd';
    public $url = 'https://www.hapag-lloyd.com/en/online-business/tracing/tracing-by-container.html';

    public function processToTracking()
    {
        $this->debug('containerNumber: ' . $this->containerNumber);

        $this->debug('wait 7 s');
        $this->page->waitFor(7000);

        $this->debug('click #accept-recommended-btn-handler');
        $this->page->click('#accept-recommended-btn-handler');

        $this->debug('type table[summary="LabelledComponentTable"] input');
        $this->page->type('table[summary="LabelledComponentTable"] input', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 1 s');
        $this->page->waitFor(1000);


        $this->debug('click table[summary="ButtonPanelTable"] button');
        $this->page->click('table[summary="ButtonPanelTable"] button');

        $this->debug('wait 5 s');
        $this->page->waitFor(5000);

        $this->debug('#statusInfo: scroll into view');

        $this->page->evaluate(JsFunction::createWithBody("
            return document.querySelector('#statusInfo').scrollIntoView();
        "));

        $this->makeScreenshot();

    }

    public function getData()
    {

        $this->debug('GET DATA');
        $data = $this->page->evaluate(JsFunction::createWithBody("
        
            let table = document.querySelector('#detailInfo > table');

            let cells = table.querySelectorAll('tr:last-child > td');
    
            return [{
                type: document.getElementById('st_cntrTpszNm').innerHTML.split('<br>')[1],
                date: new Date(Date.parse(cells[3].textContent)).toDateString(),
                event: cells[1].textContent.trim(),
                place: cells[2].textContent.trim()
            }];

        "));

        return $this->appendAdapterName($data);
    }
}
