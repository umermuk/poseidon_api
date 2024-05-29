<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Estimate\StoreRequest;
use App\Http\Resources\Estimate\AllEstimateResource;
use App\Http\Resources\Roof\AllRoofResource;
use App\Models\BuildingType;
use App\Models\Estimate;
use App\Models\Roof;
use App\Models\SteepRoof;
use App\Models\User;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Estimate::with(['building_type', 'steep_roof', 'currently_roof', 'installed_roof']);
            if (!empty($request->type))
                $query->where('type', $request->type);
            if (!empty($request->skip))
                $query->skip($request->skip);
            if (!empty($request->take))
                $query->take($request->take);
            $estimate = $query->orderBy('id', 'DESC')->get();
            return response()->json([
                'status' => true,
                'message' => ($estimate->count()) . " estimate(s) found",
                'data' => AllEstimateResource::collection($estimate),
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
     * @param  \App\Http\Requests\Estimate\StoreRequest  $request
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            Estimate::create($request->validated());
            $allroof = Roof::all();
            $user = User::find(1);
            foreach ($allroof as $value) {
                $value->extra = (object) ['size' => $request->roof_size];
            }
            $building_type = BuildingType::find($request->building_type_id);
            $steep_roof = SteepRoof::find($request->steep_roof_id);
            $currently_roof = Roof::find($request->currently_roof_id);
            $installed_roof = Roof::find($request->installed_roof_id);
            if ($request->type == 'proposal') {
                if (empty($request->proposal_roof_id))
                    throw new Error('Proposal Roof is Required');
                $proposal_roof = Roof::find($request->proposal_roof_id);
                if (empty($proposal_roof))
                    throw new Error('Proposal Roof not found.');
            }
            $email = $request->email;
            $file = public_path('storage/' . $installed_roof->image);
            if ($request->type == 'proposal')
                $file1 = public_path('storage/' . $proposal_roof->image);
            $data = [
                'building_type' => $building_type->name ?? '',
                'steep_roof' => $steep_roof->name ?? '',
                'currently_roof' => $currently_roof->name ?? '',
                'installed_roof' => $installed_roof->name ?? '',
                'proposal_roof' => ($request->type == 'proposal') ? $proposal_roof->name : '',
                'when_start' => $request->when_start ?? '',
                'interested_financing' => $request->interested_financing ?? '',
                'address' => $request->address ?? '',
                'roof_size' => $request->roof_size ?? '',
                'installed_roof_price' => ($installed_roof->price) * ((int) $request->roof_size) ?? '',
                'proposal_roof_price' => ($request->type == 'proposal') ? ($proposal_roof->price) * ((int) $request->roof_size) : '',
                'installed_roof_desc' => $installed_roof->desc ?? '',
                'proposal_roof_desc' => ($request->type == 'proposal') ? $proposal_roof->desc : '',
                'about' => $request->about ?? '',
                'name' => $request->name ?? '',
                'email' => $request->email ?? '',
                'phone' => $request->phone ?? '',
                'type' => $request->type ?? '',
            ];
            if ($request->type == 'estimate') {
                Mail::send('mail.estimate', ['data' => $data], function ($message) use ($email, $file) {
                    $message->to($email);
                    $message->subject('Estimate');
                    if (!empty($file)) {
                        $message->attach($file);
                    }
                    $message->priority(3);
                });
            } else {
                Mail::send('mail.estimate', ['data' => $data], function ($message) use ($user, $file1) {
                    $message->to($user->email);
                    $message->subject('Proposal');
                    if (!empty($file1)) {
                        $message->attach($file1);
                    }
                    $message->priority(3);
                });
            }
            $to = ($request->type == 'proposal') ? $user->name : $request->email;
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "We have sent an email with these estimates to " . $to . " Someone from our team will follow up within the next business day to discuss your project further.",
                'roof' => AllRoofResource::collection($allroof),
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
     * @param  \App\Models\Estimate $estimate
     */
    public function show(Estimate $estimate)
    {
        if (empty($estimate)) {
            return response()->json([
                'status' => false,
                'message' => "Estimate not found",
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "Estimate has been successfully found",
            'estimate' => new AllEstimateResource($estimate),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estimate $estimate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estimate $estimate)
    {
        //
    }
}
