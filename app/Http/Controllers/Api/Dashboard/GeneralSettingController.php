<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Interfaces\SettingInterface;
use App\Models\GeneralSetting;
use App\Providers\GeneralSettingProvider;
use App\Services\SettingService;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $settings = app(SettingInterface::class);
        $settings = $settings->all();

        return response()->json($settings);
    }

    public function updateSettings(Request $request , SettingService $setting)
    {
        $request->validate([
            'minimum_fare_to_ride'          =>'sometimes|required|numeric|min:0',
            'profit_type'                   => 'sometimes|required|in:fixed,percentage',
            'profit_value'                  => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $type = $request->input('profit_type');
                    if ($type === 'percentage') {
                        if ($value > 100) {
                            $fail(__('The :attribute must be between 0 and 100 when type is percentage.'));
                        }
                    }
                }
            ],
        ]);

        $setting->clear();

        GeneralSetting::updateOrCreate(
            ['setting_key' => 'minimum_fare_to_ride'],
            ['setting_value' => $request->minimum_fare_to_ride]
        );

        GeneralSetting::updateOrCreate(
            ['setting_key' => 'profit_type'],
            ['setting_value' => $request->profit_type]
        );

        GeneralSetting::updateOrCreate(
            ['setting_key' => 'profit_value'],
            ['setting_value' => $request->profit_value]
        );

        return response()->json([
            'message'               =>__('Set Successfully'),
        ]);

    }
}
