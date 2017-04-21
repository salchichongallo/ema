<?php
declare(strict_types=1);

namespace EMA\App\Query\Note\AllNotes;

use EMA\Domain\Foundation\Command\Authorizer;
use EMA\Domain\Foundation\VO\Identity;

class AllNotesAuthorizer extends Authorizer
{
    
    /**
     * denied
     *
     *
     * @param Identity                            $user_id
     * @param AllNotes $command
     *
     * @return bool
     */
    public function denied(Identity $user_id, $command): bool
    {
        return !$user_id->isEqual($command->getOwnerId());
    }
    
}