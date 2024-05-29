<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roof\StoreRequest;
use App\Http\Requests\Roof\UpdateRequest;
use App\Http\Resources\Roof\AllRoofResource;
use App\Models\Roof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RoofController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Roof::query();
            if (!empty($request->skip)) $query->skip($request->skip);
            if (!empty($request->take)) $query->take($request->take);
            $roof = $query->orderBy('id', 'DESC')->get();
            return response()->json([
                'status' => true,
                'message' => ($roof->count()) . " roof(s) found",
                'data' => AllRoofResource::collection($roof),
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
     * @param  \App\Http\Requests\Roof\StoreRequest  $request
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
                $image->storeAs('roof', $filename, "public");
                $inputs['image'] = "roof/" . $filename;
            }
            $roof = Roof::create($inputs);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Roof has been successfully added",
                'roof' => new AllRoofResource($roof),
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
     * @param  \App\Models\Roof $roof
     */
    public function show(Roof $roof)
    {
        if (empty($roof)) {
            return response()->json([
                'status' => false,
                'message' => "Roof not found",
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Roof has been successfully found",
            'roof' => new AllRoofResource($roof),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  \App\Http\Requests\Roof\UpdateRequest  $request
     * @param  \App\Models\Roof $roof
     */
    public function update(UpdateRequest $request, Roof $roof)
    {
        if (empty($roof)) {
            return response()->json([
                'status' => false,
                'message' => "Roof not found",
            ], 404);
        }

        try {
            DB::beginTransaction();
            $roof->update($request->validated());
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Roof has been successfully updated",
                'roof' => new AllRoofResource($roof),
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
     * @param  \App\Models\Roof $roof
     */
    public function destroy(Roof $roof)
    {
        if (empty($roof)) {
            return response()->json([
                'status' => false,
                'message' => "Roof not found",
            ], 404);
        }

        try {
            DB::beginTransaction();
            $roof->delete();
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Roof has been successfully deleted",
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
