<?php

namespace Tests\Browser;

use App\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Class PartnersTest
 * @package Tests\Browser
 */
class PartnersTest extends DuskTestCase
{
    public function testIndex()
    {
        $admin = User::find(1);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit(route('admin.partners.index'));
            $browser->assertRouteIs('admin.partners.index');
        });
    }
}
