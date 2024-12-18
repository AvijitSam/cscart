<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PurchaseSlot;
use App\Models\Slot;
use App\Models\UseSlot;


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



class CreditController extends Controller
{
	/************************use*****************************/
    # CreditController
    # Function name : customerCreditDetailList
    # Author        :
    # Created Date  : 12-01-2022
    # Purpose       : Display Customer credit listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function customerCreditDetailList(Request $request, $encryptString){
        //echo Auth::id(); die;
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['page_title']="Liste de crédit client";
        $this->data['panel_title']="Liste de crédit client";
        $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY'));

        $getDetail = User::where('id', $userId)->first();
        $this->data['getDetail'] = $getDetail;

        $slotbalance = UseSlot::where('user_id', $userId)->first();

        if($slotbalance)
        {
        	$this->data['balance_slot'] = $slotbalance['balance_slot'];
        	$this->data['used_slot'] = $slotbalance['used_slot'];
        }
        else
        {
        	$this->data['balance_slot'] = 0;
        	$this->data['used_slot'] = 0;
        }

        $user_status = '';

        if($getDetail['user_status'] == 1)
        {
            $user_status = 'Actif';
        }
        else
        {
            $user_status = 'Inactif';
        }

        $this->data['user_status'] = $user_status;

        $this->data['encryptString'] = $encryptString;

        //dd($this->data);
        
