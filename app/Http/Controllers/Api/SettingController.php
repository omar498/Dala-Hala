<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SettingStoreRequest;
use App\Http\Requests\SettingUpdateRequest;

class SettingController extends Controller
{
    public function add(SettingStoreRequest $request)
    {

        $setting = Setting::create($request->validated());


        return response()->json([
            'message' => 'Setting added successfully!',
            'data' => new SettingResource($setting),
        ], 201);
    }
    public function update(SettingUpdateRequest $request)
{
    // Get the setting ID from the request body
    $id = $request->input('id');

    // Retrieve the existing setting
    $setting = Setting::findOrFail($id);

    // Update the setting with validated data
    $setting->update($request->validated());

    // Return a success response with the updated setting
    return response()->json([
        'message' => 'Setting updated successfully!',
        'data' => new SettingResource($setting),
    ], 200);
}


    public function get_all()
    {
        $setting=Setting::all();
        return SettingResource::collection($setting);
    }

    public function deleteSetting(Request $request)
    {
        try{
        $id = $request->input('id');

        $setting = Setting::findOrFail($id);
        $setting->delete();

        return response()->json(['message' => 'Setting deleted successfully',
        'data'=>new SettingResource($setting),

         200]);
        }

        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Setting not found'], 404);
        }
    }
}

