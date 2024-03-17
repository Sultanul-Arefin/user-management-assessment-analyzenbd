<?php

namespace App\Http\Services\Classes;

use App\Http\Services\Classes\BaseRepository;
use App\Http\Services\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * User Repository constructor.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param  array|string[]  $columns
     */
    public function allWithSearch(
        array $columns = ['*'],
        array $relations = [],
        int $count = 15
    ): mixed {
        return $this->searchQuery($relations, $count);
    }

    private function searchQuery($relations, $count)
    {
        return $this->model::query()
                ->where('created_by', auth()->user()->id)
                ->with($relations)
                ->paginate($count);
    }

    /**
     * @param  array|string[]  $columns
     */
    public function getTrashedUser(
        array $columns = ['*'],
        array $relations = [],
        int $count = 15
    ): mixed {
        return $this->model::query()
                ->onlyTrashed()
                ->where('created_by', auth()->user()->id)
                ->with($relations)
                ->paginate($count);
    }

    /**
     * @param  array|string[]  $columns
     */
    public function findByIdTrashedUser(
        int $id,
    ): ?Model {
        return $this->model::query()
                ->onlyTrashed()
                ->findOrFail($id);
    }
}
