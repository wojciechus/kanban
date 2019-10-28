<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;
/**
 * @method static Action COMPLETED()
 * @method static Action ACTIVE()
 * @method static Action QUEUED()
 */
class KanbanIssueStatus extends Enum
{
    public const COMPLETED = 'completed';
    public const ACTIVE = 'active';
    public const QUEUED = 'queued';
}
