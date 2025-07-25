<?php

namespace App\Repositories;

interface CompanyRepositoryInterface
{
    public function all();

    public function find($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);
    
    public function findCompanyOrFail($id);

    public function paginate($perPage = 10);
}
