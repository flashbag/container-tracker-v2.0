<?php
/**
 * Created by PhpStorm.
 * User: flashbag
 * Date: 29.05.20
 * Time: 16:35
 */

namespace App\Models\Adapters;

use Nesk\Rialto\Data\JsFunction;

class ParseAdapterOocl extends BaseAdapter
{
    public $adapterName = 'OOCL';

    public $url = 'https://www.oocl.com/eng/ourservices/eservices/cargotracking/Pages/cargotracking.aspx';

    public function processToTracking()
    {
//        var_dump(time() . ' before waitForNavigation');
//        $this->page->waitForNavigation();
//        var_dump(time() . ' after waitForNavigation');

        $this->page->waitForSelector('#cargoTrackingDropBtn',[
//            'visible' => true
        ]);

        $this->page->waitFor(10000);

        var_dump(time() . ' waitForSelector \'button.btn.dropdown-toggle\'');

        var_dump(time() . ' click \'button.btn.dropdown-toggle\'');

        $this->page->click('button.btn.dropdown-toggle');

        var_dump(time() . ' waitFor');

        $this->page->waitFor(2000);

        var_dump(time() . ' click #cargoTrackingDropBtn .dropdown-menu.inner li[data-original-index="2"] a');

        $this->page->click('#cargoTrackingDropBtn .dropdown-menu.inner li[data-original-index="2"] a');

        $this->page->waitFor(2000);

        $this->page->type('#SEARCH_NUMBER', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->page->click('#container_btn');

        $this->page->waitFor(5000);

        $this->makeScreenshot();

    }

    public function getData()
    {
        $data = [];

        return $this->appendAdapterName($data);
    }
}
