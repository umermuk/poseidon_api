<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SteepRoof\StoreRequest;
use App\Http\Requests\SteepRoof\UpdateRequest;
use App\Http\Resources\SteepRoof\AllSteepRoofResource;
use App\Models\SteepRoof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class SteepRoofController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = SteepRoof::query();
            if (!empty($request->skip)) $query->skip($request->skip);
            if (!empty($request->take)) $query->take($request->take);
            $steep_roof = $query->orderBy('id', 'DESC')->get();
            return response()->json([
                'status' => true,
                'message' => ($steep_roof->count()) . " steep roof(s) found",
                'data' => AllSteepRoofResource::collection($steep_roof),
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
     * @param  \App\Http\Requests\SteepRoof\StoreRequest  $request
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
                $image->storeAs('steep_roof', $filename, "public");
                $inputs['image'] = "steep_roof/" . $filename;
            }
            $steep_roof = SteepRoof::create($inputs);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Steep Roof has been successfully added",
                'steep_roof' => new AllSteepRoofResource($steep_roof),
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
     * @param  \App\Models\SteepRoof $steep_roof
     */
    public function show(SteepRoof $steep_roof)
    {
        if (empty($steep_roof)) {
            return response()->json([
                'status' => false,
                'message' => "Steep Roof not found",
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Steep Roof has been successfully found",
            'steep_roof' => new AllSteepRoofResource($steep_roof),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Http\Requests\SteepRoof\UpdateRequest  $request
     * @param  \App\Models\SteepRoof $steep_roof
     */
    public function update(UpdateRequest $request, SteepRoof $steep_roof)
    {
        if (empty($steep_roof)) {
            return response()->json([
                'status' => false,
                'message' => "Steep Roof not found",
            ], 404);
        }

        try {
            DB::beginTransaction();
            $steep_roof->update($request->validated());
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Steep Roof has been successfully updated",
                'steep_roof' => new AllSteepRoofResource($steep_roof),
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
     * @param  \App\Models\SteepRoof $steep_roof
     */
    public function destroy(SteepRoof $steep_roof)
    {
        if (empty($steep_roof)) {
            return response()->json([
                'status' => false,
                'message' => "Steep Roof not found",
            ], 404);
        }

        try {
            DB::beginTransaction();
            $steep_roof->delete();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Steep Roof has been successfully deleted",
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
