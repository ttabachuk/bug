<?php

class Bug
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

    public function getId() {
        return $this->id;
    }

    public function getProjectId() {
        return $this->projectId;
    }

    public function getOwnerId() {
        return $this->ownerId;
    }

    public function getAssignedToId() {
        return $this->assignedToId;
    }

    public function getStatusId() {
        return $this->statusId;
    }

    public function getPriorityId() {
        return $this->priorityId;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getFixDescription() {
        return $this->fixDescription;
    }

    public function getDateRaised() {
        return $this->dateRaised;
    }

    public function getTargetDate() {
        return $this->targetDate;
    }

    public function getDateClosed() {
        return $this->dateClosed;
    }
}
