<?php

namespace App\Http\Services\Interfaces;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface
{
    /**
     * @param  array|string[]  $columns
     */
    public function allWithSearch(
        array $columns = ['*'],
        array $relations = [],
        int $count = 15
    ): mixed;

    /**
     * @param  array|string[]  $columns
     */
    public function getTrashedUser(
        array $columns = ['*'],
        array $relations = [],
        int $count = 15
    ): mixed;

    public function findByIdTrashedUser(
        int $id,
    ): ?Model;
}
