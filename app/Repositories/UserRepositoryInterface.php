<?php
namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getAllUsers($key,$sort,$size);
	public function showUser($id);
	public function checkExistEmail($email);
}