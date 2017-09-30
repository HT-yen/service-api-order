<?php

namespace App\Repositories;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\UserRepositoryInterface;
use App\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
	public function model()
    {
       return \App\User::class;
    }

    public function getAllUsers()
    {
    	return $this->model->with('roles')->paginate(User::ITEMS_PER_PAGE);
    }

    public function showUser($id)
    {
    	return $this->model->with('roles')->find($id);
    }

}