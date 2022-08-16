<?php

namespace App\Http\Controllers\User;

use App\Address;
use App\AnnualOutput;
use App\CompanyCertificate;
use App\CompanyNearestPort;
use App\Http\Controllers\Controller;
use App\ProductionLine;
use App\RndStaff;
use App\StaffNumber;
use App\Traits\FileUpload;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    use FileUpload;
    public function index()
    {
        $user = User::where('id', Auth::user()->id)->select('id', 'first_name', 'last_name', 'job_title', 'email', 'mobile', 'telephone', 'photo', 'photo_type')->first();
        $address = Address::where('addressable_id', $user->id)->where('address_type', 'user')->first();
        if (!$address) {
            $user['status'] = "new";
            return $user;
        }
        $user['status'] = "old";
        $user['country'] = $address->country_id;
        $user['division'] = $address->division_id;
        $user['city'] = $address->city_id;
        $user['area'] = $address->area_id;
        $user['address'] = $address->address;
        $user['zip_code'] = $address->zip_code;
        return $user;
    }

    public function store(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->job_title = $request->job_title;
        $user->telephone = $request->telephone_no;
        if ($request->photo != '' && strlen($request->photo) > 200){
            $photo= $this->saveImagesVue2($request->photo, 'upload/user/profile/');
            $user->photo =$photo;
        }
        $user->save();
        $address = Address::where('addressable_id', $user->id)->where('address_type', 'user')->first();
        if ($address) {
            $address->country_id = $request->country;
            $address->division_id = $request->division;
            $address->city_id = $request->city;
            $address->area_id = $request->area;
            $address->address = $request->address;
            $address->zip_code = $request->zip_code;
            $address->updated_by = auth()->user()->id;
            $address->save();
        } else {
            $address = new Address();
            $address->addressable_id = $user->id;
            $address->address_type = 'user';
            $address->country_id = $request->country;
            $address->division_id = $request->division;
            $address->city_id = $request->city;
            $address->area_id = $request->area;
            $address->address = $request->address;
            $address->zip_code = $request->zip_code;
            $address->created_by = auth()->user()->id;
            $address->save();
        }
        return response()->json(['status'=>'success','message'=>'Successfully updated'],200);
    }

    /*
     * method for only user photo change
     * */
    public function userPhotoChange(Request $request)
    {
        if ($request->profile_photo != '' && strlen($request->profile_photo) > 200){
            $photo= $this->saveImagesVue2($request->profile_photo, 'upload/user/profile/');
            auth()->user()->update(['photo_type'=>0,'photo'=>$photo]);
            return response()->json(['status'=>'success','message'=>'Successfully updated profile photo'],200);
        }
        return response()->json(['status'=>'error','message'=>'Invalid request'],404);
    }
    /*
     * method for certification store
     * */
    public function certificateStore(Request $request)
    {
        /*request data validation*/
        $this->cerVal($request->all())->validate();

        $data = $request->all();

        $certificate_photo_name=null;
        if ($request->photo != '' && strlen($request->photo) > 200){
            $certificate_photo_name= $this->saveImagesVue2($request->photo, 'upload/company/certificate/');
        }
        /*get user company*/
        $company = auth()->user()->companyBasicInfo;
        $data +=[
            'certificate_photo_name'=>$certificate_photo_name,
          'company_id'=>$company?$company->id:null,
            'created_by'=>auth()->id(),
        ];
        $ss= CompanyCertificate::create($data);
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully store'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }

    /*
     * method for get all certificates information for auth user
     * */
    public function getCertificates()
    {
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id)) return response()->json([],200);

        $certificates = CompanyCertificate::where(['company_id'=>$company->id])->get(['id','name','reference_number','issued_by','start_date','end_date','certificate_photo_name']);
        return response()->json($certificates,200);
    }

    /*
     * method for company certificate update
     * */
    public function certificateUpdate(Request $request, CompanyCertificate $companyCertificate)
    {
        /*request data validation*/
        $this->cerVal($request->all())->validate();
        /*authorize check*/
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id) || $company->id !=$companyCertificate->company_id) return response()->json(['status'=>'fail','message'=>'you are Unauthorized for this operation'],403);

        $data = $request->all();
        $certificate_photo_name=null;
        if ($request->photo != '' && strlen($request->photo) > 200){
            $certificate_photo_name= $this->saveImagesVue2($request->photo, 'upload/company/certificate/');
            File::delete(public_path($companyCertificate->certificate_photo_name));
        }
        /*get user company*/
        $data +=[
            'certificate_photo_name'=>$certificate_photo_name?$certificate_photo_name:$companyCertificate->certificate_photo_name,
            'updated_by'=>auth()->id(),
        ];
        $ss= $companyCertificate->update($data);
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully updated'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }
    /*
     * method for certificate info validation
     * */
    private function cerVal(array $data){
        return Validator::make($data,[
            'name'=>'required|string|max:150',
            'reference_number'=>'required|string|max:15',
            'issued_by'=>'required|string|max:150',
            'start_date'=>'required|date',
            'end_date'=>'required|date',
        ]);
    }

    /*
     * method for company certificate delete
     * */
    public function removeCertificate(CompanyCertificate $companyCertificate)
    {
        /*authorize check*/
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id) || $company->id !=$companyCertificate->company_id) return response()->json(['status'=>'fail','message'=>'you are Unauthorized for this operation'],403);

        $ss= $companyCertificate->delete();
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully remove'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }

    /*
     * method for get qc,rnd staff, production line and annual output list
     * */
    public function RQPAOutput()
    {
        return $data=[
            'staff_numbers'=>StaffNumber::all(['id','number']),
            'rnd_staffs'=>RndStaff::all(['id','name']),
            'production_lines'=>ProductionLine::all(['id','name']),
            'annual_outputs'=>AnnualOutput::all(['id','output']),
        ];
    }

    /*
     * method for get all nearest port info
     * */
    public function nearestPorts()
    {
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id)) return response()->json([],200);

        return CompanyNearestPort::where(['company_id'=>$company->id])->get(['id','name','address']);
    }

    /*
     * method for store company nearest port
     * */
    public function nearestPortStore(Request $request)
    {
        Validator::make($request->all(),[
            'name'=>'required|string|max:100',
            //'address'=>'required|string'
        ])->validate();
        $company = auth()->user()->companyBasicInfo;
        $data = $request->all();
        $data +=[
            'company_id'=>$company?$company->id:null,
            'created_by'=>auth()->id(),
        ];
        $ss = CompanyNearestPort::create($data);
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully store'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }

    /*
     * method for update nearest port
     * */
    public function nearestPortUpdate(Request $request, CompanyNearestPort $companyNearestPort)
    {
        Validator::make($request->all(),[
            'name'=>'required|string|max:100',
            //'address'=>'required|string'
        ])->validate();
        /*authorize check*/
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id) || $company->id !=$companyNearestPort->company_id) return response()->json(['status'=>'fail','message'=>'you are Unauthorized for this operation'],403);

        $data = $request->all();
        $data +=[
            'updated_by'=>auth()->id(),
        ];
        $ss = $companyNearestPort->update($data);
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully updated'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }

    /*
     * method for remove company nearest port information
     * */
    public function removeNearestPort(CompanyNearestPort $companyNearestPort)
    {
        /*authorize check*/
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id) || $company->id !=$companyNearestPort->company_id) return response()->json(['status'=>'fail','message'=>'you are Unauthorized for this operation'],403);

        $ss = $companyNearestPort->delete();
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully remove'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }

}
