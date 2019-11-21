<?php

namespace Tests\Browser;

use App\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Class PharmacyTest
 * @package Tests\Browser
 */
class PharmacyTest extends DuskTestCase
{
    public function testIndex()
    {
        $admin = User::find(1);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit(route('admin.pharmacy.index'));
            $browser->assertRouteIs('admin.pharmacy.index');
        });
    }
}