        return view('admin.usercreditmanagement.customer-credit-list',$this->data);
    }

    /**************************use***************************/
    # CreditController
    # Function name : customerCreditDetailListTable
    # Author        :
    # Created Date  : 12-01-2022
    # Purpose       : Display Customer credit listing table
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function customerCreditDetailListTable(Request $request, $encryptString){
        
        //$data = User::where('role','1')->get();
        $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY'));
        $data =DB::table('purchase_slots')
        ->where('user_id', $userId)
        ->where('purchase_slots.deleted_at', NULL)->orderBy('created_at', 'desc')
                ->get();
        //dd($data);
        $finalResponse= Datatables::of($data)

            ->addColumn('slot_type', function ($model){
                $slottype = '';

                if($model->booking_type == 'single')
                {
                	$slottype = 'Instant Slot Booking';
                }
                else
                {
                	$slottype = 'Bulk Slot Booking';
                }
                return $slottype;
            })
            
            
            ->addColumn('created_time', function ($model){
                $raw = $model->created_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return $localTime;
            })

            
           ->addColumn('action', function ($model) {
                
                $deletelink= route('admin.user-management.user-customer-credit-delete',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                

                $actions='<div class="btn-group btn-group-sm ">';
            
               
                
                    $actions .='<a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn delete-alert" id="button"><i class="fas fa-trash"></i></a>';
                
                
                
                //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
                //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
                //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;
            })
            //->rawColumns(['updated','action','status'])
            ->rawColumns(['slot_type','created_time','action'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }

/**************************use***************************/
    # CreditController
    # Function name : CustomerCreditAdd
    # Author        :
    # Created Date  : 12-01-2022
    # Purpose       : Display Customer credit listing table
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function CustomerCreditAdd(Request $request, $encryptString){
    	$thisAdmin = User::where('id', Auth::id())->first();
        $this->data['page_title']="Credit Add";
        $this->data['panel_title']="Credit Add";

        $this->data['encryptString'] = $encryptString;

        return view('admin.usercreditmanagement.credit-add',$this->data);

    }


	/********************use*********************************/
    # CreditController
    # Function name : CustomerCreditAddSave
    # Author        :
    # Created Date  : 12-01-2022
    # Purpose       : Customer detail edit
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/

    public function CustomerCreditAddSave(Request $request){
    
        try {

            //dd($request);
            //$userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
            $userId = $request->user_id;

            $validator = Validator::make($request->all(), [
                        'user_id' => 'required',
                        'total_slot' => 'required'
                    ]);
                    
            if ($validator->fails()) { 
                return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id)
                                    ->withErrors($validator)
                                    ->withInput();
            }
            else
            {
            	$userId = decrypt($request->user_id, Config::get('Constant.ENC_KEY'));

                if($request->todo == "add")
                {
                    $thisAdd = new PurchaseSlot;
                    $thisAdd->user_id = $userId;
                    $thisAdd->slot_id = 0;
                    $thisAdd->total_slot = $request->total_slot;
                    $thisAdd->add_by = 'admin';
                    $thisAdd->action_type = 'credit';
                    $thisAdd->booking_type = 'bulk';

                    if($thisAdd->save())
                    {
                        $isSlotBalanceCount = UseSlot::where('user_id', $userId)->count();

                        if($isSlotBalanceCount > 0)
                        {
                            //update
                            $thisSlotBalance = UseSlot::where('user_id', $userId)->first();

                            $new_total_purchased_slot = $thisSlotBalance->total_purchased_slot + $request->total_slot;
                            $new_balance_slot = $thisSlotBalance->balance_slot + $request->total_slot;

                            $updateUserSlot = UseSlot::find($thisSlotBalance->id);

                            $updateUserSlot->total_purchased_slot = $new_total_purchased_slot;
                            $updateUserSlot->balance_slot = $new_balance_slot;

                            if($updateUserSlot->save())
                            {
                                $thisUser = User::where('id',$userId)->first();
                                /*email*/
                                   $fromUser = Config::get('yourdata.admin_email_from');
                                   $toUser = $thisUser['email'];
                                   $subject = 'Credit balance add by admin - Coworking';
                                   $mailData = array('first_name' => $thisUser['first_name'], 'last_name' => $thisUser['last_name'], 'email' => $thisUser['email'], 'credit_added' => $request->total_slot, 'balance_credit'=>$new_balance_slot);
                                   Mail::send('email.addcreditbyadmin', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                                            $sent->from($fromUser)->subject($subject);
                                            $sent->to($toUser);
                                   });
                                /****/

                                session()->flash('success', 'Crédit client ajouté avec succès');
                                Session::flash('alert-class', 'alert-success'); 
                                return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);
                            }
                            else
                            {
                                return redirect()->back()
                                ->with('message','Erreur dans la mise à jour du solde')
                                ->with('alert-class', 'alert-danger')
                                ->withInput();
                            }

                        }
                        else
                        {
                            //add

                            $addSlot = new UseSlot;

                            $addSlot->user_id = $userId;
                            $addSlot->total_purchased_slot = $request->total_slot;
                            $addSlot->balance_slot = $request->total_slot;
                            $addSlot->used_slot = 0;

                            if($addSlot->save())
                            {
                                $thisUser = User::where('id',$userId)->first();
                                /*email*/
                                   $fromUser = Config::get('yourdata.admin_email_from');
                                   $toUser = $thisUser['email'];
                                   $subject = 'Credit balance add by admin - Coworking';
                                   $mailData = array('first_name' => $thisUser['first_name'], 'last_name' => $thisUser['last_name'], 'email' => $thisUser['email'], 'credit_added' => $request->total_slot, 'balance_credit'=>$request->total_slot);
                                   Mail::send('email.addcreditbyadmin', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                                            $sent->from($fromUser)->subject($subject);
                                            $sent->to($toUser);
                                   });
                                /****/

                                session()->flash('success', 'Crédit client ajouté avec succès');
                                Session::flash('alert-class', 'alert-success'); 
                                return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);
                            }
                            else
                            {
                                return redirect()->back()
                                ->with('message','Erreur dans l\'ajout du solde')
                                ->with('alert-class', 'alert-danger')
                                ->withInput();
                            }
                        }
                    }
                    else
                    {
                            // return redirect()->back()
                            // ->with('message','Error in credit add')
                            // ->with('alert-class', 'alert-danger')
                            // ->withInput();

                        session()->flash('error', 'Erreur dans l\'ajout de crédit');
                        Session::flash('alert-class', 'alert-danger'); 
                        return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);
                    }
                }
                else
                {
                    $thisSlotBalance = UseSlot::where('user_id', $userId)->first();
                    if($thisSlotBalance)
                    {
                        if($thisSlotBalance->balance_slot >= $request->total_slot)
                        {

                            $thisAdd = new PurchaseSlot;
                            $thisAdd->user_id = $userId;
                            $thisAdd->slot_id = 0;
                            $thisAdd->total_slot = $request->total_slot;
                            $thisAdd->add_by = 'admin';
                            $thisAdd->action_type = 'debit';
                            $thisAdd->booking_type = 'bulk';

                            if($thisAdd->save())
                            {
                                $isSlotBalanceCount = UseSlot::where('user_id', $userId)->count();

                                if($isSlotBalanceCount > 0)
                                {
                                    //update
                                    

                                    $new_total_purchased_slot = $thisSlotBalance->total_purchased_slot - $request->total_slot;
                                    $new_balance_slot = $thisSlotBalance->balance_slot - $request->total_slot;

                                    $updateUserSlot = UseSlot::find($thisSlotBalance->id);

                                    $updateUserSlot->total_purchased_slot = $new_total_purchased_slot;
                                    $updateUserSlot->balance_slot = $new_balance_slot;

                                    if($updateUserSlot->save())
                                    {
                                        $thisUser = User::where('id',$userId)->first();
                                        /*email*/
                                           $fromUser = Config::get('yourdata.admin_email_from');
                                           $toUser = $thisUser['email'];
                                           $subject = 'Credit balance add by admin - Coworking';
                                           $mailData = array('first_name' => $thisUser['first_name'], 'last_name' => $thisUser['last_name'], 'email' => $thisUser['email'], 'credit_added' => $request->total_slot, 'balance_credit'=>$new_balance_slot);
                                           Mail::send('email.removecreditbyadmin', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                                                    $sent->from($fromUser)->subject($subject);
                                                    $sent->to($toUser);
                                           });
                                        /****/

                                        session()->flash('success', 'Crédit client supprimé avec succès');
                                        Session::flash('alert-class', 'alert-success'); 
                                        return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);
                                    }
                                    else
                                    {
                                        session()->flash('error', 'Erreur dans la mise à jour du solde');
                                        Session::flash('alert-class', 'alert-danger'); 
                                        return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);

                                        
                                    }

                                }
                                else
                                {
                                    // //add

                                    // $addSlot = new UseSlot;

                                    // $addSlot->user_id = $userId;
                                    // $addSlot->total_purchased_slot = $request->total_slot;
                                    // $addSlot->balance_slot = $request->total_slot;
                                    // $addSlot->used_slot = 0;

                                    // if($addSlot->save())
                                    // {
                                    //     $thisUser = User::where('id',$userId)->first();
                                    //     /*email*/
                                    //        $fromUser = Config::get('yourdata.admin_email_from');
                                    //        $toUser = $thisUser['email'];
                                    //        $subject = 'Credit balance add by admin - Coworking';
                                    //        $mailData = array('first_name' => $thisUser['first_name'], 'last_name' => $thisUser['last_name'], 'email' => $thisUser['email'], 'credit_added' => $request->total_slot, 'balance_credit'=>$new_balance_slot);
                                    //        Mail::send('email.addcreditbyadmin', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                                    //                 $sent->from($fromUser)->subject($subject);
                                    //                 $sent->to($toUser);
                                    //        });
                                    //     /****/

                                    //     session()->flash('success', 'Customer credit added successfully');
                                    //     Session::flash('alert-class', 'alert-success'); 
                                    //     return redirect('securepanel/user-management/ucustomer-credit-add/'.$request->user_id);
                                    // }
                                    // else
                                    // {
                                    //     return redirect()->back()
                                    //     ->with('message','Error in balance add')
                                    //     ->with('alert-class', 'alert-danger')
                                    //     ->withInput();
                                    // }

                                    session()->flash('error', 'Do not have credit balance to deduct');
                                        Session::flash('alert-class', 'alert-danger'); 
                                        return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);

                                    
                                }
                            }
                            else
                            {
                                    // return redirect()->back()
                                    // ->with('message','Error in credit add')
                                    // ->with('alert-class', 'alert-danger')
                                    // ->withInput();
                                session()->flash('error', 'Erreur dans la suppression du crédit');
                                        Session::flash('alert-class', 'alert-danger'); 
                                        return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);

                            } 
                        }
                        else
                        {
                            //echo $request->total_slot; die;
                            //  return redirect()->back()
                            // ->with('message','Do not have enough balance to deduct')
                            // ->with('alert-class', 'alert-danger')
                            // ->withInput();

                            session()->flash('error', 'Vous n\'avez pas assez de solde pour déduire');
                            Session::flash('alert-class', 'alert-danger'); 
                            return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);
                        }
                    }
                    else
                    {
                        session()->flash('error', 'Vous n\'avez pas de solde à déduire');
                        Session::flash('alert-class', 'alert-danger'); 
                        return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);
                    }
                }
            	



            }
        }
        catch (\Exception $e) {
            //Log::error($e->getMessage());
            //session()->flash('message', $e->getMessage());
            
            // session()->flash('error', $e->getMessage());
            // Session::flash('alert-class', 'alert-danger');
            // return redirect('securepanel/user-management/user-admin-add');

            // return redirect()->back()
            // ->with('message',$e->getMessage())
            // ->with('alert-class', 'alert-danger')
            // ->withInput();

            session()->flash('error', $e->getMessage());
                        Session::flash('alert-class', 'alert-danger'); 
                        return redirect('securepanel/user-management/user-customer-credit/'.$request->user_id);
           
        }
    }

}