<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\PaymentExport;

use App\Models\User;
use App\Models\Booking;
use App\Models\MasterBooking;
use App\Models\Payment;
use App\Models\PurchaseSlot;
use App\Models\Slot;
use App\Models\UseSlot;
use Excel;



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
use PDF;



class PaymentController extends Controller
{
	/************************use*****************************/
    # BookingController
    # Function name : PaymentList
    # Author        :
    # Created Date  : 07-02-2022
    # Purpose       : Display Customer payment listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function PaymentList(Request $request){
        //echo Auth::id(); die;
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Paiement";
        $this->data['panel_title']="Paiement";

        $data['works'] = DB::table('payments')->where('users.deleted_at', NULL)
                ->join('users','users.id', '=', 'payments.user_id')
                //->where('members.deleted_at', NULL)
                ->select('users.id as userid','users.email','users.first_name','users.last_name','users.phone', 'payments.id as payment_id', 'payments.purchase_slot_id', 'payments.purchase_type', 'payments.amount', 'payments.payment_status', 'payments.updated_at', 'payments.created_at')
                ->orderBy('payments.created_at','desc')
                ->get();

        if($request->has('export')){
            $sheetHeading   =  "Payment report till ".date('d/m/Y');
            $fileName       =  "Payment_report_".date('dmY').".xlsx";
            return Excel::download(new \App\Exports\PaymentExport($sheetHeading,$data['works']->count()), $fileName);
        }

        //dd($this->data);
        
