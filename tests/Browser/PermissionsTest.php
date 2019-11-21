<?php

namespace Tests\Browser;

use App\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Class PermissionsTest
 * @package Tests\Browser
 */
class PermissionsTest extends DuskTestCase
{
    public function testIndex()
    {
        $admin = User::find(1);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit(route('admin.permissions.index'));
            $browser->assertRouteIs('admin.permissions.index');
        });
    }
}
