<?php
declare(strict_types=1);

namespace EMA\Tests\App\Account\Command\AddAccount;

use EMA\App\Account\Command\AddAccount\AddAccount;
use EMA\App\Account\Command\AddAccount\AddAccountHandler;
use EMA\App\Account\Query\FindAccount\FindAccount;
use EMA\Domain\Foundation\VO\Identity;
use EMA\Tests\BaseTest;

class AddAccountHandlerTest extends BaseTest
{
    
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }
    
    
    function test_it_adds_new_account()
    {
        $this->setAuthenticatedUser(new Identity());
        
        $social_provider = 'google';
        $google_key      = "google_id_123";
        
        $command = new AddAccount($social_provider, $google_key, new Identity());
        $handler = container()->get(AddAccountHandler::class);
        $handler->__invoke($command);
        
        // Now assert there is such account
        $account = query_bus_sync_dispatch(new FindAccount($social_provider, $google_key));
        $this->assertEquals($account['social_provider_id'], $google_key);
        $this->assertEquals($account['social_provider_name'], $social_provider);
    }
    
}
