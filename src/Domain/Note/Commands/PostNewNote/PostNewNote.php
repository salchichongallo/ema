<?php
declare(strict_types=1);

namespace EMA\Domain\Note\Commands\PostNewNote;

use Assert\Assert;
use \EMA\Domain\Foundation\Command\Command;
use EMA\Domain\Foundation\VO\Identity;
use EMA\Domain\Note\Model\VO\NoteText;


class PostNewNote implements Command
{
    
    /** @var  NoteText */
    private $text;
    /** @var  Identity */
    private $id;
    /** @var  Identity */
    private $owner_id;
    
    /**
     * PostNewNote constructor.
     *
     * @param NoteText   $text
     * @param Identity $id
     * @param Identity $owner_id
     */
    public function __construct(NoteText $text, Identity $id, Identity $owner_id)
    {
        $this->text     = $text;
        $this->id       = $id;
        $this->owner_id = $owner_id;
    }
    
    /**
     * @return NoteText
     */
    public function getText(): NoteText
    {
        return $this->text;
    }
    
    /**
     * @return Identity
     */
    public function getId(): Identity
    {
        return $this->id;
    }
    
    /**
     * @return Identity
     */
    public function getOwnerId(): Identity
    {
        return $this->owner_id;
    }
    
    
}
