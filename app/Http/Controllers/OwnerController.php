<?php

namespace App\Http\Controllers;

use App\Models\Branch; 
use Illuminate\Http\Request;

class OwnerController extends Controller
{

    public function viewOwnerDashboard(){
        $totalBranches = $this->totalBranches();
        return view('Owner.Dashboard')->with(['totalBranches'=>$totalBranches]);
    }

    public function addNewBranchIndex(){
        return view('Owner.AddNewBranch');
    }


    public function viewBranchesDashboard(){
        $branchData = Branch::all();
        $totalBranches = $this->totalBranches();
        return view('Owner.BranchesDashboard')->with(['branchData'=>$branchData, 'totalBranches'=>$totalBranches]);
    }

    public function newBranch(Request $req)
    {
        $validatedData = $req->validate([
            'branchArea' => 'required|string|max:255',
            'branchname' => 'required|string|max:255',
            'branchcode' => 'required|string|max:50|unique:branches',
            'address' => 'required|string|max:255'
        ]);
        
        $riderOption = $req->has('riderOption') ? true : false;
        $onlineDeliveryOption = $req->has('onlineDeliveryOption') ? true : false;
        $diningTableOption = $req->has('diningTableOption') ? true : false;
        
        $newBranch = new Branch();
        $newBranch->branchLocation = $validatedData['branchArea'];
        $newBranch->branchName = $validatedData['branchname'];
        $newBranch->branchCode = $validatedData['branchcode'];
        $newBranch->address = $validatedData['address'];
    
        $newBranch->riderOption = $riderOption;
        $newBranch->onlineDeliveryOption = $onlineDeliveryOption;
        $newBranch->diningTableOption = $diningTableOption;

        $newBranch->save();

        return redirect()->route('dashboard');
    }

    public function viewAddStaff(){
        $branches = Branch::all();
        return view('Owner.MyStaff')->with(['branches'=>$branches]); 
    }

    public function newuser(Request $req)
    {
        dd($req->all());
        $validatedData = $req->validate([
            'branchArea' => 'required|string|max:255',
            'branchname' => 'required|string|max:255',
            'branchcode' => 'required|string|max:50|unique:branches',
            'address' => 'required|string|max:255'
        ]);
        
        $riderOption = $req->has('riderOption') ? true : false;
        $onlineDeliveryOption = $req->has('onlineDeliveryOption') ? true : false;
        $diningTableOption = $req->has('diningTableOption') ? true : false;
        
        $newBranch = new Branch();
        $newBranch->branchLocation = $validatedData['branchArea'];
        $newBranch->branchName = $validatedData['branchname'];
        $newBranch->branchCode = $validatedData['branchcode'];
        $newBranch->address = $validatedData['address'];
    
        $newBranch->riderOption = $riderOption;
        $newBranch->onlineDeliveryOption = $onlineDeliveryOption;
        $newBranch->diningTableOption = $diningTableOption;

        $newBranch->save();

        return redirect()->route('dashboard');
    }

    public function totalBranches(){
        $totalBranches = Branch::count();
        session(['totalBranches'=>$totalBranches]);   
        return $totalBranches;
    }
}
