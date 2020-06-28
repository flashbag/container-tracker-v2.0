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

        $this->debug('wait for cargo tracking drop btn');
        $this->page->waitForSelector('#cargoTrackingDropBtn',[
//            'visible' => true
        ]);

        $this->debug('wait 15 s');
        $this->page->waitFor(15000);

        $this->debug('scroll into view');
        $this->page->evaluate(JsFunction::createWithBody("
            return document.querySelector('.container').scrollIntoView();
        "));

        $this->debug('wait 15 s');
        $this->page->waitFor(15000);

        $this->debug('accept cookies');
        $this->page->evaluate(JsFunction::createWithBody("
            acceptCookiePolicy();
        "));

        $this->debug('click #cargoTrackingDropBtn');
        $this->page->click('#cargoTrackingDropBtn');

        $this->debug('wait 5 s');
        $this->page->waitFor(5000);

        $this->debug('click dropdown container #');
        $this->page->click('#cargoTrackingDropBtn .dropdown-menu.inner li[data-original-index="2"] a');

        $this->debug('wait 2 s');
        $this->page->waitFor(2000);

        $this->debug('type #SEARCH_NUMBER');
        $this->page->type('#SEARCH_NUMBER', $this->containerNumber, [
            'delay' => 50
        ]);

        $this->debug('click #container_btn');
        $this->page->click('#container_btn');

        $this->debug('wait 10 s');

        $this->page->waitFor(10000);

        $this->makeScreenshot();

    }

    public function getData()
    {
        $pages = $this->browser->pages();

        $popup = $pages[count($pages) - 1];

        $popup->waitFor(7000);

        $data = $popup->evaluate(JsFunction::createWithBody("
            let table = document.querySelectorAll('table.groupTable')[0];
            let row = table.querySelectorAll('tr[class]')[0];

            let event = row.querySelectorAll('td')[5].textContent.replace(/(\\t|\\n)/g,'');
            let place = row.querySelectorAll('td')[6].textContent;
            let datetime = new Date(Date.parse(row.querySelectorAll('td')[7].textContent)).toDateString();

            return [{
                place: place,
                event: event.replace(/(\\t|\\n)/g,''),
                datetime: datetime,
            }];
        "));

        return $this->appendAdapterName($data);
    }
}
