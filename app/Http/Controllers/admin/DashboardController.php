<?php

namespace App\Http\Controllers\admin;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Helper, AdminHelper, Image, Auth, Hash, Redirect, Validator, View;
use Illuminate\Support\Facades\File as FileSystem;

use App\Models\User;
use App\Models\Project;
use App\Models\Gallery;
use App\Models\CampainStage;
use App\Models\CampainType;
use App\Models\Domain;
use App\Models\DoubleDonation;
use App\Models\Payment;

use Config;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public $data = array();             // set global class object

    /*****************************************************/
    # DashboardController
    # Function name : dashboardView
    # Author        :
    # Created Date  : 03-09-2021
    # Purpose       : Dashboard View
    #                 
    #                 
    # Params        : 
    /*****************************************************/

    public function dashboardView()
    {
        return Redirect::Route('admin.order-management.order-list');
        
  //       $this->data['page_title'] = 'CS CART | Dashboard';
  //       $this->data['panel_title'] = 'Admin Dashboard';

		// $this->data['total_user'] = User::where(function($query)
  //                                   {
  //                                       $query->where('user_type', '!=', '0');
                                        
  //                                   })->count();
		// $this->data['active_user'] = User::where(function($query)
  //                                   {
  //                                       $query->where('user_type', '!=', '0');
                                        
  //                                   })->where('account_status','1')->count();
		// $this->data['inactive_user'] = User::where(function($query)
  //                                   {
  //                                       $query->where('user_type', '!=', '0');
                                        
  //                                   })->where('account_status','0')->count();

  //       $this->data['total_project'] = Project::count();

  //       $this->data['total_pending_project'] = Project::where(function($query)
  //                                         {
  //                                               $query->where('project_status', 'pending');
               
                                                
  //                                         })->count();
  //       $this->data['total_active_project'] = Project::where(function($query)
  //                                         {
  //                                               $query->where('project_status', 'active');
               
                                                
  //                                         })->count();

  //       // $this->data['total_user'] = Member::count();
  //       // $this->data['active_user'] = Member::where('member_status','1')->count();
  //       // $this->data['inactive_user'] = Member::where('member_status','0')->count();
       
                                              

  //       return view('admin.dashboard.index', $this->data);
  //       // echo 'hello';
    }

    

    /*****************************************************/
    # DashboardController
    # Function name : showChangePasswordForm
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Show Change Password Form
    #                 
    #                 
    # Params        : 
    /*****************************************************/

    public function showChangePasswordForm()
    {
        $this->data['page_title'] = 'Change Password';
        $this->data['panel_title'] = 'Change Password';
        

        return view('admin.dashboard.changepassword', $this->data);
    }

    /*****************************************************/
    # DashboardController
    # Function name : changePassword
    # Author        :
    # Created Date  : 20-07-2020
    # Purpose       : Change Password
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/


    public function changePassword(Request $request)
    {
		$thisuser = User::where('id',Auth::id())->first();
        if (md5($request->get('current_password')) != $thisuser->password) {
            // The passwords matches
            return redirect()->back()->with("error", "Your current password does not match the password you provided. Please try again.");
        } else {
            try {

                $validationCondition = [
                    'new_password' => 'required',
                    'confirm_password' => 'required|same:new_password',
                ];

                $validationMessages = array(
                    'new_password.required' => 'A new password is required.',
                    'confirm_password.required' => 'Confirm password is required.',
                    'confirm_password.same' => 'Confirm password must be the same as the new password.',
                );

                $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);
                if ($Validator->fails()) {
                    // If validation error occurs, load the error listing
                    return redirect()->back()->withErrors($Validator);
                } else {
                    $user = User::findOrFail(Auth::id());
                    $user->password = md5($request->new_password);
                    $saveResposne = $user->save();
                    if ($saveResposne == true) {
                        return redirect()->back()->with("success", "The password has been successfully changed!");
                    } else {
                        return redirect()->back()->with("error", "The password has been successfully changed!");
                    }
                }

            } catch (Exception $e) {
                return Redirect::Route('admin.changePassword')->with('error', $e->getMessage());
            }

        }
    }





}
