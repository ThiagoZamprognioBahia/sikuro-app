<?php

namespace App\Repositories\Eloquent;

use App\Models\Employee;
use App\Repositories\EmployeeRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function all()
    {
        return Employee::all();
    }

    public function find($id)
    {
        return Employee::find($id);
    }

    public function create(array $data)
    {
        return Employee::create($data);
    }

    public function update($id, array $data)
    {
        $employee = $this->findEmployeeOrFail($id);
        $employee->update($data);

        return $employee;
    }

    public function delete($id)
    {
        $employee = $this->findEmployeeOrFail($id);
        $employee->delete();

        return $employee;
    }

    public function findEmployeeOrFail($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            throw new NotFoundHttpException(__('messages.employee_not_found'));
        }

        return $employee;
    }

    public function paginate($perPage = 10)
    {
        return Employee::with('company')->paginate($perPage);
    }
}