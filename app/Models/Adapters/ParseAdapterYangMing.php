<?php

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterYangMing extends BaseAdapter
{
    public $adapterName = 'Yang Ming';

    public $url = 'https://www.yangming.com/e-service/track_trace/track_trace_cargo_tracking.aspx';

    public function processToTracking()
    {
        $this->page->type('#ContentPlaceHolder1_num1', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->page->click('#ContentPlaceHolder1_btnTrack');

        $this->page->waitFor(3000);

        $this->makeScreenshot();
    }

    public function getData()
    {
        $data = $this->page->evaluate(JsFunction::createWithBody("
            
            let rows = document.querySelectorAll('#ContentPlaceHolder1_gvCargoTracking tbody tr');
            
            let data = [];
            
            rows.forEach(function(row){
            
                let record = {};
                
                cells = row.querySelectorAll('td');
                    
                cells.forEach(function(cell, key){
                    switch(key) {
                        case 1:
                            record.size = cell.textContent.trim();
                        break;
                        
                        case 2:
                            record.type = cell.textContent.trim();
                        break;
                        
                        case 3:
                            record.datetime = cell.textContent.trim();
                        break;
                        
                        case 4:
                            record.event = cell.textContent.trim();
                        break;
                        
                        case 5:
                            record.place = cell.textContent.trim();
                        break;
                    }    
                }); 
                
                data.push(record);
            });
            
            return data;
            
        "));

        return $this->appendAdapterName($data);
    }
}
