<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterCosco extends BaseAdapter
{
    public $adapterName = 'Cosco';
    public $url = 'https://elines.coscoshipping.com/ebusiness/';

    public function processToTracking()
    {
        $this->page->waitFor(2000);

        $needToCloseModal = $this->page->evaluate(JsFunction::createWithBody("
            
            const allModalsHidden = true;
            
            let modals = document.querySelectorAll('.ivu-modal-wrap');
             
            let countModalsTotal = modals.length;
            let countModalsHidden = 0;
            
            modals.forEach(function(item, index) {    
                if (item.className.indexOf('hidden') === -1) {
                    countModalsHidden = countModalsHidden + 1;
                }
            }); 
            
            let magicBool = countModalsTotal > countModalsHidden;
            
            return magicBool;
            
        "));

        if ($needToCloseModal) {
            $this->page->waitFor(1000);
            $this->page->click('.ivu-modal-wrap:not(.ivu-modal-hidden) .ivu-modal-footer .ivu-btn-primary');
        }

        $this->page->waitFor(500);

        $this->page->click('div.search_header ul.srh_c_t li:nth-child(3)');

        $this->page->type('input.ivu-input', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->page->waitFor(200);

        $this->page->click('a.ser_btn');

        $this->page->waitFor(3000);

        $this->page->click('.toggleCNTRMovingHistory');

        $this->makeScreenshot();

    }

    public function getData()
    {
        $data = $this->page->evaluate(JsFunction::createWithBody("
            
            let rows = document.querySelectorAll('.cntrMovintItem');
            
            let data = [];
            
            rows.forEach(function(item){
            
                let record = {};
                record.datetime = item.querySelector('.issueTime').textContent;
                record.event = item.querySelector('.singleMoving > div:nth-child(2) .value').textContent;
                record.place = item.querySelector('.singleMoving > div:nth-child(3) .value').textContent;
                
                data.push(record);
            });
            
            console.log(data);
            
            return data;
            
        "));

        if (!empty($data)) {
            foreach ($data as $key => $row) {
                $data[$key]['source'] = 'COSCO';
            }
        }

        return $data;
    }
}
