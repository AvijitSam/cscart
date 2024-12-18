<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Models\User;
use App\Models\Project;
use App\Models\Gallery;
use App\Models\CampainStage;
use App\Models\CampainType;
use App\Models\Domain;
use App\Models\DoubleDonation;
use App\Models\Payment;
use App\Models\Donation;


use Illuminate\Support\Facades\Hash;
use Auth;
use Redirect;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Config;
use DateTime;
use DB;
use Mail;
use Helper, Session, File, Image, Validator, View;



class UserController extends Controller
{
    public function check_file_exist($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
     
        if ($code == 200) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close($ch);
        return $status;
    }
    

    /************************use*****************************/
    # UserController
    # Function name : SiteuserCustomerList
    # Author        :
    # Created Date  : 11-01-2022
    # Purpose       : Display Customer listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function SiteuserCustomerList(Request $request){
        //echo Auth::id(); die;
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = '1';
        $this->data['page_title']="Liste de clients";
        $this->data['panel_title']="Liste de clients";

        // dd($this->data);
        
        return view('admin.usermanagement.site-user-customer-list',$this->data);
    }

    /**************************use***************************/
    # UserController
    # Function name : SiteuserCustomerListTable
    # Author        :
    # Created Date  : 11-01-2022
    # Purpose       : Display Customer listing table
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function SiteuserCustomerListTable(Request $request){
        
        //$data = User::where('role','1')->get();
        $data =DB::table('users')->
        where(function($query)
        {
            $query->where('users.user_type', '1');
                                                
        })
        ->where('users.deleted_at', NULL)->orderBy('created_at', 'desc')
                ->get();
        //dd($data);
        $finalResponse= Datatables::of($data)

            ->addColumn('first_name', function ($model){
                $name = $model->first_name;
                return $name;
            })
            ->addColumn('last_name', function ($model){
                $name = $model->last_name;
                return $name;
            })
            
            
            ->addColumn('created_time', function ($model){
                $raw = $model->created_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('Y-m-d H:i:s',$timestamp);
                return $localTime;
            })
            ->addColumn('project_count',function($model){
                $count=Project::where('user_id',$model->id)->count();
                $link= route('admin.project-management.project-list-user',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                return '<a href="'.$link.'">'.$count.'</a>';
            })
            

           

            ->addColumn('user_status', function ($model) {
                $statuslink= route('admin.user-management.reset-customer-user-status',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
          
                    if($model->account_status == '1'){
                        //btn-success
                        $statusHtml= '<button type="button" class="btn btn-xs isactive changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'"><i class="fas fa-check"></i></button>';
                    } else{
                        // btn-warning
                        $statusHtml= '<button type="button" class="btn btn-xs isinactive changeStatus" data-redirect-url='.$statuslink.' id="status'.$model->id.'"><i class="fas fa-times"></i></button>';
                    }
                
                return  $statusHtml;
            })
           ->addColumn('action', function ($model) {
                $viewlink = route('admin.user-management.user-customer-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $editlink = route('admin.user-management.user-edit',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $deletelink= route('admin.user-management.user-customer-delete',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                // $creditlink= route('admin.user-management.user-customer-credit',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                // $thiscustomer = route('admin.booking-management.customer-booking-list',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                //$invitationlink= route('admin.user-management.user-dating',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                //$changepassword= route('admin.user-management.user-changepassword',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $actions='<div class="btn-group btn-group-sm ">';
            
                $actions .='<a href="' . $viewlink . '" class="btn" id="" title="détail du client"><i class="fas fa-eye"></i></a>';
                $actions .='<a href="' . $editlink . '" class="btn" id="" title="détail du client"><i class="fas fa-pencil-alt"></i></a>';
                // $actions .='<a href="' . $editlink . '" class="btn" id="" title="éditer la fiche client"><i class="fas fa-edit"></i></a>';
                // $use = UseSlot::where('user_id', $model->id)->first();
                // $use1 = MasterBooking::where('user_id', $model->id)->where('booking_status', 'active')->count();
                
                // if($use1 == 0)
                // {
                //     $actions .='<a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn delete-alert" id="button" title="supprimer le client"><i class="fas fa-trash"></i></a>';
                // }
                
                // $actions .='<a href="' . $creditlink . '" class="btn" id="" title="gérer les crédits"><i class="fa fa-eur" aria-hidden="true"></i></a>';
                
                
                // $actions .='<a href="' . $thiscustomer . '" class="btn" id="" title="gérer les réservations"><i class="fas fa-book"></i></a>';
                
                //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
                //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
                //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;
            })
            //->rawColumns(['updated','action','status'])
            ->rawColumns(['first_name','last_name','email','user_status','created_time','project_count','action'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }

    /**************************use***************************/
    # UserController *
    # Function name : userCustomerDetail
    # Author        :
    # Created Date  : 11-01-2022
    # Purpose       : Customer detail
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function userCustomerDetail(Request $request, $encryptString){
        $this->data['page_title']="Détails du client";
        $this->data['panel_title']="Détails du client";
        $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY'));

        //$data = User::where('role','1')->get();
        /*$getDetail =DB::table('users')->where('users.id',$userId)->where('users.deleted_at', NULL)
                ->join('members','users.id', '=', 'members.user_id')
                ->where('members.deleted_at', NULL)
                ->select('users.id','users.email','users.profile_picture','users.phone','users.phone_verified', 'users.created_at', 'members.id as member_id', 'members.first_name', 'members.last_name', 'members.birth_date', 'members.gender', 'members.race', 'members.profile_description')
                ->first();*/
        $getDetail = User::where('id', $userId)->first();

        if($getDetail['account_status'] == '1')
        {
            $user_status = '<i class="fas fa-check"></i>';
        }
        else
        {
            $user_status = '<i class="fas fa-times"></i>';
        }

        $this->data['user_status'] = $user_status;

        $this->data['getDetail'] = $getDetail;

        
        
        return view('admin.usermanagement.customer-view',$this->data);

        
    }


    /**************************use***************************/
    # UserController *
    # Function name : userCustomerEdit
    # Author        :
    # Created Date  : 11-01-2022
    # Purpose       : Customer edit form
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function userCustomerEdit(Request $request, $encryptString){
        $this->data['page_title']="Client Modifier";
        $this->data['panel_title']="Client Modifier";
        // $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY'));
        $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY'));
        $this->data['encryptCode'] = $encryptString;
        
        $getDetail = User::where('id', $userId)->first();
        $this->data['getDetail'] = $getDetail;

        
        return view('admin.usermanagement.user-edit',$this->data);
        
        
    }
    public function userAdd(Request $request)
    {
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Ajouter un porteur de projet";
        $this->data['panel_title']="Ajouter un porteur de projet";
        return view('admin.usermanagement.site-user-add',$this->data);
    }
    public function userAddSave(Request $request)
    {
        try{
            if(Auth::id() and Auth::user()->user_type == 0 and Auth::user()->account_status == 1)
            {
                

                $rules=array('first_name' => 'required',
                            'last_name' => 'required',
                            // 'address' => 'required',
                            // 'postal_code' => 'required',
                            // 'village' => 'required',
                            'email' => 'required',
                            'password' => 'required|min:6',
                            'account_status' => 'required',
                            'is_subscribe_newsletter' => 'required',
                            
                             );
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) { 
                    return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
                }
                else
                {
                    $randCode = rand(1000,9999);

                    $emailExists=User::where('email',$request->email)->first();
                    if($emailExists==null)
                    {
                        $newuser = new User;
                        $rules['address']='required';
                        $rules['postal_code']='required';
                        $rules['village']='required';

                        foreach ($rules as $key => $value) {
                            if($key=='password')
                            {
                                $newuser->$key = md5($request->$key);
                            }
                            else
                            {
                                $newuser->$key = ($request->$key!=null || strlen($request->$key)>0?$request->$key:'');
                            }
                        }
                        $newuser->user_type = '1';
                        if($newuser->save())
                        {
                        
                            $mailData = array('email'=>$request->email,'password'=>$request->password);
                            $fromUser = Config::get('yourdata.admin_email_from');
                            $toUser = $request->email;
                            $subject = 'Fraternité pour Demain - Votre projet a été accepté !';
                            Mail::send('email.userregistration', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                                    $sent->from($fromUser)->subject($subject);
                                    $sent->to($toUser);
                                    $sent->to('projets@fraternitepourdemain.org','Admin Support');
                                });
                            return redirect()->back()
                                ->with('success','Client ajouté avec succès')
                                ->with('alert-class', 'alert-danger');
                        }
                    }
                    else
                    {
                        return redirect()->back()
                        ->with('error',"l'email existe déjà")
                        ->with('alert-class', 'alert-danger')
                        ->withInput();
                    }

                    
                }
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()
                ->with('error',$e->getMessage().' '.$e->getLine())
                ->with('alert-class', 'alert-danger')
                ->withInput();
        }
    }

    /********************use*********************************/
    # UserController
    # Function name : userCustomerEditSave
    # Author        :
    # Created Date  : 11-01-2022
    # Purpose       : Customer detail edit
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/

    public function userCustomerEditSave(Request $request){
    
        try{
            if(Auth::id() and Auth::user()->user_type == 0 and Auth::user()->account_status == 1)
            {
                $rules=array('first_name' => 'required',
                            'last_name' => 'required',
                            // 'address' => 'required',
                            // 'postal_code' => 'required',
                            // 'village' => 'required',
                            'email' => 'required',
                            'account_status' => 'required',
                            'is_subscribe_newsletter' => 'required',
                            
                             );
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) { 
                    return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
                }
                else
                {
                    $userId=$request->Userid;
                    $newuser = User::find($userId);
                    if(strlen($request->password)>0)
                    {
                        $rules['password']='required';
                    }
                    $rules['address']='required';
                    $rules['postal_code']='required';
                    $rules['village']='required';
                    foreach ($rules as $key => $value) {
                        if($key=='password')
                        {
                            $newuser->$key = md5($request->$key);
                        }
                        else
                        {
                            $newuser->$key = ($request->$key!=null || strlen($request->$key)>0?$request->$key:'');
                        }
                    }
                    $newuser->user_type = '1';
                    if($newuser->save())
                    {
                    
                        return redirect()->back()
                            ->with('success','Client modifié avec succès')
                            ->with('alert-class', 'alert-danger');
                    }
                }
            }
        }
        catch(\Exception $e)
        {
            return redirect()->back()
                ->with('error',$e->getMessage().' '.$e->getLine())
                ->with('alert-class', 'alert-danger')
                ->withInput();
        }

        
    }



    /************************use*****************************/
    # UserController
    # Function name : userCustomerDelete
    # Author        :
    # Created Date  : 11-01-2022
    # Purpose       : Customer Delete
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/


    public function userCustomerDelete(Request $request,$encryptString)
    {
       
        $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        

        if (User::where('id', $userId)->delete()) {
            return redirect()->route('admin.user-management.site.user.customer.list')->with('success','Le client a été supprimé avec succès !');
        } else {
            $request->session()->flash('alert-danger', 'Une erreur s\'est produite lors de la suppression du client');
             return redirect()->back();
        }

        
    }

    /********************use*********************************/
    # UserController
    # Function name : resetuserCustomerStatus
    # Author        :
    # Created Date  : 11-01-2022
    # Purpose       : Customer Status Change
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/

    public function resetuserCustomerStatus(Request $request){
    
        $response['has_error']=1;
        $response['msg']="Quelque chose c'est mal passé. Merci d'essayer plus tard.";

        $userId = decrypt($request->encryptCode, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        

        $user = User::find($userId);

        //dd($user);
            
        if($user->account_status == '1')
        {
            $status = '0';
        } 
        else 
        {
            $status = '1';
        }
        $user->account_status = $status;
        // $user->save();

        
        if($user->save()){
            $response['has_error']=0;
            $response['msg']="Statut changé avec succès.";
        }

        //print_r($response); die;
        return $response;
    }

    /************************use*****************************/
    # UserController
    # Function name : SiteuserCustomerAdd
    # Author        :
    # Created Date  : 11-01-2022
    # Purpose       : Add customer form
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function SiteuserCustomerAdd(Request $request){
        //echo 'gg';
        $this->data['page_title']="Ajouter un client";
        $this->data['panel_title']="Ajouter un client";
        
        return view('admin.usermanagement.customer-add',$this->data);
    }


    /************************use*****************************/
    # UserController
    # Function name : userCustomerAddSave
    # Author        :
    # Created Date  : 11-01-2022
    # Purpose       : Add customer save
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 6; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

    public function userCustomerAddSave(Request $request){
        
        try {

            //dd($request);

            $validator = Validator::make($request->all(), [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'email' => 'required|email|unique:users,email',
                        //'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
                        // 'address' => 'required',
                        'user_status' => 'required'
                    ]);
                    
            if ($validator->fails()) { 
                return redirect('securepanel/user-management/user-customer-add')
                                    ->withErrors($validator)
                                    ->withInput();
            }
            else
            {
                $randCode = rand(1000,9999);

                $newpass = $this->randomPassword();

                $user = new \App\Models\User;

                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->phone = $request->phone;
                $user->email = $request->email;
                $user->otp = $randCode;
                $user->account_verified = '1';
                //$user->password = md5($request->password);
                $user->password = md5($newpass);
                $user->user_type = 'client';
                $user->user_status = '1';
                $user->address = $request->address;
                $user->postal_code = $request->postal_code;
                $user->village = $request->village;
                $user->tva = $request->tva;
                $user->siret = $request->siret;
             

                if($user->save())
                {
                    /*email*/
                       $fromUser = Config::get('yourdata.admin_email_from');
                       $toUser = $request->email;
                       $subject = 'Confirmation d\'inscription, bienvenue chez La Grange !';
                       $mailData = array('first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'password' => $newpass);
                       Mail::send('email.registrationmailbyadmin', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                                $sent->from($fromUser)->subject($subject);
                                $sent->to($toUser);
                       });
                    /****/
                    session()->flash('success', 'Client ajouté avec succès');
                    Session::flash('alert-class', 'alert-success'); 
                    return redirect('securepanel/user-management/user-customer-add');
                }


            }
        }
        catch (\Exception $e) {
            //Log::error($e->getMessage());
            //session()->flash('message', $e->getMessage());
            
            // session()->flash('error', $e->getMessage());
            // Session::flash('alert-class', 'alert-danger');
            // return redirect('securepanel/user-management/user-admin-add');

            return redirect()->back()
            ->with('message',$e->getMessage())
            ->with('alert-class', 'alert-danger')
            ->withInput();
           
        }
    }


}
