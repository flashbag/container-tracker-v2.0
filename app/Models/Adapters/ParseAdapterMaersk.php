<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterMaersk extends BaseAdapter
{
    public $adapterName = 'Maersk';
    public $url = 'https://www.maersk.com';

    public function processToTracking()
    {
        $this->debug('containerNumber: ' . $this->containerNumber);

        $this->debug('wait 5 s');
        $this->page->waitFor(5000);

        $this->debug('CookieInformation.submitAllCategories()');
        $this->page->evaluate(JsFunction::createWithBody("
            return CookieInformation.submitAllCategories();
        "));

        $this->debug('click button[data-id="header-track"]');
        $this->page->click('button[data-id="header-track"]');

        $this->debug('wait 1 s');
        $this->page->waitFor(1000);

        $this->debug('type #ign-trackingNumber');
        $this->page->type('#ign-trackingNumber', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 1 s');
        $this->page->waitFor(1000);


        $this->debug('click button.ign-button--primary');
        $this->page->click('button.ign-button--primary');

        $this->debug('wait 5 s');
        $this->page->waitFor(5000);

        $this->debug('.pt-results: scroll into view');
        $this->page->evaluate(JsFunction::createWithBody("
            return document.querySelector('.pt-results').scrollIntoView();
        "));

        $this->makeScreenshot();

    }

    public function  getData()
    {

        $this->debug('GET DATA');
        $data = $this->page->evaluate(JsFunction::createWithBody("
        
            let record = {};           
            let table = document.querySelector('table.expandable-table__wrapper');

            if (!table) {
                return [record];
            }


            let typeSpans =  table.querySelectorAll('td[data-th=\"Container type size\"] > span');
            let dateSpans =  table.querySelectorAll('td[data-th=\"Arrival date and time\"] > span');
            let placeSpans = table.querySelectorAll('td[data-th=\"Last location\"] > span');

            if (typeSpans.length) {
                record.type = typeSpans[typeSpans.length - 1].textContent;    
            } 

            if (dateSpans.length) {
                let dateParts = dateSpans[dateSpans.length - 1].innerHTML.split('<br>');

                if (dateParts.length) {
                    record.date = new Date(Date.parse(dateParts[0])).toDateString();
                }    
            }

            if (placeSpans.length) {

                let placeParts = placeSpans[placeSpans.length - 1].innerHTML.split('<br>');    

                if (placeParts.length) {
                    let placeParts2 = placeParts[0].split('•');        

                    if (placeParts2.length === 2) {
                        record.place = placeParts2[1].trim();
                        record.event = placeParts2[0].trim();
                    }
                }
            }

            return [record];
        "));

        return $this->appendAdapterName($data);
    }
}
