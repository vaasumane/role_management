<?php

namespace App\Http\Controllers;

use App\Models\Parts;
use App\Models\UserMapping;
use App\Models\VoterDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BLAController extends Controller
{
    public function voterlist()
    {
        $userId = session('user')->id;
        $userPartsIds = UserMapping::where('user_id', $userId)->pluck('part_id')->toArray();
        // $userParts= Parts::whereIn('id',$userPartsIds)->get();
        $userParts = Parts::whereIn('parts.id', $userPartsIds)
            ->join('assembly_constituencies', 'assembly_constituencies.id', '=', 'parts.ac_id')
            ->select(
                'parts.id as part_id',
                'parts.booth_address',
                'assembly_constituencies.id as assembly_id',
                'assembly_constituencies.name as assembly_name'
            )
            ->get();
        $userPartsGrouped = $userParts->groupBy('part_id');
    
        $statuses = DB::table('statuses')->get();
        $colors = DB::table('colors')->get();
        $religions = DB::table('religions')->get();
        $castes = DB::table('castes')->get();
        $occupations = DB::table('occupations')->get();
        $educations = DB::table('educations')->get();
        $languages = DB::table('languages')->get();
        return view('voter_list', compact('userParts', 'userPartsGrouped','statuses','colors','religions','castes','occupations','educations','languages'));
    }
    public function getVoters(Request $request)
    {
        $query = DB::table('voter_details')
            ->select('voter_details.id', 'voter_details.applicant_full_name', 'voter_details.age', 'voter_details.gender', 'voter_details.epic_number', 'parts.booth_address', 'assembly_constituencies.name as assem_name')->join('assembly_constituencies', 'assembly_constituencies.id', '=', 'voter_details.acid')->join('parts', 'parts.id', '=', 'voter_details.part_id');

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
        $VoterData = DB::table('voter_details')
            ->select('voter_details.*', 'parts.booth_address', 'assembly_constituencies.name as assem_name')->join('assembly_constituencies', 'assembly_constituencies.id', '=', 'voter_details.acid')->join('parts', 'parts.id', '=', 'voter_details.part_id')->where('voter_details.id', $request->voter_id)->first();
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
        $request->validate([
            'applicant_full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:M,F,O',
            'epic_number' => 'required|string|max:20',
            'part_id' => 'required|integer',
            'acid' => 'required|integer',
            'mobile' => 'required|string|max:20',
        ]);

        $updated = VoterDetails::where('id', $request->edit_id)->update([
            'applicant_full_name' => $request->applicant_full_name,
            'applicant_first_name' => $request->applicant_first_name,
            'applicant_last_name' => $request->applicant_last_name,
            'age' => $request->age,
            'gender' => $request->gender,
            'relation' => $request->relation,
            'part_id' => $request->part_id,
            'acid' => $request->acid,
            'realtion_full_name' => $request->realtion_full_name,
            'realtion_last_name' => $request->realtion_last_name,
            'epic_number' => $request->epic_number,
            'booth_address' => $request->booth_address,
            'v_address' => $request->v_address,
            'visited_status' => $request->visited_status,
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
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => $updated ? true : false
        ]);
    }
}
