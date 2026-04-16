<?php

namespace App\Http\Controllers;

use App\Models\VoterDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $taotalPart = DB::table('parts')->count();
        $taotalAssemble = DB::table('assembly_constituencies')->count();
        $assignedAssem = DB::table('assembly_constituencies')
            ->whereIn('id', function ($query) {
                $query->select('acid')->from('user_part_assembly_mapping');
            })
            ->count();
        $unassignedAssem = DB::table('assembly_constituencies as ac')
            ->leftJoin('user_part_assembly_mapping as um', 'ac.id', '=', 'um.acid')
            ->whereNull('um.acid')
            ->count();

        $assignedPart = DB::table('parts')
            ->whereIn('id', function ($query) {
                $query->select('part_id')->from('user_part_assembly_mapping');
            })
            ->count();
        $unassignedPart = DB::table('parts as p')
            ->leftJoin('user_part_assembly_mapping as um', 'p.id', '=', 'um.part_id')
            ->whereNull('um.part_id')
            ->count();
        $totalVoter = VoterDetails::count();
        $VisitedVoter = VoterDetails::where('visited_status','1')->count();
        $NotVisitedVoter = VoterDetails::where('visited_status','0')->count();
        

        return view('dashboard', compact('taotalPart', 'assignedPart', 'unassignedPart', 'taotalAssemble', 'assignedAssem', 'unassignedAssem','totalVoter','VisitedVoter','NotVisitedVoter'));
    }
}
