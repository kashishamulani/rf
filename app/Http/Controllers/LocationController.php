<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
        public function getStates()
    {
        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => config('services.csc.key')
        ])->get("https://api.countrystatecity.in/v1/countries/IN/states");

        return response()->json($response->json());
    }

    // ✅ Get districts (cities) by state
    public function getDistricts($stateCode)
    {
        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => config('services.csc.key')
        ])->get("https://api.countrystatecity.in/v1/countries/IN/states/{$stateCode}/cities");

        return response()->json($response->json());
    }

    public function getCities($countryCode, $stateCode)
    {
        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => config('services.csc.key')
        ])->get("https://api.countrystatecity.in/v1/countries/{$countryCode}/states/{$stateCode}/cities");

        return response()->json($response->json());
    }
}