<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterHyundai extends BaseAdapter
{
    public $adapterName = 'Hyundai';
    public $url = 'https://www.hmm21.com/cms/company/engn/index.jsp';

    public function processToTracking()
    {
        $this->debug('containerNumber: ' . $this->containerNumber);

        $this->closePopup();

        $this->debug('change select[name="type"] value to 2');

        $this->page->evaluate(JsFunction::createWithBody("
            select = document.querySelector('select[name=\"type\"]');
            select.value = 2;
        "));

        $this->debug('wait 2 s');
        $this->page->waitFor(2000);

        $this->debug('type input[name="number"]');
        $this->page->type('input[name="number"]', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 3 s');
        $this->page->waitFor(3000);



        $this->debug('click table[summary="ButtonPanelTable"] button');
        $this->page->click('table[summary="ButtonPanelTable"] button');

        $this->debug('wait 20 s');
        $this->page->waitFor(20000);


        $this->makeScreenshot();

    }

    public function getData()
    {
        $this->debug('GET DATA');

        $pages = $this->browser->pages();

        if (count($pages) > 1) {
            $pageResults = $pages[count($pages) - 1];
        }

        // TODO
        $data = $pageResults->evaluate(JsFunction::createWithBody("
        
//            let table = document.querySelector('#detailInfo > table');
//
//            let cells = table.querySelectorAll('tr:last-child > td');
//    
//            return [{
//                type: document.getElementById('st_cntrTpszNm').innerHTML.split('<br>')[1],
//                date: new Date(Date.parse(cells[3].textContent)).toDateString(),
//                event: cells[1].textContent.trim(),
//                place: cells[2].textContent.trim()
//            }];

        "));

        return $this->appendAdapterName($data);
    }

    private function closePopup()
    {
        $this->page->waitFor(7000);

        $pages = $this->browser->pages();

        if (count($pages) > 1) {
            $popup = $pages[count($pages) - 1];

            $popup->close();
        }



    }
}
