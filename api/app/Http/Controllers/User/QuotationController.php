<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Quotation;
use App\QuotationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:users,admins']);
    }

    /*
     * method for get all quotations
     * */
    public function index()
    {
        $quotations = Quotation::with([
            'user' => function ($q) {
                $q->select('id', 'first_name', 'last_name', 'mobile', 'email');
            },
            'quotation_users' => function ($q) {
                $q->with([
                    'user' => function ($r) {
                        $r->select('id', 'first_name', 'last_name', 'mobile', 'email');
                    },
                ])->get();
            },
        ])->orderBy('id', 'DESC')->get();
        return response()->json($quotations, 200);
    }

    public function create()
    {
        //
    }

    /*
     * method for store quotation
     * */

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'product' => 'required|string|max:191',
            'quantity' => 'required|not_in:0',
            'unit_id' => 'required|not_in:0|exists:units,id'
        ])->validate();
        $data = $request->only(['product', 'quantity', 'unit_id', 'details']);
        $data += [
            'user_id' => auth()->id()
        ];
        $ss = Quotation::create($data);
        if (isset($ss)) return response()->json(['status' => 'success', 'message' => 'Successfully submit your quotation'], 200);
        return response()->json(['status' => 'error', 'message' => 'Invalid request'], 404);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Quotation $quotation)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'user_ids' => 'required|not_in:0|exists:users,id',
        ]);
        $quotation->update(['status' => 1, 'updated_by' => auth()->id()]);
        $ss = $quotation->quotationUsers()->syncWithoutDetaching($request->user_ids);
        if (isset($ss)) return response()->json(['status' => 'success', 'message' => 'Successfully supplier added'], 200);
        return response()->json(['status' => 'error', 'message' => 'Invalid request'], 404);
    }

    /*
     *
     * method for accept quotation
     * */
    public function accept(Quotation $quotation)
    {
        $ss = $quotation->update(['status' => 1, 'updated_by' => auth()->id()]);
        if (isset($ss)) return response()->json(['status' => 'success', 'message' => 'Successfully status change'], 200);
        return response()->json(['status' => 'error', 'message' => 'Invalid request'], 404);
    }

    /*
     * method for cancel quotation
     * */
    public function cancel(Quotation $quotation)
    {
        $ss = $quotation->update(['is_cancel' => 1, 'updated_by' => auth()->id()]);
        if (isset($ss)) return response()->json(['status' => 'success', 'message' => 'Successfully status change'], 200);
        return response()->json(['status' => 'error', 'message' => 'Invalid request'], 404);
    }

    /*
     * method for remove quotation
     * */
    public function destroy(Quotation $quotation)
    {
        QuotationUser::where(['quotation_id' => $quotation->id])->delete();
        return $quotation->delete();
    }

    /*
     *
     * method for get buyer rfq
     * */
    public function buyerQuotations()
    {
        $quotations = Quotation::with([
            'quotation_users' => function ($q) {
                $q->with([
                    'user' => function ($r) {
                        $r->select('id', 'first_name', 'last_name', 'mobile', 'email');
                    },
                ])->get();
            },
        ])->where(['user_id' => auth()->id()])->orderBy('id', 'DESC')->get();
        return response()->json($quotations, 200);
    }

    /*
     * method for get supplier quotations
     *
     * */
    public function supplierQuotations()
    {
        $quotation_ids = QuotationUser::where('user_id', auth()->id())->pluck('quotation_id')->unique()->whereNotNull();
        $quotations = Quotation::with([
            'unit' => function ($q) {
                $q->select('id', 'name');
            },
            'user' => function ($r) {
                $r->select('id', 'first_name', 'last_name', 'mobile', 'email');
            },
            'quotation_users' => function ($r) {
                $r->where('user_id', auth()->id());
            },
        ])->whereIn('id', $quotation_ids)->orderBy('id', 'DESC')->get();
        return response()->json($quotations, 200);
    }

    /*
     * method for supplier accept
     * */
    public function supplierAccept(QuotationUser $quotationUser)
    {
        if ($quotationUser->user_id != auth()->id()) return response()->json(['status' => 'error', 'message' => 'Access denied'], 403);
        $ss = $quotationUser->update(['status' => 1, 'response_at' => Carbon::now()]);
        if (isset($ss)) return response()->json(['status' => 'success', 'message' => 'Successfully accept'], 200);
        return response()->json(['status' => 'error', 'message' => 'Invalid request'], 404);
    }

    /*
     * method for supplier cancel rfq
     * */
    public function supplierCancel(QuotationUser $quotationUser, $note)
    {
        if ($quotationUser->user_id != auth()->id()) return response()->json(['status' => 'error', 'message' => 'Access denied'], 403);
        $ss = $quotationUser->update(['status' => 2, 'note' => $note, 'response_at' => Carbon::now()]);
        if (isset($ss)) return response()->json(['status' => 'success', 'message' => 'Successfully canceled'], 200);
        return response()->json(['status' => 'error', 'message' => 'Invalid request'], 404);
    }
}
