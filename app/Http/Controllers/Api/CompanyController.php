<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Repositories\CompanyRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    protected $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        $companies = $this->companyRepository->all();
        return CompanyResource::collection($companies);
    }

    public function show($id)
    {
        $company = $this->companyRepository->findCompanyOrFail($id);

        return new CompanyResource($company);
    }

    public function store(StoreCompanyRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo_path')) {
            $path = $request->file('logo_path')->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        $company = $this->companyRepository->create($data);

        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request, $id)
    {
        $data = $request->validated();

        if ($request->hasFile('logo_path')) {
            $company = $this->companyRepository->findCompanyOrFail($id);
            if ($company && $company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $path = $request->file('logo_path')->store('logos', 'public');
            $data['logo_path'] = $path;
        }

        $company = $this->companyRepository->update($id, $data);

        return new CompanyResource($company);
    }

    public function destroy($id)
    {
        $company = $this->companyRepository->findCompanyOrFail($id);

        if ($company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
        }

        $this->companyRepository->delete($id);

        return response()->json(['message' => __('messages.company_deleted')]);
    }
}