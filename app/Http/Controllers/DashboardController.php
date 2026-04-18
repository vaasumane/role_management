<?php

namespace App\Http\Controllers;

use App\Models\UserMapping;
use App\Models\VoterDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        
        $userMappingIds = UserMapping::where('user_id', session('user')->id)->select('part_id', 'acid')->orderBy('id', 'desc')->first();

        $totalVoter = VoterDetails::where('part_id', $userMappingIds->part_id)->where('acid', $userMappingIds->acid)->count();
        $VisitedVoter = VoterDetails::where('part_id', $userMappingIds->part_id)->where('acid', $userMappingIds->acid)->where('visited_status', '1')->count();
        $NotVisitedVoter = VoterDetails::where('part_id', $userMappingIds->part_id)->where('acid', $userMappingIds->acid)->where('visited_status', '0')->count();

        $MappedVoter = VoterDetails::where('part_id', $userMappingIds->part_id)->where('acid', $userMappingIds->acid)->where('status', '1')->count();
        $NonMappedVoter = VoterDetails::where('part_id', $userMappingIds->part_id)->where('acid', $userMappingIds->acid)->where('status', '6')->count();

        $FamilyVoter = VoterDetails::where('part_id', $userMappingIds->part_id)->where('acid', $userMappingIds->acid)->whereNotNull('family_id')->count();



        return view('dashboard', compact('totalVoter','VisitedVoter','NotVisitedVoter','MappedVoter','NonMappedVoter','FamilyVoter','userMappingIds'));
    }
}
