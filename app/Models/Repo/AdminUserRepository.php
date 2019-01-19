<?php

namespace App\Models\Repo;

use App\Models\Repo\Interfaces\UserRepositoryInterface;
use App\Models\Orm\Analysis\SystemUsers as User;

/**
 *  The user repository.
 *
 * @author zhaoqiying
 *
 */
class UserRepository extends AbsEloquentRepository implements UserRepositoryInterface
{
    /**
     * Class constructor.
     *
     * @param  User $model
     *
     * @return EloquentUserRepository
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Creates a new instance of the user.
     *
     * @param  array $fields
     *
     * @return Model
     */
    public function create(array $fields)
    {
        $fields['password'] = bcrypt($fields['password']);

        return $this->model->create($fields);
    }

    /**
     * Update an instance of the user.
     *
     * @param  array $fields
     *
     * @return Model
     */
    public function updateById(array $fields, $model_id)
    {
        $user = $this->getById($model_id);

        if (array_key_exists('password', $fields)) {
            if (empty($fields['password'])) {
                unset($fields['password']);
            } else {
                $fields['password'] = bcrypt($fields['password']);
            }
        }

        $user->update($fields);

        return $user;
    }

    /**
     * Find user by the email token.
     *
     * @param  string $token
     *
     * @return User
     */
    public function findByEmailToken($token)
    {
        return $this->model->where('email_token', $token)->first();
    }
}
