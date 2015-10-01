<?php 

namespace Thinmy\CachedEloquentUser;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class CachedEloquentUserProvider implements UserProvider {

    protected $hasher;

    protected $model;

    public function __construct(Hasher $hasher, $model)
    {
        $this->model = $model;
        $this->hasher = $hasher;
    }

    public function retrieveById($identifier)
    {
        $model = $this->createModel();
        return \Cache::remember('userById_' . $identifier, 60,
            function () use ($model, $identifier) {
                return $model->newQuery()->find($identifier);
            }
        );
    }

    
    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();
        return \Cache::remember('userByIdAndToken_' . $identifier . $token, 60,
            function () use ($model, $identifier, $token) {
                return $model->newQuery()
                    ->where($model->getKeyName(), $identifier)
                    ->where($model->getRememberTokenName(), $token)
                    ->first();
            }
        );
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->createModel()->newQuery();
        foreach ($credentials as $key => $value)
        {
            if ( ! str_contains($key, 'password')) $query->where($key, $value);
        }
        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Auth\UserInterface  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];
        return $this->hasher->check($plain, $user->getAuthPassword());
    }

    /**
     * Create a new instance of the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
        $class = '\\'.ltrim($this->model, '\\');
        return new $class;
    }
}