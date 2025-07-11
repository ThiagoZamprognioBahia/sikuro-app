<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CompanyRepositoryInterface;
use Illuminate\Http\Request;

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
        return response()->json($companies);
    }

    public function show($id)
    {
        $company = $this->companyRepository->find($id);
        
        if ($company) {
            return response()->json($company);
        }
        
        return response()->json(['message' => 'Company not found'], 404);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'logo_path' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'website' => 'nullable|url',
        ]);

        $company = $this->companyRepository->create($data);

        return response()->json($company, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'logo_path' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'website' => 'nullable|url',
        ]);

        $company = $this->companyRepository->update($id, $data);

        if ($company) {
            return response()->json($company);
        }

        return response()->json(['message' => 'Company not found'], 404);
    }

    public function destroy($id)
    {
        $deleted = $this->companyRepository->delete($id);

        if ($deleted) {
            return response()->json(['message' => 'Company successfully deleted']);
        }

        return response()->json(['message' => 'Company not found'], 404);
    }
}