<?php

class User
{
    protected $Id;
    protected $Username;
    protected $RoleID;
    protected $ProjectId;
    protected $Password;
    protected $Name;

    public function getId() {
        return $this->Id;
    }

    public function getUsername() {
        return $this->Username;
    }

    public function getRoleID() {
        return $this->RoleID;
    }

    public function getProjectId() {
        return $this->ProjectId;
    }

    public function getPassword() {
        return $this->Password;
    }

    public function getName() {
        return $this->Name;
    }
}
