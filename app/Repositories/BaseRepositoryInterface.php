<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function create(array $attributes);
    public function findById(int $id);
    public function findAll() : LengthAwarePaginator;
    public function delete(int $id) : bool;
    public function existsById(int $id) : bool;
    public function count() : int;

}
