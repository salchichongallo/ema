<?php
declare(strict_types=1);

namespace EMA\Tests\Infrastructure;

use function DI\object;
use Doctrine\DBAL\Connection;
use EMA\App\Account\Model\Collection\AccountCollection;
use EMA\App\Account\Query\AccountFinder;
use EMA\App\Account\Query\FindAccount\FindAccount;
use EMA\Domain\Foundation\VO\Identity;
use EMA\Domain\Note\Model\Collection\NoteCollection;
use EMA\Domain\Note\Model\Note;
use EMA\Domain\Note\Model\VO\NoteText;
use EMA\Infrastructure\Account\Collection\DoctrineAccountCollection;
use EMA\Infrastructure\Account\Finder\DoctrineAccountFinder;
use EMA\Infrastructure\Note\Collection\DoctrineNoteCollection;
use EMA\Tests\BaseTest;
use Slim\App;


/**
 * Test http requests to CRUD oeprations
 */
final class HttpNotesCrudTest extends \EMA\Tests\App\HttpNotesCrudTest
{
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->restartContainer();
        $this->migrate();
        
        
        container()->set(NoteCollection::class, object(DoctrineNoteCollection::class));
        container()->set(AccountFinder::class, object(DoctrineAccountFinder::class));
        container()->set(AccountCollection::class, object(DoctrineAccountCollection::class));
    }
    
    
    function test_user_can_signup_with_google()
    {
        $client_mock = $this->getMockBuilder(\Google_Client::class)
                            ->setMethods([
                                'verifyIdToken',
                                'fetchAccessTokenWithAuthCode',
                                'getAccessToken',
                            ])->getMock();
        
        $client_mock->method('fetchAccessTokenWithAuthCode')->willReturn(true);
        $client_mock->method('verifyIdToken')->willReturn(['sub' => 'some_id']);
        $client_mock->method('getAccessToken')->willReturn(['sub' => 'some_id']);
        
        container()->set(\Google_Client::class, $client_mock);
        
        $app      = container()->get(App::class);
        $path     = $app->getContainer()->get('router')->pathFor('api.google.exchange');
        $request  = $this->getRequest("get", $path, ['code' => 'some_code']);
        $response = $this->sendHttpRequest($request, $app);
        
        $this->assertEquals(200, $response->getStatusCode());
        
        // Make sure this user has created
        query_bus()->dispatch(new FindAccount('google', 'some_id'))->then(
            function ($account) {
                $this->assertNotEquals(count($account), 0);
            },
            function (\Throwable $e) {
                $this->fail($e);
            }
        )->done();
        
        
    }
}