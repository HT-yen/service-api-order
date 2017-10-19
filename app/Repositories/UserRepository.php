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

    public function getAllUsers($key,$sort,$size)
    {
        if (isset($sort))
        {
            $directionSort = 'ASC';
            if ($sort[0] == '-') {
                $directionSort = 'DESC';
                $sort = substr($sort, 1);
            }
            $this->model = $this->model->orderBy($sort, $directionSort);
        }
        if (isset($key)) {
            $this->model = $this->model
                                ->orWhere("full_name", "LIKE", "%$key%")
                                ->orWhere("email", "LIKE", "%$key%");
        }
    	return $this->model->with('roles')->paginate(isset($size) ? $size : User::ITEMS_PER_PAGE);
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