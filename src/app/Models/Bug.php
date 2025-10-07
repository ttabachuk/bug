<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    protected $id;
    protected $projectId;
    protected $ownerId;
    protected $assignedToId;
    protected $statusId;
    protected $priorityId;
    protected $summary;
    protected $description;
    protected $fixDescription;
    protected $dateRaised;
    protected $targetDate;
    protected $dateClosed;
}
