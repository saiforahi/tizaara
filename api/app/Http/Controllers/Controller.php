<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function pagination(LengthAwarePaginator $data, string $jsonResource = null)
    {
        if (!empty($jsonResource)) {
            $dataTmp = $jsonResource::collection($data->getCollection());
            $data->setCollection($dataTmp->collection);
        }

        $meta = [
            'current_page' => $data->currentPage(),
            'data' => $data->items(),
            'first_page_url' => $data->url(1),
            'from' => $data->firstItem(),
            'last_page' => $data->lastPage(),
            'last_page_url' => $data->url($data->lastPage()),
            'next_page_url' => $data->nextPageUrl(),
            'path' => $data->path(),
            'per_page' => $data->perPage(),
            'prev_page_url' => $data->previousPageUrl(),
            'to' => $data->lastItem(),
            'total' => $data->total()
        ];

        return response()->json([
            'draw' => request()->get('draw'),
            'data' => array_merge([
                'data' => $data->items()
            ], $meta)
        ], 200);
    }

    protected function saveAddress($address, $request)
    {
        $address->country_id = $request->country;
        $address->division_id = $request->division;
        $address->city_id = $request->city;
        $address->area_id = $request->area;
        $address->address = $request->address;
        $address->zip_code = $request->zip_code;
        $address->save();
        return $address->id;
    }

    public function phone_number($number)
    {
        if (preg_match("/^(?:\+88|01)?(?:\d{11}|\d{13})$/", $number)) {
            $length = strlen($number);
            if ($length === 11 && stripos($number, "+88") == false) return "+88" . $number;
            elseif ($length === 14 && stripos($number, "88") && preg_match('/^\+?\d+$/', $number)) return $number;
            else return false;
        } else return false;
    }
}
