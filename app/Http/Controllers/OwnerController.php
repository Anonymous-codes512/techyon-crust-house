<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

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
        $staff = User::with('branch')->get();
        return view('Owner.MyStaff')->with(['branches'=>$branches, 'Staff'=>$staff]); 
    }

    public function updateStaffData(Request $req){

        $auth = User::find($req->input('staffId'));
        if ($req->hasFile('updated_profile_picture')) {
            $imageName = null;
            $existingImagePath = public_path('Images/UsersImages') . '/' . $auth->profile_picture;
            File::delete($existingImagePath);

            $image = $req->file('updated_profile_picture');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('Images/UsersImages'), $imageName);
            $auth->profile_picture = $imageName;
        }

        $auth->name = $req->input('name');
        $auth->email = $req->input('email');
        $auth->role = $req->input('role');
        $auth->branch_id = $req->input('branch');

        if ($req->filled('password')) {
            $auth->password = Hash::make($req->input('password'));
        }

        $auth->save();

        return redirect()->back();
    }

    public function deleteStaffData($id)
    {
        $staff = User::find($id);
        $staff->delete();
        return redirect()->back();
    }

    public function totalBranches(){
        $totalBranches = Branch::count();
        session(['totalBranches'=>$totalBranches]);   
        return $totalBranches;
    }
}
