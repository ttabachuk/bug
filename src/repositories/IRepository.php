<?php

interface IRepository
{
    public function get($id);
    public function getAll();
    public function delete($id);
    public function update($id);
    public function create($entity);

}