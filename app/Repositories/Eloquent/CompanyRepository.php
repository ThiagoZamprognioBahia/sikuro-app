<?php

namespace App\Repositories\Eloquent;

use App\Models\Company;
use App\Repositories\CompanyRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function all()
    {
        return Company::all();
    }

    public function find($id)
    {
        return Company::find($id);
    }

    public function create(array $data)
    {
        return Company::create($data);
    }

    public function update($id, array $data)
    {
        $company = $this->findCompanyOrFail($id);
        $company->update($data);

        return $company;
    }

    public function delete($id)
    {
        $company = $this->findCompanyOrFail($id);
        return $company->delete();
    }

    public function findCompanyOrFail($id)
    {
        $company = Company::find($id);

        if (!$company) {
            throw new NotFoundHttpException(__('messages.company_not_found'));
        }

        return $company;
    }

    public function paginate($perPage = 10)
    {
        return Company::paginate($perPage);
    }
}