<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Booking;
use App\Models\MasterBooking;
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



class BookingController extends Controller
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
    # BookingController
    # Function name : BookingList
    # Author        :
    # Created Date  : 25-01-2022
    # Purpose       : Display Customer booking listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function BookingList(Request $request){
        //echo Auth::id(); die;
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Réservations";
        $this->data['panel_title']="Réservations";

        //dd($this->data);
        
        return view('admin.bookingmanagement.booking-list',$this->data);
    }

    /**************************use***************************/
    # BookingController
    # Function name : BookingListTable
    # Author        :
    # Created Date  : 25-01-2022
    # Purpose       : Display Customer booking listing table
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function BookingListTable(Request $request){
        
        $data = MasterBooking::where('booking_status','!=','init')->get();
        // $data =DB::table('users')->
        // where(function($query)
        // {
        //     $query->where('users.user_type', 'client');
                                                
        // })
        // ->where('users.deleted_at', NULL)->orderBy('created_at', 'desc')
        //         ->get();
        //dd($data);
        $finalResponse= Datatables::of($data)

            ->addColumn('fullname', function ($model){
            	$getUser = User::where('id',$model->user_id)->first();
                $name = $getUser['first_name'].' '.$getUser['last_name'];
                return $name;
            })

            ->addColumn('user_email', function ($model){
            	$getUser = User::where('id',$model->user_id)->first();
                $thisEmail = $getUser['email'];
                return $thisEmail;
            })
            
            
            ->addColumn('created_time', function ($model){
                $raw = $model->created_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return $localTime;
            })

            ->addColumn('reserve_date', function ($model) {
                $dateHtml = '';
                $dateHtml = Carbon::createFromFormat('n-j-Y', $model->booking_date)->format('d/m/Y');
                
                return  $dateHtml;
            })


            ->addColumn('booking_status', function ($model) {
                $statusHtml = '';
                if($model->booking_status == 'active')
                {
                	$statusHtml = 'Active';
                }
                else
                {
                	if($model->booking_status == 'inactive')
                	{
                		$statusHtml = 'Inactive';
                	}
                	else
                	{
                		if($model->booking_status == 'cancel')
                		{
                			$statusHtml = 'Cancel';
                		}
                	}
                }
                
                return  $statusHtml;
            })

            ->addColumn('slot', function ($model) {
                $statusHtml = '';
                if($model->slot_half == 'first')
                {
                    $statusHtml = 'First half ('.Config::get('yourdata.first_half_start').' - '.Config::get('yourdata.first_half_end').')';
                }
                else
                {
                    if($model->slot_half == 'second')
                    {
                        $statusHtml = 'Second half ('.Config::get('yourdata.second_half_start').' - '.Config::get('yourdata.second_half_end').')';
                    }
                    else
                    {
                        if($model->slot_half == 'full')
                        {
                            $statusHtml = 'Full day ('.Config::get('yourdata.full_day_start').' - '.Config::get('yourdata.full_day_end').')';
                        }
                    }
                }
                
                return  $statusHtml;
            })

           ->addColumn('action', function ($model) {
                $viewlink = route('admin.booking-management.booking-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $editlink = route('admin.booking-management.booking-modify',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $cancellink= route('admin.booking-management.booking-cancel',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                $thiscustomer = route('admin.booking-management.customer-booking-list',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));
                
               
                $actions='<div class="btn-group btn-group-sm ">';
            
                $actions .='<a href="' . $viewlink . '" class="btn" id="" title="Détail"><i class="fas fa-eye"></i></a>';
               
                $actions .='<a href="' . $editlink . '" class="btn" id="" title="Modifier la date"><i class="fas fa-calendar-alt"></i></a>';
                if($model->booking_status == 'active')
                {
                    $actions .='<a href="'.$cancellink.'" class="btn" id="button" title="Annuler"><i class="fas fa-calendar-times"></i></a>';
                }

                $actions .='<a href="' . $thiscustomer . '" class="btn" id="" title="Cette réservation client"><i class="fas fa-book"></i></a>';
                
                
                
                //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
                //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
                //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;
            })
            //->rawColumns(['updated','action','status'])
            ->rawColumns(['fullname','user_email','reserve_date','booking_status','slot','created_time','action'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }
   /**************************use***************************/
    # BookingController
    # Function name : bookingDetail
    # Author        :
    # Created Date  : 27-01-2022
    # Purpose       : Display Customer booking listing table
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function bookingDetail(Request $request, $encryptCode){
    	$thisAdmin = User::where('id', Auth::id())->first();
        $this->data['page_title']="Détail de la réservation";
        $this->data['panel_title']="Détail de la réservation";
        $masterBookId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $getDetail = MasterBooking::where('id', $masterBookId)->first();
        $this->data['getDetail'] = $getDetail;

        $getUser = User::where('id', $getDetail['user_id'])->first();
        $this->data['getUser'] = $getUser;

        $booking_date = Carbon::createFromFormat('n-j-Y', $getDetail['booking_date'])->format('d/m/Y');
        $this->data['booking_date'] = $booking_date;

        $purchase_date = Carbon::createFromFormat('Y-m-d H:i:s', $getDetail['created_at'])->format('d/m/Y');
        $this->data['purchase_date'] = $purchase_date;

        return view('admin.bookingmanagement.booking-detail',$this->data);

    }


    /**************************use***************************/
    # BookingController
    # Function name : bookingCancel
    # Author        :
    # Created Date  : 27-01-2022
    # Purpose       : Display Customer booking cancel form
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function bookingCancel(Request $request, $encryptCode){
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['page_title']="Annuler Réserver";
        $this->data['panel_title']="Annuler Réserver";
        $masterBookId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $getDetail = MasterBooking::where('id', $masterBookId)->first();
        $this->data['getDetail'] = $getDetail;

        $getUser = User::where('id', $getDetail['user_id'])->first();
        $this->data['getUser'] = $getUser;

        $booking_date = Carbon::createFromFormat('n-j-Y', $getDetail['booking_date'])->format('d/m/Y');
        $this->data['booking_date'] = $booking_date;

        $purchase_date = Carbon::createFromFormat('Y-m-d H:i:s', $getDetail['created_at'])->format('d/m/Y');
        $this->data['purchase_date'] = $purchase_date;

        $this->data['bookingid'] = $encryptCode;

        return view('admin.bookingmanagement.booking-cancel',$this->data);

    }

    /**************************use***************************/
    # BookingController
    # Function name : bookingCancelSave
    # Author        :
    # Created Date  : 27-01-2022
    # Purpose       : Display Customer booking cancel save
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function bookingCancelSave(Request $request){

        try {

            //dd($request);
            //$userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
            $userId = $request->user_id;

            $validator = Validator::make($request->all(), [
                        'reason' => 'required'
                    ]);
                    
            if ($validator->fails()) { 
                return redirect('securepanel/booking-management/booking-cancel/'.$request->booking_id)
                                    ->withErrors($validator)
                                    ->withInput();
            }
            else
            {

                $masterBookId = decrypt($request->booking_id, Config::get('Constant.ENC_KEY'));
                

                $getMaster = MasterBooking::where('id',$masterBookId)->first();

                $booking_dt = Carbon::createFromFormat('n-j-Y', $getMaster['booking_date'])->format('d/m/Y');



             

                $slot = '';

                if($getMaster['slot_half'] == 'first')
                {
                    $slot = 'first half ('.Config::get('yourdata.first_half_start').' - '.Config::get('yourdata.first_half_end').')';
                }
                else
                {
                    if($getMaster['slot_half'] == 'second')
                    {
                        $slot = 'second half ('.Config::get('yourdata.second_half_start').' - '.Config::get('yourdata.second_half_end').')';
                    }
                    else
                    {
                        if($getMaster['slot_half'] == 'full')
                        {
                            $slot = 'full day ('.Config::get('yourdata.full_day_start').' - '.Config::get('yourdata.full_day_end').')';
                        }
                    }
                }

                // dd($slot);


                $thisMaster = MasterBooking::find($masterBookId);
                $thisMaster->booking_status = 'cancel';
                $thisMaster->cancel_reason = $request->reason;
                $thisMaster->refund_process = 'money';

                $thisMaster->save();

                // if($thisMaster->save())
                // {
                //     dd('11');
                // }
                // else
                // {
                //     dd('22');
                // }

                $getBooks = Booking::where('master_booking_id',$masterBookId)->get();

                if($getBooks)
                {
                    foreach($getBooks as $getBook)
                    {
                        $thisBook = Booking::find($getBook->id);
                        $thisBook->booking_status = 'cancel';

                        $thisBook->save();
                    }
                }

                /*email*/
                $thisUser = User::where('id',$getMaster['user_id'])->first();
                $fromUser = Config::get('yourdata.admin_email_from');
                $toUser = $thisUser['email'];
                $subject = 'Réservation de créneau annulée - Coworking';
                $mailData = array('first_name' => $thisUser['first_name'], 'last_name' => $thisUser['last_name'], 'email' => $thisUser['email'], 'booking_dt' => $booking_dt, 'slot'=>$slot);
                Mail::send('email.cancelbyadmin', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                        $sent->from($fromUser)->subject($subject);
                        $sent->to($toUser);
                    });
                /****/

                session()->flash('success', 'Réservation de créneau annulée avec succès');
                Session::flash('alert-class', 'alert-success'); 
                return redirect('securepanel/booking-management/booking-list');

            }
        }
        catch (\Exception $e) {
            //Log::error($e->getMessage());
            //session()->flash('message', $e->getMessage());
            
            // session()->flash('error', $e->getMessage());
            // Session::flash('alert-class', 'alert-danger');
            // return redirect('securepanel/user-management/user-admin-add');

            return redirect('securepanel/booking-management/booking-cancel/'.$request->booking_id)
            ->with('message',$e->getMessage())
            ->with('alert-class', 'alert-danger')
            ->withInput();
           
        }
    }



    /************************use*****************************/
    # BookingController
    # Function name : CustomerBookingList
    # Author        :
    # Created Date  : 28-01-2022
    # Purpose       : Display Customer booking listing specific customer
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function CustomerBookingList(Request $request, $encryptCode){
        //echo Auth::id(); die;
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Réservations";
        $this->data['panel_title']="Réservations";
        $this->data['encryptCode']=$encryptCode;

        //dd(decrypt($encryptCode, Config::get('Constant.ENC_KEY')));

        $getUser = User::where('id', decrypt($encryptCode, Config::get('Constant.ENC_KEY')))->first();
        $this->data['getUser'] = $getUser;

        if($getUser['user_status'] == '1')
        {
            $user_status = 'Actif';
        }
        else
        {
            $user_status = 'Inactif';
        }

        $this->data['user_status'] = $user_status;

        //dd($this->data);
        
        return view('admin.bookingmanagement.customer-booking-list',$this->data);
    }

    /**************************use***************************/
    # BookingController
    # Function name : CustomerBookingListTable
    # Author        :
    # Created Date  : 28-01-2022
    # Purpose       : Display Customer booking listing table specific customer
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function CustomerBookingListTable(Request $request, $encryptCode){
        
        $data = MasterBooking::where('booking_status','!=','init')->where('user_id', decrypt($encryptCode, Config::get('Constant.ENC_KEY')))->get();
        // $data =DB::table('users')->
        // where(function($query)
        // {
        //     $query->where('users.user_type', 'client');
                                                
        // })
        // ->where('users.deleted_at', NULL)->orderBy('created_at', 'desc')
        //         ->get();
        //dd($data);
        $finalResponse= Datatables::of($data)

            ->addColumn('fullname', function ($model){
                $getUser = User::where('id',$model->user_id)->first();
                $name = $getUser['first_name'].' '.$getUser['last_name'];
                return $name;
            })

            ->addColumn('user_email', function ($model){
                $getUser = User::where('id',$model->user_id)->first();
                $thisEmail = $getUser['email'];
                return $thisEmail;
            })
            
            
            ->addColumn('created_time', function ($model){
                $raw = $model->created_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return $localTime;
            })

            ->addColumn('reserve_date', function ($model) {
                $dateHtml = '';
                $dateHtml = Carbon::createFromFormat('n-j-Y', $model->booking_date)->format('d/m/Y');
                
                return  $dateHtml;
            })


            ->addColumn('booking_status', function ($model) {
                $statusHtml = '';
                if($model->booking_status == 'active')
                {
                    $statusHtml = 'Active';
                }
                else
                {
                    if($model->booking_status == 'inactive')
                    {
                        $statusHtml = 'Inactive';
                    }
                    else
                    {
                        if($model->booking_status == 'cancel')
                        {
                            $statusHtml = 'Cancel';
                        }
                    }
                }
                
                return  $statusHtml;
            })

            ->addColumn('slot', function ($model) {
                $statusHtml = '';
                if($model->slot_half == 'first')
                {
                    $statusHtml = 'First half ('.Config::get('yourdata.first_half_start').' - '.Config::get('yourdata.first_half_end').')';
                }
                else
                {
                    if($model->slot_half == 'second')
                    {
                        $statusHtml = 'Second half ('.Config::get('yourdata.second_half_start').' - '.Config::get('yourdata.second_half_end').')';
                    }
                    else
                    {
                        if($model->slot_half == 'full')
                        {
                            $statusHtml = 'Full day ('.Config::get('yourdata.full_day_start').' - '.Config::get('yourdata.full_day_end').')';
                        }
                    }
                }
                
                return  $statusHtml;
            })

           ->addColumn('action', function ($model) {
                $viewlink = route('admin.booking-management.booking-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $editlink = route('admin.booking-management.booking-modify',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $cancellink= route('admin.booking-management.booking-cancel',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                
               
                $actions='<div class="btn-group btn-group-sm ">';
            
                $actions .='<a href="' . $viewlink . '" class="btn" id="" title="Détail"><i class="fas fa-eye"></i></a>';
               
                $actions .='<a href="' . $editlink . '" class="btn" id="" title="Modifier la date"><i class="fas fa-calendar-alt"></i></a>';
                if($model->booking_status == 'active')
                {
                    $actions .='<a href="'.$cancellink.'" class="btn" id="button" title="Annuler"><i class="fas fa-calendar-times"></i></a>';
                }
                
                
                
                //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
                //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
                //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;
            })
            //->rawColumns(['updated','action','status'])
            ->rawColumns(['fullname','user_email','reserve_date','booking_status','slot','created_time','action'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }


   /**************************use***************************/
    # BookingController
    # Function name : bookingModify
    # Author        :
    # Created Date  : 27-01-2022
    # Purpose       : Display Customer booking modify form
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function bookingModify(Request $request, $encryptCode){
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['page_title']="Modifier la date";
        $this->data['panel_title']="Modifier la date";
        $masterBookId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $getDetail = MasterBooking::where('id', $masterBookId)->first();
        $this->data['getDetail'] = $getDetail;

        $getUser = User::where('id', $getDetail['user_id'])->first();
        $this->data['getUser'] = $getUser;

        $booking_date = Carbon::createFromFormat('n-j-Y', $getDetail['booking_date'])->format('d/m/Y');
        $this->data['booking_date'] = $booking_date;

        $purchase_date = Carbon::createFromFormat('Y-m-d H:i:s', $getDetail['created_at'])->format('d/m/Y');
        $this->data['purchase_date'] = $purchase_date;


        /**************/
        $today = date('n-j-Y');

        $firsthalfbooked = Booking::where('booking_status', 'active')->where('slot_half','first')->get();
        $is_first_book = array();
    
        foreach($firsthalfbooked as $fhalfbooked)
        {
            if(Carbon::createFromFormat('n-j-Y', $fhalfbooked->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
            {
                $is_first_book[] = $fhalfbooked->booking_date;
            }
            
        }

        $unique_is_first_book = array_unique($is_first_book);

        $confirm_unavail_first = array();

        foreach($unique_is_first_book as $unique_is_f_book)
        {
            $is_first_full =  Booking::where('booking_status', 'active')->where('slot_half','first')->where('booking_date',$unique_is_f_book)->count();

            if($is_first_full >= 8)
            {
                $confirm_unavail_first[] = $unique_is_f_book;
            }
        }
        //dd($data);

        $secondhalfbooked = Booking::where('booking_status', 'active')->where('slot_half','second')->get();
        $is_second_book = array();

        foreach($secondhalfbooked as $shalfbooked)
        {
            if(Carbon::createFromFormat('n-j-Y', $shalfbooked->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
            {
                $is_second_book[] = $shalfbooked->booking_date;
            }
            
        }

        $unique_is_second_book = array_unique($is_second_book);

        $confirm_unavail_second = array();

        foreach($unique_is_second_book as $unique_is_s_book)
        {
            $is_second_full =  Booking::where('booking_status', 'active')->where('slot_half','second')->where('booking_date',$unique_is_s_book)->count();

            if($is_second_full >= 8)
            {
                $confirm_unavail_second[] = $unique_is_s_book;
            }
        }

        $half_day_unavailable = array_intersect($confirm_unavail_first,$confirm_unavail_second);

        $merge_array = array_merge($confirm_unavail_first,$confirm_unavail_second);

        $full_day_unavailable = array_unique($merge_array);

        // $data['half_day_unavailable'] = $half_day_unavailable;
        // $data['full_day_unavailable'] = $full_day_unavailable;
        if($getDetail['slot_half'] == 'full')
        {
            $this->data['day_unavailable'] = $full_day_unavailable;
            $this->data['slot_half_type'] = 'full';
        }
        else
        {
            $this->data['day_unavailable'] = $half_day_unavailable;
            $this->data['slot_half_type'] = 'half';
        }

        $this->data['bookingid'] = $getDetail['id'];


        

        $booked_date = array();

        
            $mybookingdate = Booking::where('booking_status', 'active')->where('user_id',$getDetail['user_id'])->get();

            foreach($mybookingdate as $mybookingdate_each)
            {
                if(Carbon::createFromFormat('n-j-Y', $shalfbooked->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
                {
                    $booked_date[] = $mybookingdate_each->booking_date;
                }
                
            }
        
        $this->data['booked_date'] = $booked_date;

        // $data['booked_date'] = array_unique($booked_date);

        return view('admin.bookingmanagement.booking-modify',$this->data);

    }


    //=================================================================
    /*****************************************************/
    # BookingController
    # Function name : getThatDateSlot
    # Author        :
    # Created Date  : 31-01-2022
    # Purpose       : get available solt ajax
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function getThatDateSlot(Request $request)
    { 
        //return($request->selected_date);
        $thisDate = $request->selected_date;
        $slottype = $request->slot_type;

        $modify_date = Carbon::createFromFormat('n-j-Y', $thisDate)->format('d/m/Y');

        // return $modify_date;

        $html = '<select name="slot_half" required>';

        if($slottype == 'half')
        {
                // $first_half = Booking::where('booking_status', 'active')->where('booking_date','>=',$thisDate)->where('slot_half','first')->count();
                $first_half = Booking::where('booking_status', 'active')->where('booking_date',$thisDate)->where('slot_half','first')->count();

                //return $first_half;

                if($first_half < 8)
                {
                    $html .= '<option value="first">'.$modify_date.' '.Config::get('yourdata.first_half_start').'-'.Config::get('yourdata.first_half_end').'</option>';
                }
                else
                {
                    $html .= '<option value="first" disabled>'.$modify_date.' '.Config::get('yourdata.first_half_start').'-'.Config::get('yourdata.first_half_end').'</option>';
                }

                $second_half = Booking::where('booking_status', 'active')->where('booking_date','>=',$thisDate)->where('slot_half','second')->count();

                if($second_half < 8)
                {
                    $html .= '<option value="second">'.$modify_date.' '.Config::get('yourdata.second_half_start').'-'.Config::get('yourdata.second_half_end').'</option>';
                }
                else
                {
                    $html .= '<option value="second" disabled>'.$modify_date.' '.Config::get('yourdata.second_half_start').'-'.Config::get('yourdata.second_half_end').'</option>';
                }

        }
        else
        {
            $first_half = Booking::where('booking_status', 'active')->where('booking_date',$thisDate)->where('slot_half','first')->count();

            $second_half = Booking::where('booking_status', 'active')->where('booking_date',$thisDate)->where('slot_half','second')->count();

            //return $first_half;

            if($first_half < 8 and $first_half < 8)
            {
                $html .= '<option value="full">'.$modify_date.' '.Config::get('yourdata.full_day_start').'-'.Config::get('yourdata.full_day_end').'</option>';
            }
            else
            {
                $html .= '<option value="full" disabled>'.$modify_date.' '.Config::get('yourdata.full_day_start').'-'.Config::get('yourdata.full_day_end').'</option>';
            }

        }

        $html .= '</select>';

        return $html;


    }


/**************************use***************************/
    # BookingController
    # Function name : bookingModifySave
    # Author        :
    # Created Date  : 31-01-2022
    # Purpose       : Display Customer booking cancel save
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function bookingModifySave(Request $request){

        try {

            //dd($request);
            //$userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
            $userId = $request->user_id;

            $validator = Validator::make($request->all(), [
                        'booking_date' => 'required',
                        'slot_half' => 'required'
                    ]);
                    
            if ($validator->fails()) { 
                return redirect('securepanel/booking-management/booking-modify/'.encrypt($request->booking_id, Config::get('Constant.ENC_KEY')))
                                    ->withErrors($validator)
                                    ->withInput();
            }
            else
            {
                $masterBookId = $request->booking_id;

                $thisMaster = MasterBooking::find($masterBookId);

                if($request->slot_half_type == 'half')
                {
                    $thisMaster->booking_date = $request->booking_date;
                    $thisMaster->slot_half = $request->slot_half;

                    $thisMaster->save();

                    $getBooking = Booking::where('master_booking_id', $masterBookId)->first();

                    $thisBooking = Booking::find($getBooking['id']);

                    $thisBooking->booking_date = $request->booking_date;
                    $thisBooking->slot_half = $request->slot_half;

                    $thisBooking->save();

                    session()->flash('success', 'Date modifiée avec succès');
                    Session::flash('alert-class', 'alert-success'); 
                    return redirect('securepanel/booking-management/booking-modify/'.encrypt($request->booking_id, Config::get('Constant.ENC_KEY')));


                }
                else
                {
                    $thisMaster->booking_date = $request->booking_date;
                    // $thisMaster->slot_half = $request->slot_half;

                    $thisMaster->save();

                    $getBooking = Booking::where('master_booking_id', $masterBookId)->get();

                    $bookids = array();

                    foreach($getBooking as $getBook)
                    {
                        $bookids[] = $getBook->id;
                    }

                    foreach($bookids as $bookid)
                    {
                        $thisBooking = Booking::find($bookid);

                        $thisBooking->booking_date = $request->booking_date;
                        $thisBooking->slot_half = $request->slot_half;

                        $thisBooking->save();
                    }

                    session()->flash('success', 'Date modifiée avec succès');
                    Session::flash('alert-class', 'alert-success'); 
                    return redirect('securepanel/booking-management/booking-modify/'.encrypt($request->booking_id, Config::get('Constant.ENC_KEY')));

                    
                }

            }
        }
        catch (\Exception $e) {
            //Log::error($e->getMessage());
            //session()->flash('message', $e->getMessage());
            
            // session()->flash('error', $e->getMessage());
            // Session::flash('alert-class', 'alert-danger');
            // return redirect('securepanel/user-management/user-admin-add');

            return redirect('securepanel/booking-management/booking-modify/'.encrypt($request->booking_id, Config::get('Constant.ENC_KEY')))
            ->with('message',$e->getMessage())
            ->with('alert-class', 'alert-danger')
            ->withInput();
           
        }
    }

//=================================================================
    /*****************************************************/
    # BookingController
    # Function name : theCalendar
    # Author        :
    # Created Date  : 31-01-2022
    # Purpose       : get available solt ajax
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function theCalendar(Request $request)
    {
        /**************/

        $today = date('n-j-Y');

        $booked_date = array();

        
            $mybookingdate = MasterBooking::where('booking_status', 'active')->get();

            foreach($mybookingdate as $mybookingdate_each)
            {
                if(Carbon::createFromFormat('n-j-Y', $mybookingdate_each->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
                {
                    $booked_date[] = $mybookingdate_each->booking_date;
                }
                
            }
        
        $this->data['booked_date'] = $booked_date;
        $this->data['month'] = date('n');
        $this->data['day'] = date('j');
        $this->data['year'] = date('Y');

        $this->data['month'] = date('n');
        $this->data['day'] = date('j');
        $this->data['year'] = date('Y');
        $this->data['this_date'] = date('d/m/Y');;

        // dd($this->data);

        return view('admin.bookingmanagement.calendar',$this->data);
    }


    /**************************use***************************/
    # BookingController
    # Function name : BookingListThisDateTable
    # Author        :
    # Created Date  : 03-03-2022
    # Purpose       : Display Customer booking listing table for this date
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function BookingListThisDateTable(Request $request,$month,$day,$year){
        // dd($month);
        $thisDate = $month.'-'.$day.'-'.$year;
        $data = MasterBooking::where('booking_status','active')->where('booking_date', $thisDate)->get();
        // $data =DB::table('users')->
        // where(function($query)
        // {
        //     $query->where('users.user_type', 'client');
                                                
        // })
        // ->where('users.deleted_at', NULL)->orderBy('created_at', 'desc')
        //         ->get();
        //dd($data);
        $finalResponse= Datatables::of($data)

            ->addColumn('fullname', function ($model){
                $getUser = User::where('id',$model->user_id)->first();
                $name = $getUser['first_name'].' '.$getUser['last_name'];
                return $name;
            })

            ->addColumn('user_email', function ($model){
                $getUser = User::where('id',$model->user_id)->first();
                $thisEmail = $getUser['email'];
                return $thisEmail;
            })
            
            
            ->addColumn('created_time', function ($model){
                $raw = $model->created_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return $localTime;
            })

            ->addColumn('reserve_date', function ($model) {
                $dateHtml = '';
                $dateHtml = Carbon::createFromFormat('n-j-Y', $model->booking_date)->format('d/m/Y');
                
                return  $dateHtml;
            })


            ->addColumn('booking_status', function ($model) {
                $statusHtml = '';
                if($model->booking_status == 'active')
                {
                    $statusHtml = 'Active';
                }
                else
                {
                    if($model->booking_status == 'inactive')
                    {
                        $statusHtml = 'Inactive';
                    }
                    else
                    {
                        if($model->booking_status == 'cancel')
                        {
                            $statusHtml = 'Cancel';
                        }
                    }
                }
                
                return  $statusHtml;
            })

            ->addColumn('slot', function ($model) {
                $statusHtml = '';
                if($model->slot_half == 'first')
                {
                    $statusHtml = 'First half ('.Config::get('yourdata.first_half_start').' - '.Config::get('yourdata.first_half_end').')';
                }
                else
                {
                    if($model->slot_half == 'second')
                    {
                        $statusHtml = 'Second half ('.Config::get('yourdata.second_half_start').' - '.Config::get('yourdata.second_half_end').')';
                    }
                    else
                    {
                        if($model->slot_half == 'full')
                        {
                            $statusHtml = 'Full day ('.Config::get('yourdata.full_day_start').' - '.Config::get('yourdata.full_day_end').')';
                        }
                    }
                }
                
                return  $statusHtml;
            })

           ->addColumn('action', function ($model) {
                $viewlink = route('admin.booking-management.booking-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $editlink = route('admin.booking-management.booking-modify',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $cancellink= route('admin.booking-management.booking-cancel',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                $thiscustomer = route('admin.booking-management.customer-booking-list',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));
                
               
                $actions='<div class="btn-group btn-group-sm ">';
            
                $actions .='<a href="' . $viewlink . '" class="btn" id="" title="Détail"><i class="fas fa-eye"></i></a>';
               
                $actions .='<a href="' . $editlink . '" class="btn" id="" title="Modifier la date"><i class="fas fa-calendar-alt"></i></a>';
                if($model->booking_status == 'active')
                {
                    $actions .='<a href="'.$cancellink.'" class="btn" id="button" title="Annuler"><i class="fas fa-calendar-times"></i></a>';
                }

                $actions .='<a href="' . $thiscustomer . '" class="btn" id="" title="Cette réservation client"><i class="fas fa-book"></i></a>';
                
                
                
                //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
                //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
                //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;
            })
            //->rawColumns(['updated','action','status'])
            ->rawColumns(['fullname','user_email','reserve_date','booking_status','slot','created_time','action'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }

}