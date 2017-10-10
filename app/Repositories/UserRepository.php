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

    public function getAllUsers($key)
    {
        if (isset($key)) {
            $this->model = $this->model
                                ->orWhere("full_name", "LIKE", "%$key%")
                                ->orWhere("email", "LIKE", "%$key%");
        }
    	return $this->model->with('roles')->paginate(User::ITEMS_PER_PAGE);
    }

    public function showUser($id)
    {
    	return $this->model->with('roles')->find($id);
    }

    public function checkExistEmail($email)
    {
        if (count($this->model->where('email', $email)->get()) > 0) {
            return false;
        }
        return true;
    }
}