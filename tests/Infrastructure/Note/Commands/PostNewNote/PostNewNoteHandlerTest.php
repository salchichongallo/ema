<?php
declare(strict_types=1);

namespace EMA\Tests\Infrastructure\Note\Commands\PostNewNote;

use function DI\object;
use EMA\Domain\Note\Model\Collection\NoteCollection;
use EMA\Infrastructure\Note\Collection\DoctrineNoteCollection;
use EMA\Tests\BaseTest;

class PostNewNoteHandlerTest extends \EMA\Tests\Domain\Note\Commands\PostNewNote\PostNewNoteHandlerTest
{
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        
        $this->restartContainer();
        container()->set(NoteCollection::class, object(DoctrineNoteCollection::class));
        
        $this->migrate();
    }
    
}