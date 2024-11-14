<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Http\Requests\SettingStoreRequest;

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

