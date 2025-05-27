<?php

use App\Models\LightingTowerAsset as LightingTowerAssetAlias;
use App\Models\LightVehicleAsset;
use App\Models\TruckAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('get-all-assets', function () {
    $assets = collect()
        ->merge(LightVehicleAsset::pluck('asset_no'))
        ->merge(LightingTowerAssetAlias::pluck('asset_no'))
        ->merge(TruckAsset::pluck('asset_no'));

    return response()->json([
        'status' => 'success',
        'data' => $assets
    ]);
});
