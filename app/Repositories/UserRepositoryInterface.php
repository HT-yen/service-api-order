<?php
namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getAllUsers($key);
	public function showUser($id);
}