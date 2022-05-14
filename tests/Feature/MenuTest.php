<?php

namespace Dealskoo\Deal\Tests\Feature;

use Dealskoo\Admin\Facades\AdminMenu;
use Dealskoo\Seller\Facades\SellerMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Dealskoo\Deal\Tests\Testcase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu()
    {
        $this->assertNotNull(AdminMenu::findBy('title', 'deal::deal.deals'));
        $this->assertNotNull(SellerMenu::findBy('title', 'deal::deal.deals'));
    }
}
