<?php

namespace Tests\Browser;

use App\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Class ClientsTest
 * @package Tests\Browser
 */
class ClientsTest extends DuskTestCase
{
    public function testIndex()
    {
        $admin = User::find(1);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit(route('admin.clients.index'));
            $browser->assertRouteIs('admin.clients.index');
        });
    }
}
