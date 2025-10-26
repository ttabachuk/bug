<?php

class Project
{
    protected $Id;
    protected $Project;

    public function getProject() {
        return $this->Project;
    }

    public function getId() {
        return $this->Id;
    }
}