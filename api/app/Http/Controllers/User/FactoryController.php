<?php

namespace App\Http\Controllers\User;

use App\Address;
use App\CompanyBasicInfo;
use App\CompanyDetails;
use App\CompanyFactory;
use App\Http\Controllers\Controller;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class FactoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    use FileUpload;

    public function index()
    {
        $factory = CompanyBasicInfo::with(['businessTypes'])->where('user_id', auth()->id())->first();
        $factory_details = CompanyDetails::where('user_id', auth()->id())->first();

        if (!$factory) {
            return response()->json(['result' => 'Success', 'company' => $factory, 'company_details' => $factory_details], 200);
        }
        $address = Address::find($factory->reg_address_id);

        $factory['country'] = $address->country_id;
        $factory['division'] = $address->division_id;
        $factory['city'] = $address->city_id;
        $factory['area'] = $address->area_id;
        $factory['address'] = $address->address;
        $factory['zip_code'] = $address->zip_code;

        if ($factory->address_type == 2) {
            $address2 = Address::find($factory->ope_address_id);
            $factory['country2'] = $address2->country_id;
            $factory['division2'] = $address2->division_id;
            $factory['city2'] = $address2->city_id;
            $factory['area2'] = $address2->area_id;
            $factory['address2'] = $address2->address;
            $factory['zip_code2'] = $address2->zip_code;
        }

        return response()->json(['result' => 'Success', 'company' => $factory, 'company_details' => $factory_details], 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $Company = CompanyBasicInfo::where('user_id', auth()->id())->first();
        $address = Address::where('addressable_id', auth()->id())->where('address_type', 'register')->first();
        $address2 = Address::where('addressable_id', auth()->id())->where('address_type', 'operation')->first();
        if ($request->address_type == 1) {
            if ($address2) {
                $address2->delete();
            }
        }
        if ($request->address_type == 2 && $request->country2 != null) {
            if (!$address2) {
                $address2 = new Address();
                $address2->addressable_id = auth()->id();
                $address2->address_type = "operation";
                $address2->created_by = auth()->id();
            } else {
                $address2->updated_by = auth()->id();
            }
            $address2->country_id = $request->country2;
            $address2->division_id = $request->division2;
            $address2->city_id = $request->city2;
            $address2->area_id = $request->area2;
            $address2->address = $request->address2;
            $address2->zip_code = $request->zip_code2;
            $address2->save();
        }
        if (!$address) {
            $address = new Address();
            $address->addressable_id = auth()->id();
            $address->address_type = "register";
            $address->created_by = auth()->id();
        } else {
            $address->updated_by = auth()->id();
        }

        $address = $this->saveAddress($address, $request);

        if (!$Company) {
            $Company = new CompanyBasicInfo();
            $Company->user_id = auth()->id();
            $Company->reg_address_id = $address;
        }

        if ($request->address_type == 1) {
            $Company->ope_address_id = null;
        } else {
            $Company->ope_address_id = $address2->id;
        }

        $Company->address_type = $request->address_type;
        $Company->name = $request->name;
        $Company->display_name = $request->display_name;
        $Company->establishment_date = $request->establishment_date;
        $Company->office_space = $request->office_space;
        $Company->website = $request->website;
        $Company->email = $request->email;
        $Company->phone = $request->phone;
        $Company->cell = $request->cell;
        $Company->fax = $request->fax;
        $Company->number_of_employee = $request->number_of_employee;
        $Company->ownership_type = $request->ownership_type;
        $Company->revenue = $request->revenue;
        $Company->main_product = $request->main_product;
        $Company->other_product = $request->other_product;
        $Company->save();
        /*
         * business type sync
         * */
        $Company->businessTypes()->sync($request->business_types);

        return response()->json(['status'=>'success','message'=>'Success fully updated']);
    }

    /*
     * update factory more information
     * */
    public function factoryDetails(Request $request)
    {
        $factory = CompanyDetails::where('user_id', Auth::user()->id)->first();

        if (!$factory) {
            $factory = new CompanyDetails();
            $factory->user_id = Auth::user()->id;
        }

        if ( $request->company_logo != '' && strlen($request->company_logo) > 200) {
            File::delete(public_path($factory->company_logo));
            $factory->company_logo = $this->saveImagesVue2($request->company_logo, 'upload/company/logo/');
        }

        $photos = [];
        foreach ($request->company_photos as $photo) {
            if (array_key_exists("path", $photo) && strlen($photo['path']) > 200) {
                $image = $this->saveImagesVue($photo, 'path', 'upload/company/gallery/', 920, 920);
                array_push($photos, $image);
            } else {
                foreach (json_decode($factory->company_photos) as $pho) {
                    if (strpos($photo['path'], $pho)) {
                        array_push($photos, $pho);
                    }
                }
            }
        }

        $factory->company_photos = json_encode($photos);
        $factory->about_us = $request->about_us;
        $factory->mission = $request->mission;
        $factory->vision = $request->vision;
        $factory->company_video = $request->company_video;
        $factory->facebook_url = $request->facebook_url;
        $factory->save();
        return response()->json(['status'=>'success','message'=>'Successfully updated'],200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    /*
     * method for check company owner unique name
     * */
    public function uniqNameCheck($name)
    {
        $company = auth()->user()->companyBasicInfo;
        if (isset($company->id)) $chk = CompanyBasicInfo::where('id','!=',$company->id)->where(['display_name'=>$name])->count();
        else $chk = CompanyBasicInfo::where(['display_name'=>$name])->count();
        if ($chk>0) return true;
        else return false;
    }

    /*
     * company_factories table
     * method for company factory info store
     * */
    public function companyFactoryStore(Request $request)
    {
        /*request data validate*/
        $this->cmpVal($request->all());

        $data =$request->all();
        $company = auth()->user()->companyBasicInfo;
        $data +=[
            'company_id'=>$company?$company->id:null,
            'created_by'=>auth()->id(),
        ];
        $ss = CompanyFactory::create($data);
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully created'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }

    /*
     * method for company factory update
     * */
    public function companyFactoryUpdate(Request $request,CompanyFactory $companyFactory)
    {
        //return $companyFactories;
        /*request data validate*/
        $this->cmpVal($request->all());
        /*permission check*/
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id) || $company->id != $companyFactory->company_id) return response()->json(['status'=>'Fail','message'=>'Access denied'],403);

        $data =$request->all();
        $data +=[
            'updated_by'=>auth()->id(),
        ];
        $ss = $companyFactory->update($data);
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully updated'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);
    }

    /*
     * private method for validate company factory data
     * */
    private function cmpVal(array $data){
        return Validator::make($data,[
            'name'=>'required|string|max:100',
            'factory_space'=>'required|string|max:250',
            'staff_number_id'=>'required|not_in:0|exists:staff_numbers,id',
            'rnd_staff_id'=>'required|not_in:0|exists:rnd_staff,id',
            'production_line_id'=>'required|not_in:0|exists:production_lines,id',
            'annual_output_id'=>'required|not_in:0|exists:annual_outputs,id',
        ])->validate();
    }

    /*
     * method for company factory remove
     * */
    public function companyFactoryRemove(CompanyFactory $companyFactory)
    {
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id) || $company->id != $companyFactory->company_id) return response()->json(['status'=>'Fail','message'=>'Access denied'],403);
        $ss = $companyFactory->delete();
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully remove'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);
    }
    /*
     * method for get company factory information
     * */
    public function companyFactories()
    {

        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id)) return [];
        return CompanyFactory::with([
            'staffNumber'=>function($q){$q->select('id','number'); },
            'rndStaff'=>function($q){$q->select('id','name'); },
            'productionLine'=>function($q){$q->select('id','name'); },
            'annualOutput'=>function($q){$q->select('id','output'); },
        ])->where(['company_id'=>$company->id])->get();
    }

}
