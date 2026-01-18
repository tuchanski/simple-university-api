<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function create(array $attributes);
    public function findById(int $id);
    public function findAll() : Collection;
    public function delete(int $id) : bool;
    public function existsById(int $id) : bool;
    public function count() : int;

}
