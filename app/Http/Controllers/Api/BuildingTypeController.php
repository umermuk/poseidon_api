<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuildingType\StoreRequest;
use App\Http\Requests\BuildingType\UpdateRequest;
use App\Http\Resources\BuildingType\AllBuildingTypeResource;
use App\Models\BuildingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BuildingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = BuildingType::query();
            if (!empty($request->skip)) $query->skip($request->skip);
            if (!empty($request->take)) $query->take($request->take);
            $building_type = $query->orderBy('id', 'DESC')->get();
            return response()->json([
                'status' => true,
                'message' => ($building_type->count()) . " building type(s) found",
                'data' => AllBuildingTypeResource::collection($building_type),
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Http\Requests\BuildingType\StoreRequest  $request
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->except(
                'image',
            );
            if (!empty($request->image)) {
                $image = $request->image;
                $filename = "Image-" . time() . "-" . rand() . "." . $image->getClientOriginalExtension();
                $image->storeAs('building_type', $filename, "public");
                $inputs['image'] = "building_type/" . $filename;
            }
            $building_type = BuildingType::create($inputs);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Building Type has been successfully added",
                'building_type' => new AllBuildingTypeResource($building_type),
            ]);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * @param  \App\Models\BuildingType $building_type
     */
    public function show(BuildingType $building_type)
    {
        if (empty($building_type)) {
            return response()->json([
                'status' => false,
                'message' => "Building Type not found",
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Building Type has been successfully found",
            'building_type' => new AllBuildingTypeResource($building_type),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Http\Requests\BuildingType\UpdateRequest  $request
     * @param  \App\Models\BuildingType $building_type
     */
    public function update(UpdateRequest $request, BuildingType $building_type)
    {
        if (empty($building_type)) {
            return response()->json([
                'status' => false,
                'message' => "Building Type not found",
            ], 404);
        }

        try {
            DB::beginTransaction();
            $building_type->update($request->validated());
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Building Type has been successfully updated",
                'building_type' => new AllBuildingTypeResource($building_type),
            ]);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Models\BuildingType $building_type
     */
    public function destroy(BuildingType $building_type)
    {
        if (empty($building_type)) {
            return response()->json([
                'status' => false,
                'message' => "Building Type not found",
            ], 404);
        }

        try {
            DB::beginTransaction();
            $building_type->delete();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Building Type has been successfully deleted",
            ]);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
