<?php

namespace App\Service\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface CrudServiceInterface
{
    public function all(array $params):LengthAwarePaginator|Collection|null; 
    public function find(int $id):Model|Collection|null;
    public function save(array $data):?Model;
    public function update(array $data,int $id):Model|bool|null;
    public function delete(int $id):bool;
}
