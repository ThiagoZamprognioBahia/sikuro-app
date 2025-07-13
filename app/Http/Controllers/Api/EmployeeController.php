<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Repositories\EmployeeRepositoryInterface;

class EmployeeController extends Controller
{
    protected $employeeRepository;

    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function index()
    {
        $employees = $this->employeeRepository->paginate(10);

        return EmployeeResource::collection($employees);
    }

    public function show($id)
    {
        $employee = $this->employeeRepository->findEmployeeOrFail($id);

        return new EmployeeResource($employee);
    }

    public function store(StoreEmployeeRequest $request)
    {
        $data = $request->validated();

        $employee = $this->employeeRepository->create($data);

        return new EmployeeResource($employee);
    }

    public function update(UpdateEmployeeRequest $request, $id)
    {
        $data = $request->validated();

        $employee = $this->employeeRepository->update($id, $data);

        return new EmployeeResource($employee);
    }

    public function destroy($id)
    {
        $this->employeeRepository->delete($id);

        return response()->json(['message' => __('messages.employee_deleted')]);
    }
}