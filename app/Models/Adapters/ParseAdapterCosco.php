<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterCosco extends BaseAdapter
{
    public $adapterName = 'Cosco';
    public $url = 'https://elines.coscoshipping.com/ebusiness/';

    public function processToTracking()
    {
        $this->debug('wait 3 s');
        $this->page->waitFor(3000);


        $this->debug('closing modal');
        $this->page->click('.ivu-modal-wrap:not(.ivu-modal-hidden) .ivu-modal-footer .ivu-btn-primary');


        $this->debug('wait 1 s');
        $this->page->waitFor(1000);

        $this->debug('click div.search_header ul.srh_c_t li:nth-child(3)');
        $this->page->click('div.search_header ul.srh_c_t li:nth-child(3)');

        $this->debug('type input.ivu-input');

        $this->page->type('input.ivu-input', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('wait 1 s');
        $this->page->waitFor(1000);

        $this->debug('click a.ser_btn');
        $this->page->click('a.ser_btn');

        $this->debug('wait 3 s');
        $this->page->waitFor(3000);

        $this->makeScreenshot();

    }

    public function getData()
    {
        $this->debug('GET DATA');

        $data = $this->page->evaluate(JsFunction::createWithBody("
            
            let record = {};
            
            let row = document.querySelector('.cntrMovintItem');
               
            if (!row) {
                return [record];    
            }
               
              
            let elements = [
            
                { 
                    prop: 'type',
                    node: document.querySelector('.cntrInfos span > span') 
                },
                
                { 
                    prop: 'date',
                    node: row.querySelector('.issueTime > p')
                },
                
                { 
                    prop: 'event',
                    node: row.querySelector('.singleMoving > div:nth-child(2) p.value')
                },
                
                { 
                    prop: 'place',
                    node: row.querySelector('.singleMoving > div:nth-child(3) p.value')
                }
            ];
            
            elements.forEach(function(el, index) {
                if (el.node instanceof Element) {
                    record[el.prop] = el.node.textContent.trim();
                }
            });
            
           
            return [record];
            
        "));

        return $this->appendAdapterName($data);
    }
}
