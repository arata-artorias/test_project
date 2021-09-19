<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyCollection;
use App\Models\User;
use App\Models\FavouriteCompany;

class CompanyController extends Controller
{
    /**
     * get all Companies data based on search
     *
     * @param Request $request
     * @return void
     */
    public function getCompanies(Request $request, $data = '')
    {
        $search = $data;

        if ($search == '') {
            return new CompanyCollection(Company::paginate(10));
        }

        return new CompanyCollection(Company::ofCompanySearch($search)->paginate(10));
    }

    /**
     * add Company data
     *
     * @param Request $request
     * @return void
     */
    public function addCompany(Request $request)
    {
        $this->validation($request);

        $company = Company::create($request->all());

        return response()->json(["message" => "Company is added", "data" => $company->toArray()], 200);
    }

    /**
     * add favorite company of each user
     *
     * @param Request $request
     * @return void
     */
    public function addFavouriteCompany(Request $request)
    {
        $company_id = $request->company_id;
        $user_id = auth()->user()->id;

        $company = Company::find($company_id);

        if (!$company) {
            return response()->json(["message" => "Company Not Found"], 200);
        }

        $data = FavouriteCompany::ofCompanyIdSearch($company_id)->ofUserIdSearch($user_id)->get();

        $user = User::find($user_id);

        if ($data->isEmpty()) {
            $user->companies()->attach($company_id);
            $msg = 'added to';
        } else {
            $user->companies()->detach($company_id);
            $msg = 'removed from';
        }

        return response()->json(["message" => "Company is ".$msg." Favourite List", "data" => $user->companies()->get()], 200);
    }

    /**
     * get favourite companies list of each user
     *
     * @param Request $request
     * @return void
     */
    public function viewFavouriteCompaniesList(Request $request)
    {
        $user = User::find(auth()->user()->id);
        return response()->json(["data" => $user->companies()->get()], 200);
    }

    /**
     * Validate user input
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Array
     */
    public function validation(Request $request)
    {
        return $request->validate([
            'company_name' => 'required',
            'address' => 'required',
            'phone_number' => 'required',
        ]);
    }
}
