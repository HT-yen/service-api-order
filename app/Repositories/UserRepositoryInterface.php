<?php
namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getAllUsers();
	public function showUser($id);
}