<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterKLine extends BaseAdapter
{
    public $adapterName = 'K-LINE';
    public $url = 'http://ecomm.one-line.com/ecom/CUP_HOM_3301.do?redir=Y&sessLocale=en';

    public function processToTracking()
    {
        $this->debug('containerNumber: ' . $this->containerNumber);

        $this->debug('wait 5 s');
        $this->page->waitFor(5000);

        $this->debug('selecting "C" in select#searchType');
        $this->page->evaluate(JsFunction::createWithBody("
            select = document.getElementById('searchType');
            select.value = 'C';
        "));

        $this->debug('type #searchName');
        $this->page->type('#searchName', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 1 s');
        $this->page->waitFor(1000);


        $this->debug('click #btnSearch');
        $this->page->click('#btnSearch');

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
        
            let record = {};
            let table = document.querySelector('#detailInfo > table');
            
            if (!table) {
                return [record];
            }

            let cells = table.querySelectorAll('tr:last-child > td');
            
            if (!cells.length) {
                return [record];
            }
    
            let elements = [
            
                { 
                    prop: 'type',
                    node: document.getElementById('st_cntrTpszNm')
                },
                
                { 
                    prop: 'date',
                    node: cells[3]
                },
                
                { 
                    prop: 'event',
                    node: cells[1]
                },
                
                { 
                    prop: 'place',
                    node: cells[2]
                }
            ];
            
            elements.forEach(function(el, index) {
            
                if (el.node instanceof Element) {
                  if (el.prop === 'type') {
                    record[el.prop] = el.node.innerHTML.split('<br>')[1];
                  } else if (el.prop === 'date') {
                    record[el.prop] = new Date(Date.parse(el.node.textContent)).toDateString();
                  } else {
                    record[el.prop] = el.node.textContent.trim();
                  }  
                }
                
               
            });
            
            return [record];
           

        "));

        return $this->appendAdapterName($data);
    }
}