        return view('admin.paymentmanagement.payment-list',$this->data);
    }

    /**************************use***************************/
    # BookingController
    # Function name : PaymentListTable
    # Author        :
    # Created Date  : 25-01-2022
    # Purpose       : Display Customer booking listing table
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function PaymentListTable(Request $request){

    	$data = DB::table('payments')->where('users.deleted_at', NULL)
                ->join('users','users.id', '=', 'payments.user_id')
                //->where('members.deleted_at', NULL)
                ->select('users.id as userid','users.email','users.first_name','users.last_name','users.phone', 'payments.id as payment_id', 'payments.purchase_slot_id', 'payments.purchase_type', 'payments.amount', 'payments.payment_status', 'payments.updated_at', 'payments.created_at')
                ->orderBy('payments.created_at','desc')
                ->get();
        
        //$data = MasterBooking::where('booking_status','!=','init')->get();
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
            	//$getUser = User::where('id',$model->user_id)->first();
                $name = $model->first_name.' '.$model->last_name;
                $viewlink = route('admin.booking-management.customer-booking-list',  encrypt($model->userid, Config::get('Constant.ENC_KEY')));
                return '<a href="'.$viewlink.'">'.$name.'</a>';
            })

            // ->addColumn('user_email', function ($model){
            // 	//$getUser = User::where('id',$model->user_id)->first();
            //     $thisEmail =$model->email;
            //     return $thisEmail;
            // })
            
            
            ->addColumn('created_time', function ($model){
                $raw = $model->created_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return $localTime;
            })

            ->addColumn('payment_time', function ($model){
                $raw = $model->updated_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return $localTime;
            })

            ->addColumn('pay_amount', function ($model) {
                
                return  str_replace('.',',',number_format($model->amount, 2, '.', '')).' €';
            })


            ->addColumn('purchase_for', function ($model) {
                $statusHtml = '';
                if($model->purchase_type == 'bulk')
                {
                	$statusHtml = 'Carte liberté';
                }
                else
                {
                	$viewlink = route('admin.booking-management.booking-detail',  encrypt($model->purchase_slot_id, Config::get('Constant.ENC_KEY')));
                	$statusHtml = '<a href="'.$viewlink.'">Réservation ponctuelle</a>';
                }
                
                return  $statusHtml;
            })

            ->addColumn('paymentstatus', function ($model) {
                $statusHtml = '';
                if($model->payment_status == 'success')
                {
                    $statusHtml = 'Success';
                }
                else
                {
                    $statusHtml = 'Fail';
                }
                
                return  $statusHtml;
            })

           ->addColumn('action', function ($model) {

           		// if($model->purchase_type == 'bulk')
           		// {
           		// 	$viewlink = route('admin.payment-management.purchase-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
           		// }
           		// else
           		// {
           		// 	$viewlink = route('admin.booking-management.booking-detail',  encrypt($model->purchase_slot_id, Config::get('Constant.ENC_KEY')));
           		// }
                $viewlink = route('admin.payment-management.payment-detail',  encrypt($model->payment_id, Config::get('Constant.ENC_KEY')));
                $downloadlink = route('admin.payment-management.invoice-download',  encrypt($model->payment_id, Config::get('Constant.ENC_KEY')));
                
               
                $actions='<div class="btn-group btn-group-sm ">';
            
                $actions .='<a href="' . $viewlink . '" class="btn" id="" title="détails de la commande"><i class="fas fa-eye"></i></a>';
               
                $actions .='<a href="' . $downloadlink . '" class="btn" id="" title="télécharger la facture"><i class="fas fa-file-download"></i></a>';
                
                
                
                
                //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
                //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
                //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;
            })
            //->rawColumns(['updated','action','status'])
            ->rawColumns(['fullname','payment_time','pay_amount','purchase_for','paymentstatus','created_time','action'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }


    /************************use*****************************/
    # BookingController
    # Function name : invoiceDownload
    # Author        :
    # Created Date  : 08-02-2022
    # Purpose       : Download invoice
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function invoiceDownload(Request $request, $encryptCode)
    {
        $paymentId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $getDetail = Payment::where('id', $paymentId)->first();
        $this->data['getDetail'] = $getDetail;

        $numlength = strlen((string)$getDetail->id);
        $add_with = 0;
        $extention = 0;

        switch($numlength)
        {
                        case 1: $add_width = '0000';
                        break;
                        case 2: $add_width = '000';
                        break;
                        case 3: $add_width = '00';
                        break;
                        case 4: $add_width = '0';
                        break;
                        case 5: $add_width = '';
                        break;
                        case 6: $add_width = '';
                        break;
                        case 7: $add_width = '';
                        break;
                        case 8: $add_width = '';
                        break;
                        case 9: $add_width = '';
                        break;
                        case 10: $add_width = '';
                        break;
                        // case 1: $add_width = 100000000000000;
                        // break;
                        // case 2: $add_width = 10000000000000;
                        // break;
                        // case 3: $add_width = 1000000000000;
                        // break;
                        // case 4: $add_width = 100000000000;
                        // break;
                        // case 5: $add_width = 10000000000;
                        // break;
                        // case 6: $add_width = 1000000000;
                        // break;
                        // case 7: $add_width = 100000000;
                        // break;
                        // case 8: $add_width = 10000000;
                        // break;
                        // case 9: $add_width = 1000000;
                        // break;
                        // case 10: $add_width = 100000;
                        // break;
         }           

        $extention = $add_width;

        $serial = $extention.''.$getDetail->id;

        $getUser = User::where('id', $getDetail['user_id'])->first();
        $this->data['getUser'] = $getUser;

        $this->data['invoice_no'] = $serial;
        // $file = 'invoice-'.'LG-'.$getDetail->purchase_slot_id.'-'.$serial;
        $file = 'invoice-'.$serial;
        $this->data['today'] = date('d/m/Y');
        $this->data['fullname'] = $getUser['first_name'].' '.$getUser['last_name'];
        $this->data['email'] = $getUser['email'];
        $this->data['phone'] = $getUser['phone'];

        $this->data['address'] = $getUser['address'];
        $this->data['tva'] = $getUser['tva'];
        $this->data['siret'] = $getUser['siret'];
        $this->data['postal_code'] = $getUser['postal_code'];
        $this->data['village'] = $getUser['village'];

        $payment_date = Carbon::createFromFormat('Y-m-d H:i:s', $getDetail['updated_at'])->format('d/m/Y');
        $this->data['payment_date'] = $payment_date;

        if($getDetail['purchase_type'] == 'booking')
        {
            // echo 1; die;
            $bookingDetail = MasterBooking::where('id', $getDetail['purchase_slot_id'])->first();
            $this->data['bookingDetail'] = $bookingDetail;
            $this->data['payment_for'] = 'booking';

            $booking_date = Carbon::createFromFormat('n-j-Y', $bookingDetail['booking_date'])->format('d/m/Y');
            $this->data['booking_date'] = $booking_date;

            $purchase_date = Carbon::createFromFormat('Y-m-d H:i:s', $bookingDetail['created_at'])->format('d/m/Y');
            $this->data['purchase_date'] = $purchase_date;

            $modify_booking_date = Carbon::createFromFormat('n-j-Y', $bookingDetail['booking_date'])->format('d-m-Y');

            if($bookingDetail['slot_half'] == 'first')
            {
                $start_time = Config::get('yourdata.first_half_start');
                $end_time = Config::get('yourdata.first_half_end');
                $show_txt = 'Location à la 1/2 journée';
            }
            else
            {
                if($bookingDetail['slot_half'] == 'second')
                {
                    $start_time = Config::get('yourdata.second_half_start');
                    $end_time = Config::get('yourdata.second_half_end');
                    $show_txt = 'Location à la 1/2 journée';
                }
                else
                {
                    $start_time = Config::get('yourdata.full_day_start');
                    $end_time = Config::get('yourdata.full_day_end');
                    $show_txt = 'Location à la journée';
                }
            }

            // $show_description = '<h4>'.$show_txt.' de coworking</h4>
                    
            //          <p>'.$modify_booking_date.' / '.$start_time.' - '.$end_time.'</p>';
            // $this->data['show_description'] = $show_description;

            $show_description = $show_txt;

            // $theslot = Slot::where('id', $bookingDetail['slot_id'])->first();
            // $slot_price = $theslot['slot_price'];
            // $tax = Config::get('yourdata.tax_percent');
            // $this_tax = round(($slot_price * $tax)/100);
            // $slot_price_with_tax = round(($slot_price * ($tax + 100))/100);
            // $booking_type = $theslot['package_name'];
            // $this->data['show_description'] = $show_description;

            $theslot = Slot::where('id', $bookingDetail['slot_id'])->first();
            $slot_price_cal = $theslot['slot_price'];
            $slot_price = str_replace('.',',',number_format($theslot['slot_price'], 2, '.', ''));
            $tax = Config::get('yourdata.tax_percent');
            // $this_tax = round(($slot_price * $tax)/100);
            // $slot_price_with_tax = round(($slot_price * ($tax + 100))/100);
            $this_tax = round(($slot_price_cal * $tax)/100,2);
            $slot_price_with_tax = round(($slot_price_cal * ($tax + 100))/100, 2);

            $this_tax = str_replace('.',',',number_format($this_tax, 2, '.', ''));
            $slot_price_with_tax = str_replace('.',',',number_format($slot_price_with_tax, 2, '.', ''));

            $booking_type = $theslot['package_name'];
            $this->data['show_description'] = $show_description;


            $this->data['slot_price'] = $slot_price;
            $this->data['tax'] = $tax;
            $this->data['slot_price_with_tax'] = $slot_price_with_tax;
            $this->data['booking_type'] = $booking_type;
            $this->data['this_tax'] = $this_tax;
        }
        else
        {
            // echo 2; die;
            $purchaseDetail = PurchaseSlot::where('id', $getDetail['purchase_slot_id'])->first();
            $this->data['purchaseDetail'] = $purchaseDetail;
            $this->data['payment_for'] = 'bulk';

            $purchase_date = Carbon::createFromFormat('Y-m-d H:i:s', $purchaseDetail['created_at'])->format('d/m/Y');
            $this->data['purchase_date'] = $purchase_date;

            $show_description = 'Carte liberté '.Config::get('yourdata.bulk_total_solt').' jours';

            $this->data['show_description'] = $show_description;

            // $theslot = Slot::where('slot_type', 'bulk')->where('is_active', '1')->first();

            // $slot_price = $theslot['slot_price'];
            // $tax = Config::get('yourdata.tax_percent');
            // $this_tax = round(($slot_price * $tax)/100);
            // $slot_price_with_tax = round(($slot_price * ($tax + 100))/100);
            // $booking_type = $theslot['package_name'];

            $theslot = Slot::where('slot_type', 'bulk')->where('is_active', '1')->first();
            $slot_price_cal = $theslot['slot_price'];
            $slot_price = str_replace('.',',',number_format($theslot['slot_price'], 2, '.', ''));
            $tax = Config::get('yourdata.tax_percent');
            // $this_tax = round(($slot_price * $tax)/100);
            // $slot_price_with_tax = round(($slot_price * ($tax + 100))/100);
            $this_tax = round(($slot_price_cal * $tax)/100,2);
            $slot_price_with_tax = round(($slot_price_cal * ($tax + 100))/100, 2);

            $this_tax = str_replace('.',',',number_format($this_tax, 2, '.', ''));
            $slot_price_with_tax = str_replace('.',',',number_format($slot_price_with_tax, 2, '.', ''));

            $booking_type = $theslot['package_name'];

            $this->data['slot_price'] = $slot_price;
            $this->data['tax'] = $tax;
            $this->data['slot_price_with_tax'] = $slot_price_with_tax;
            $this->data['this_tax'] = $this_tax;
        }
        $this->data['pdf_footer'] = Config::get('yourdata.pdf_footer');
        // $data = [
        //     'title' => 'Welcome to ItSolutionStuff.com',
        //     'date' => date('m/d/Y')
        // ];

        // dd($this->data);
          
        $pdf = PDF::loadView('admin/pdf/invoice', $this->data);
        // for download
        return $pdf->download($file.'.pdf');
        // for view
        // return $pdf->setPaper('a4')->stream();

        //save
        //return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile11.pdf')->stream('download.pdf');
    }

    /**************************use***************************/
    # BookingController
    # Function name : paymentDetail
    # Author        :
    # Created Date  : 08-02-2022
    # Purpose       : Display payment detail
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function paymentDetail(Request $request, $encryptCode){
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['page_title']="Détail du paiement";
        $this->data['panel_title']="Détail du paiement";
        // $masterBookId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        // $getDetail = MasterBooking::where('id', $masterBookId)->first();
        // $this->data['getDetail'] = $getDetail;

        // $getUser = User::where('id', $getDetail['user_id'])->first();
        // $this->data['getUser'] = $getUser;

        // $booking_date = Carbon::createFromFormat('n-j-Y', $getDetail['booking_date'])->format('d/m/Y');
        // $this->data['booking_date'] = $booking_date;

        // $purchase_date = Carbon::createFromFormat('Y-m-d H:i:s', $getDetail['created_at'])->format('d/m/Y');
        // $this->data['purchase_date'] = $purchase_date;

        $paymentId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $getDetail = Payment::where('id', $paymentId)->first();
        $this->data['getDetail'] = $getDetail;

        $getUser = User::where('id', $getDetail['user_id'])->first();
        $this->data['getUser'] = $getUser;

        $payment_date = Carbon::createFromFormat('Y-m-d H:i:s', $getDetail['updated_at'])->format('d/m/Y');
        $this->data['payment_date'] = $payment_date;

        $show_amount = $getDetail['amount'];

        if($getDetail['purchase_type'] == 'booking')
        {
            $bookingDetail = MasterBooking::where('id', $getDetail['purchase_slot_id'])->first();
            $this->data['bookingDetail'] = $bookingDetail;
            $this->data['payment_for'] = 'booking';

            $booking_date = Carbon::createFromFormat('n-j-Y', $bookingDetail['booking_date'])->format('d/m/Y');
            $this->data['booking_date'] = $booking_date;

            $purchase_date = Carbon::createFromFormat('Y-m-d H:i:s', $bookingDetail['created_at'])->format('d/m/Y');
            $this->data['purchase_date'] = $purchase_date;
        }
        else
        {
            $purchaseDetail = PurchaseSlot::where('id', $getDetail['purchase_slot_id'])->first();
            $this->data['purchaseDetail'] = $purchaseDetail;
            $this->data['payment_for'] = 'bulk';

            $purchase_date = Carbon::createFromFormat('Y-m-d H:i:s', $purchaseDetail['created_at'])->format('d/m/Y');
            $this->data['purchase_date'] = $purchase_date;
        }
        //str_replace('.',',',number_format($model->amount, 2, '.', ''))
        $this->data['show_amount'] = str_replace('.',',',number_format($show_amount, 2, '.', ''));

        return view('admin.paymentmanagement.payment-detail',$this->data);

    }


    /************************use*****************************/
    # PaymentController
    # Function name : paymentReportDownload
    # Author        :
    # Created Date  : 15-03-2021
    # Purpose       : pament list download
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function paymentReportDownload(Request $request){
     
       $tasks = DB::table('payments')->where('users.deleted_at', NULL)
                ->join('users','users.id', '=', 'payments.user_id')
                //->where('members.deleted_at', NULL)
                ->select('users.id as userid','users.email','users.first_name','users.last_name','users.phone','users.address','users.postal_code','users.village','users.tva','users.siret', 'payments.id as payment_id', 'payments.purchase_slot_id', 'payments.purchase_type', 'payments.amount as grand_total', 'payments.payment_status', 'payments.updated_at', 'payments.created_at')
                ->get();

       // $fileName = 'logfile-'.$warrantyId.'.csv';
       $fileName = 'Payment List_till_'.date('d-m-y').'.csv';

       // $tasks = MasterSerialNumber::where('admin_warranty_id', $warrantyId)->get();

        $headers = array(
            "Content-type"        => "application/octet-stream; text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
            // "Refresh"             => "0",
            // "url"                 => url('/securepanel/warranty/list')

        );

        $columns = array('Nom', 'E-mail', 'Tlphone', 'Adresse', 'Code Postal', 'Ville', 'TVA', 'Siret', 'Date de paiement', 'Type', 'Amount HT', 'Amount TTC', 'VAT amount', 'Statut de paiement', 'Facture no');

        $callback = function() use($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $task) {
                /************/
                    $numlength = strlen((string)$task->payment_id);
                    $add_with = 0;
                    $extention = 0;

                    switch($numlength)
                    {
                                    case '1': $add_width = 'Facture n° - 0000';
                                    break;
                                    case '2': $add_width = 'Facture n° - 000';
                                    break;
                                    case '3': $add_width = 'Facture n° - 00';
                                    break;
                                    case '4': $add_width = 'Facture n° - 0';
                                    break;
                                    case '5': $add_width = 'Facture n° - ';
                                    break;
                                    case '6': $add_width = 'Facture n° - ';
                                    break;
                                    case '7': $add_width = 'Facture n° - ';
                                    break;
                                    case '8': $add_width = 'Facture n° - ';
                                    break;
                                    case '9': $add_width = 'Facture n° - ';
                                    break;
                                    case '10': $add_width = 'Facture n° - ';
                                    break;
                                    
                     }           

                    $extention = $add_width;
                    $serial = ''.$extention.''.$task->payment_id;
                    
                    if($task->purchase_type == 'booking')
                    {
                        // echo 1; die;
                        $bookingDetail = MasterBooking::where('id',$task->purchase_slot_id)->first();
                        

                        $theslot = Slot::where('id', $bookingDetail['slot_id'])->first();
                        $slot_price = $theslot['slot_price'];
                        $tax = Config::get('yourdata.tax_percent');
                        $this_tax = round(($slot_price * $tax)/100);
                        $slot_price_with_tax = round(($slot_price * ($tax + 100))/100);
                        $booking_type = $theslot['package_name'];
                        $this_type = 'Réservation ponctuelle';
                        
                    }
                    else
                    {
                        // echo 2; die;
                        $purchaseDetail = PurchaseSlot::where('id', $task->purchase_slot_id)->first();
                        

                        $theslot = Slot::where('slot_type', 'bulk')->where('is_active', '1')->first();

                        $slot_price = $theslot['slot_price'];
                        $tax = Config::get('yourdata.tax_percent');
                        $this_tax = round(($slot_price * $tax)/100);
                        $slot_price_with_tax = round(($slot_price * ($tax + 100))/100);
                        $booking_type = $theslot['package_name'];
                        $this_type = 'Carte liberté';

                        // $this->data['slot_price'] = $slot_price;
                        // $this->data['tax'] = $tax;
                        // $this->data['slot_price_with_tax'] = $slot_price_with_tax;
                        // $this->data['this_tax'] = $this_tax;
                    }
                    
                    $statusHtml = '';
                    if($task->payment_status == 'success')
                    {
                        $statusHtml = 'Success';
                    }
                    else
                    {
                        $statusHtml = 'Fail';
                    }
                

                    $name = $task->first_name.' '.$task->last_name;
                    $email = $task->email;
                    $phone = $task->phone;
                    $address = $task->address;
                    $postal_code = $task->postal_code;
                    $village = $task->village;
                    $tva = $task->tva;
                    $siret = $task->siret;
                    $payment_date = Carbon::createFromFormat('Y-m-d H:i:s', $task->updated_at)->format('d/m/Y');
                    $type = $this_type;
                    $ht = $slot_price.' €';
                    $ttc = $slot_price_with_tax.' €';
                    $vat = $this_tax.' €';
                    $invoice_no = $serial;
                    $payment_status = $statusHtml;
                    

                /***********/
                $row['Nom']  = $name;
                $row['E-mail']    = $email;
                $row['Tlphone']    = $phone;
                $row['Adresse']  = $address;
                $row['Code Postal']  = $postal_code;
                $row['Ville']  = $village;
                $row['TVA']  = $tva;
                $row['Siret']  = $siret;
                $row['Date de paiement']  = $payment_date;
                $row['Type']  = $type;
                $row['Amount HT']  = $ht;
                $row['Amount TTC']  = $ttc;
                $row['VAT amount']  = $vat;
                $row['Statut de paiement']  = $payment_status;
                $row['Facture no']  = $invoice_no;

                fputcsv($file, array($row['Nom'], $row['E-mail'], $row['Tlphone'], $row['Adresse'], $row['Code Postal'],$row['Ville'],$row['TVA'],$row['Siret'],$row['Date de paiement'],$row['Type'],$row['Amount HT'],$row['Amount TTC'],$row['VAT amount'],$row['Statut de paiement'],$row['Facture no']));
            }



            fclose($file);
        };

        

        return response()->stream($callback, 200, $headers);


        //return Redirect::back();
    }
}