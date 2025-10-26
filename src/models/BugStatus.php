<?php

class BugStatus
{
    protected $Id;
    protected $Status;

    public function getId(){
        return $this->Id;
    }

    public function getStatus(){
        return $this->Status;
    }
}
