<?php
declare(strict_types=1);

namespace EMA\Tests\App\Note\Query\RecentNotes;

use Carbon\Carbon;
use Doctrine\Common\Collections\Collection;
use EMA\App\Note\Query\RecentNotes\RecentNotes;
use EMA\Domain\Foundation\VO\Identity;
use EMA\Domain\Note\Model\Collection\NoteCollection;
use EMA\Domain\Note\Model\Note;
use EMA\Domain\Note\Model\VO\NoteText;
use EMA\Tests\BaseTest;

class RecentNotesHandlerTest extends BaseTest
{
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->restartContainer();
    }
    
    
    function test_it_works()
    {
        
        $owner_id = new Identity();
        $count    = 2;
        
        // seed notes
        Carbon::setTestNow(Carbon::parse("01.01.2017 00:00:00"));
        $note1 = new Note(new Identity(), new NoteText("1"), $owner_id);
        Carbon::setTestNow(Carbon::parse("01.01.2017 00:00:01"));
        $note2 = new Note(new Identity(), new NoteText("2"), $owner_id);
        Carbon::setTestNow(Carbon::parse("01.01.2017 00:00:02"));
        $note3 = new Note(new Identity(), new NoteText("3"), $owner_id);
        
        container()->get(NoteCollection::class)->save($note1);
        container()->get(NoteCollection::class)->save($note2);
        container()->get(NoteCollection::class)->save($note3);
        
        // make request to get 2 recent notes
        $query = new RecentNotes($owner_id, $count);
        query_bus()->dispatch($query)
                   ->then(function (Collection $result) use ($note3, $note2) {
                       $this->assertEquals(2, $result->count());
                       
                       $this->assertEquals($note3->getId()->getAsString(), $result->get(0)['id']);
                       $this->assertEquals($note2->getId()->getAsString(), $result->get(1)['id']);
                   }, function (\Throwable $e) {
                       throw $e;
                   })
                   ->done();
        
    }
    
}
