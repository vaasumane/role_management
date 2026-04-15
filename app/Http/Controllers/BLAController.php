<?php

namespace App\Http\Controllers;

use App\Models\Parts;
use App\Models\UserMapping;
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
        return view('voter_list', compact('userParts', 'userPartsGrouped'));
    }
    public function getVoters(Request $request)
    {
        $query = DB::table('voter_details')
            ->select('voter_details.id', 'voter_details.applicant_full_name', 'voter_details.age', 'voter_details.gender', 'voter_details.epic_number', 'parts.booth_address', 'assembly_constituencies.name as assem_name')->join('assembly_constituencies', 'assembly_constituencies.id', '=', 'voter_details.acid')->join('parts', 'parts.id', '=', 'voter_details.part_id');

        if ($request->search) {
            $query->where('voter_details.applicant_full_name', 'like', '%' . $request->search . '%');
        }
        if ($request->part_id != "") {
            $query->where('voter_details.part_id',$request->part_id);
        }
        if ($request->acid != "") {
            $query->where('voter_details.acid',$request->acid);
        }

        return response()->json(
            $query->paginate(9)
        );
    }
}
