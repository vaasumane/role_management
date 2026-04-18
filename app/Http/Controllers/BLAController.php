<?php

namespace App\Http\Controllers;

use App\Models\FamilyMaster;
use App\Models\FamilyVisited;
use App\Models\Parts;
use App\Models\UserMapping;
use App\Models\VoterDetails;
use App\Models\VoterVisited;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BLAController extends Controller
{
    public function voterlist()
    {
        $userId = session('user')->id;
        $userPartsIds = UserMapping::where('user_id', $userId)->pluck('part_id')->toArray();
        $userMappingIds = UserMapping::where('user_id', $userId)->with(['assembly','parts'])->select('part_id', 'acid')->orderBy('id', 'desc')->first();

        // $userParts = Parts::whereIn('parts.id', $userPartsIds)
        //     ->join('assembly_constituencies', 'assembly_constituencies.id', '=', 'parts.ac_id')
        //     ->select(
        //         'parts.id as part_id',
        //         'parts.booth_address',
        //         'assembly_constituencies.id as assembly_id',
        //         'assembly_constituencies.name as assembly_name'
        //     )
        //     ->get();
        // $userPartsGrouped = $userParts->groupBy('part_id');

        $userPartsGrouped = array();
        $userParts = array();
        $statuses = DB::table('statuses')->get();
        $colors = DB::table('colors')->get();
        $religions = DB::table('religions')->get();
        $castes = DB::table('castes')->get();
        $occupations = DB::table('occupations')->get();
        $educations = DB::table('educations')->get();
        $languages = DB::table('languages')->get();
        return view('voter_list', compact('userParts', 'userPartsGrouped', 'statuses', 'colors', 'religions', 'castes', 'occupations', 'educations', 'languages', 'userMappingIds'));
    }
    public function getVoters(Request $request)
    {
        $query = DB::table('voter_details')
            ->select('voter_details.id', 'voter_details.applicant_full_name', 'voter_details.age', 'voter_details.gender', 'voter_details.epic_number', 'voter_details.realtion_full_name', DB::raw("
            CASE 
                WHEN voter_details.relation = 'FTHR' THEN 'Father'
                WHEN voter_details.relation = 'HSBN' THEN 'Husband'
                WHEN voter_details.relation = 'MTHR' THEN 'Mother'
                WHEN voter_details.relation = 'WIFE' THEN 'Wife'
                WHEN voter_details.relation = 'OTHR' THEN 'Other'
                ELSE voter_details.relation
            END as relation_name
        "));

        // Filter
        if ($request->part_id) {
            $query->where('part_id', $request->part_id);
        }

        if ($request->ac_id) {
            $query->where('acid', $request->ac_id);
        }

        if ($request->search_value) {
            $query->where(function ($q) use ($request) {
                $q->where('applicant_full_name', 'like', '%' . $request->search_value . '%')
                    ->orWhere('epic_number', 'like', '%' . $request->search_value . '%');
            });
        }

        $total = $query->count();

        $data = $query
            ->offset($request->start)
            ->limit($request->length)
            ->get();

        return response()->json([
            "draw" => intval($request->draw),
            "recordsTotal" => $total,
            "recordsFiltered" => $total,
            "data" => $data
        ]);
    }
    public function getVoterDetails(Request $request)
    {
        $VoterData = DB::table('voter_details as v')
            ->leftJoin('voter_visited as vv', function ($join) {
                $join->on('vv.voter_id', '=', 'v.id')
                    ->whereRaw('vv.id = (SELECT MAX(id) FROM voter_visited WHERE voter_id = v.id)');
            })
            ->leftJoin('family_visited as fv', function ($join) {
                $join->on('fv.family_id', '=', 'v.family_id')
                    ->whereRaw('fv.id = (SELECT MAX(id) FROM family_visited WHERE family_id = v.family_id)');
            })
            ->where('v.id', $request->voter_id)
            ->select(
                'v.id',
                'v.applicant_full_name',
                'v.mobile',
                'v.age',
                'v.dob',
                'v.visited_status',
                'v.status',
                'v.color',
                'v.religions',
                'v.occupations',
                'v.castes',
                'v.educations',
                'v.languages',
                'v.status1',
                'v.status2',
                'v.status3',
                'v.status4',

                DB::raw("
            CASE 
                WHEN v.visited_flg = 1 THEN fv.visited_date 
                ELSE vv.visited_date 
            END as visited_date
        "),
                DB::raw("
            CASE 
                WHEN v.visited_flg = 1 THEN fv.visited_time 
                ELSE vv.visited_time 
            END as visited_time
        "),
                DB::raw("
            CASE 
                WHEN v.visited_flg = 1 THEN fv.visited_location 
                ELSE vv.visited_location 
            END as visited_location
        ")
            )
            ->first();
        if (!empty($VoterData)) {
            $response['status'] = true;
            $response['data'] = $VoterData;
        } else {
            $response['status'] = true;
            $response['data'] = $VoterData;
        }
        return response()->json($response);
    }
    public function updateVoter(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'age' => 'required|integer|min:1|max:120',
                'mobile' => 'required|digits:10',
                'dob' => 'required|date',
                'visited_date' => 'date',
                'status' => 'required',
                'color' => 'required',
                'religions' => 'required',
                'occupations' => 'required',
                'educations' => 'required',
                'languages' => 'required',
            ]);

            $updated = VoterDetails::where('id', $request->edit_id)->update([
                'dob' => $request->dob,
                'visited_status' => $request->visited_status,
                'visited_flg' => '2',
                'status' => $request->status,
                'color' => $request->color,
                'religions' => $request->religions,
                'castes' => $request->castes,
                'occupations' => $request->occupations,
                'educations' => $request->educations,
                'languages' => $request->languages,
                'mobile' => $request->mobile,
                'status1' => $request->status1,
                'status2' => $request->status2,
                'status3' => $request->status3,
                'status4' => $request->status4,
                'updated_at' => now(),
                'updated_by' => session('user')->id
            ]);

            if (isset($request->visited_date) || isset($request->visited_time)) {
                $VoterVisited = new VoterVisited();
                $VoterVisited->voter_id = $request->edit_id;
                $VoterVisited->visited_date = $request->visited_date;
                $VoterVisited->visited_time = $request->visited_time;
                $VoterVisited->visited_location = $request->visited_location;
                $VoterVisited->created_by = session('user')->id;
                $VoterVisited->save();
                $updated = true;
            }
            DB::commit();
            return response()->json([
                'status' => $updated ? true : false
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            Log::info('Update Voter :' . $e->getMessage() . ' on File : ' . $e->getFile() . ' Line no ' . $e->getLine());

            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::info('Update Voter :' . $e->getMessage() . ' on File : ' . $e->getFile() . ' Line no ' . $e->getLine());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }
    public function StoreFamily(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'mobile' => 'required|digits:10',
                'religions' => 'required',
            ]);
            $last = DB::table('family_master')
                ->select(DB::raw("MAX(CAST(SUBSTRING(family_no, 3) AS UNSIGNED)) as max_no"))
                ->value('max_no');

            $next = $last ? $last + 1 : 1;

            $houseNo = 'HN' . str_pad($next, 5, '0', STR_PAD_LEFT);
            $FamilyMaster = new FamilyMaster();
            $FamilyMaster->family_no  = $houseNo;
            $FamilyMaster->created_by = session('user')->id;
            $FamilyMaster->save();

            $VoterArr = $request->voters;
            if ($FamilyMaster) {
                $updateVoter = VoterDetails::whereIn('id', $VoterArr)->update(['family_id' => $FamilyMaster->id, 'visited_flg' => '1', 'mobile' => $request->mobile, 'religions' => $request->religions]);
                if (isset($request->visited_date) || isset($request->visited_time)) {
                    $FamilyVisited = new FamilyVisited();
                    $FamilyVisited->family_id = $FamilyMaster->id;
                    $FamilyVisited->visited_date = $request->visited_date;
                    $FamilyVisited->visited_time = $request->visited_time;
                    $FamilyVisited->visited_location = $request->visited_location;
                    $FamilyVisited->created_by = session('user')->id;
                    $FamilyVisited->save();
                    $updated = true;
                }
            }
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Family Created',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('Update Voter :' . $e->getMessage() . ' on File : ' . $e->getFile() . ' Line no ' . $e->getLine());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage() // remove in production
            ], 500);
        }
    }
}
