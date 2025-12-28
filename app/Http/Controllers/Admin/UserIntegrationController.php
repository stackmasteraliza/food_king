<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Http;
use App\Models\UserIntegration;

class UserIntegrationController extends AdminController
{

  
    public function SaveIntegrationAPI(Request $request)
    {
        $request->validate([
            'integration_id' => 'required|exists:integrations,id',
            'api_url' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'api_key' => 'nullable|string',
            'client_id' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
            'auth_method' => 'required|in:basic,token,oauth2',
        ]);

        $integration = UserIntegration::updateOrCreate(
            ['user_id' => auth()->id(), 'integration_id' => $request->integration_id],
            [...$request->all(), 'user_id' => auth()->id()]
        );

        return response()->json(['message' => 'Saved successfully', 'integration' => $integration]);
    }
    public function test(Request $request)
    {
        // $integration = UserIntegration::where('user_id', auth()->id())
        //     ->where('integration_id', $request->integration_id)
        //     ->firstOrFail();

        // try {
        //     $response = Http::withHeaders([
        //         'Authorization' => $integration->auth_method === 'token' ? 'Bearer ' . $integration->api_key : null,
        //     ])->get($integration->api_url);

        //     return response()->json(['success' => true, 'status' => $response->status()]);
        // } catch (\Exception $e) {
        //     return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        // }
    }
}
