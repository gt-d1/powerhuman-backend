<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateCompanyRequest;

class CompanyController extends Controller
{
    // public function all(Request $request)
    // {
    //     $id = $request->input("id");
    //     $name = $request->input("name");
    //     $limit = $request->input("limit", 10);

    //     //powerhuman.com/api/commpany?id=1
    //     if ($id) {
    //         $company = Company::with(['users'])->find($id);

    //         if ($company) {
    //             return ResponseFormatter::success($company, 'Company found');
    //         }

    //         return ResponseFormatter::success('Company not found', 404);
    //     }

    //     //powerhuman.com/api/commpany
    //     $companies = Company::with(['users']);

    //     //powerhuman.com/api/commpany?name=Kunde
    //     if ($name) {
    //         $companies->where('name', 'like', '%' . $name . '%');
    //     }

    //     return ResponseFormatter::success(
    //         $companies->paginate($limit),
    //         'Companies found'
    //     );
    // }

    public function create(CreateCompanyRequest $request)
    {
        try {
            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('public/logos');
            }

            $company = Company::create([
                'name' => $request->name,
                'logo' => $path,
            ]);

            if (!$company) {
                throw new Exception('Company not cerated');
            }

            $user = User::find(Auth::id());
            $user->companies()->attach($company->id);

            return ResponseFormatter::success($company, 'Company create');
        } catch (Exception $e) {
             return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
