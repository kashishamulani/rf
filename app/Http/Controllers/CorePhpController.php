<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CorePhpController extends Controller
{
    protected $baseUrl;

    public function __construct()
    {
        // Check if running on local environment
        if (app()->environment('local')) {
            $this->baseUrl = 'http://localhost/reliance_core';
        } else {
            $this->baseUrl = 'https://ebiztechnologies.in/reliance_core';
        }
    }

    /**
     * Fetch users from Core PHP API
     */
    public function getUsers()
    {
        $url = $this->baseUrl . "/api/get-users.php";

        try {
            Log::info('Fetching users from: ' . $url);
            
            $response = Http::timeout(10)->get($url);
            
            if ($response->failed()) {
                Log::error('Failed to fetch users', [
                    'url' => $url,
                    'status' => $response->status()
                ]);
                return response()->json(['error' => 'Failed to fetch users'], 500);
            }
            
            $users = $response->json();
            
            // Option 1: return JSON
            return response()->json($users);
            
            // Option 2: send to blade view (uncomment if needed)
            // return view('core-users', compact('users'));
            
        } catch (\Exception $e) {
            Log::error('Exception while fetching users', [
                'url' => $url,
                'message' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch registers from Core PHP API
     */
    public function getRegisters()
    {
        $url = $this->baseUrl . "/api/get-registers.php";

        try {
            Log::info('Fetching registers from: ' . $url);
            
            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                Log::error('Failed to fetch registers', [
                    'url' => $url,
                    'status' => $response->status()
                ]);
                return response()->json(['error' => 'Failed to fetch registers'], 500);
            }

            return response()->json($response->json());
            
        } catch (\Exception $e) {
            Log::error('Exception while fetching registers', [
                'url' => $url,
                'message' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Fetch specific request by form_id from Core PHP API
     * 
     * @param int $form_id
     */
    public function getrequest($form_id)
    {
        $url = $this->baseUrl . "/api/get-requests.php?id=" . $form_id;

        try {
            Log::info('Fetching request for form_id: ' . $form_id . ' from: ' . $url);
            
            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                Log::error('Failed to fetch request', [
                    'url' => $url,
                    'form_id' => $form_id,
                    'status' => $response->status()
                ]);
                return response()->json(['error' => 'Failed to fetch request'], 500);
            }

            return response()->json($response->json());
            
        } catch (\Exception $e) {
            Log::error('Exception while fetching request', [
                'url' => $url,
                'form_id' => $form_id,
                'message' => $e->getMessage()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Alternative method to fetch registers if you want to return to a view
     */
    public function showRegisters()
    {
        $url = $this->baseUrl . "/api/get-registers.php";

        try {
            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                return view('registers', ['registers' => [], 'error' => 'Failed to fetch registers']);
            }

            $registers = $response->json();
            return view('registers', compact('registers'));
            
        } catch (\Exception $e) {
            return view('registers', ['registers' => [], 'error' => $e->getMessage()]);
        }
    }

    /**
     * Method to check API connection status
     */
    public function checkConnection()
    {
        $endpoints = [
            'users' => '/api/get-users.php',
            'registers' => '/api/get-registers.php',
            'requests' => '/api/get-requests.php?id=1' // Test with ID 1
        ];
        
        $status = [];
        
        foreach ($endpoints as $name => $endpoint) {
            $url = $this->baseUrl . $endpoint;
            
            try {
                $response = Http::timeout(5)->get($url);
                $status[$name] = [
                    'url' => $url,
                    'status' => $response->status(),
                    'working' => $response->successful()
                ];
            } catch (\Exception $e) {
                $status[$name] = [
                    'url' => $url,
                    'error' => $e->getMessage(),
                    'working' => false
                ];
            }
        }
        
        return response()->json([
            'environment' => app()->environment(),
            'base_url' => $this->baseUrl,
            'endpoints' => $status
        ]);
    }
}