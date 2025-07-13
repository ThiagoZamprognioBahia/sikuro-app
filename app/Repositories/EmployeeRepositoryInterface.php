<?php

namespace App\Repositories;

interface EmployeeRepositoryInterface
{
    public function paginate($perPage);

    public function findEmployeeOrFail($id);

    public function create(array $data);

    public function update($id, array $data);
    
    public function delete($id);
}