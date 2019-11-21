<?php

namespace Tests\Browser;

use App\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

/**
 * Class SpecialtiesTest
 * @package Tests\Browser
 */
class SpecialtiesTest extends DuskTestCase
{
    public function testIndex()
    {
        $admin = User::find(1);
        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin);
            $browser->visit(route('admin.specialties.index'));
            $browser->assertRouteIs('admin.specialties.index');
        });
    }
}
