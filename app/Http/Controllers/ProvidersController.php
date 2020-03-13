<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Provider;

class ProvidersController extends Controller
{
    public function getAllProviders() {
        $providers = Provider::all();
        return response()->json(["providers" => $providers]);
    }

    public function getProvider(Request $request) {
        $provider = Provider::where('id', $request->id)->first();
        return response()->json(['provider' => $provider]);
    }

    public function newProvider(Request $request) {        
        $provider = Provider::create($request->only(['name', 'phone', 'contact_person', 'location', 'is_active']));
        return response()->json(['provider' => $provider]);
    }

    public function updateProvider(Request $request) {
        $provider = Provider::where('id', $request->id)->first();
        $provider->name = $request->name;
        $provider->phone = $request->phone;
        $provider->contact_person = $request->contact_person;
        $provider->location = $request->location;
        $provider->is_active = $request->is_active;
        $provider->save();
        return response()->json(['provider' => $provider]);
    }

    public function deleteProvider(Request $request) {
        Provider::where('id', $request->id)->delete();        
        return response()->json('success');
    }
}
