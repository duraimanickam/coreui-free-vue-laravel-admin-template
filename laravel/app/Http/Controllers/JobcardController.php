<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Facility;
use App\Models\Zone;
use App\Models\Building;
use App\Models\Company;
use App\Models\Floor;
use App\Models\User;
use App\Models\Jobcard;
use App\Models\JobcardLog;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\JobcardResource;

class JobcardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jobcards = Jobcard::get();
        return response()->json(JobcardResource::collection( $jobcards) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'zones' => Zone::select('zones.name as label', 'zones.id as value')->get(),
            'users' => User::select('users.name as label', 'users.id as value')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'start_date'             => 'required',
            'zone_id'          => 'required',
        ]);
        $user = auth()->userOrFail();
        Jobcard::create($request->all());
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jobcard = Jobcard::find($id);

        return response()->json( new JobcardResource($jobcard) );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $facility = DB::table('facilities')->where('id', '=', $id)->first();
        $zoneList = Zone::select('zones.name as label', 'zones.id as value')->get();
        return response()->json( [ 'facility' => $facility, 'zonelist' =>  $zoneList ] );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //var_dump('bazinga');
        //die();
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:64'
        ]);
        $zone = Facility::find($id);
        $zone->name     = $request->input('name');
        $zone->save();
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zone = Facility::find($id);
        if($zone){
            $zone->delete();
        }
        return response()->json( ['status' => 'success'] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function facilities(Request $request)
    {
        $zone_id = $request->zone_id;
        //Log::info('Getting Facilities for the zone.', ['id' => $zone_id]);
        return response()->json([
            'facilities' => Facility::select('facilities.name as label', 'facilities.id as value')->where('zone_id', $zone_id)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function buildings(Request $request)
    {
        $facility_id = $request->facility_id;
        //Log::info('Getting Facilities for the zone.', ['id' => $zone_id]);
        return response()->json([
            'buildings' => Building::select('buildings.name as label', 'buildings.id as value')->where('facility_id', $facility_id)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function companies(Request $request)
    {
        $building_id = $request->building_id;
        //Log::info('Getting Companies for the building.', ['id' => $building_id]);
        return response()->json([
            'companies' => Company::select('companies.name as label', 'companies.id as value')->where('building_id', $building_id)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function floors(Request $request)
    {
        $company_id = $request->company_id;
        //Log::info('Getting Companies for the building.', ['id' => $building_id]);
        return response()->json([
            'floors' => Floor::select('floors.name as label', 'floors.id as value')->where('company_id', $company_id)->get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUpdates(Request $request)
    {
        $validatedData = $request->validate([
            'jobcard_id'             => 'required',

        ]);
        $user = auth()->userOrFail();
        $updates = json_decode($request->updates);
        Log::info('Updates:', $updates);
        foreach($updates as $update){
            JobcardLog::create(['user_id'=>$user->id, 'jobcard_id'=>$request->jobcard_id, 'description'=>$update->description]);
        }

        return response()->json( ['status' => 'success'] );
    }

}
