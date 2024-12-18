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
use Excel;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
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



class CertificateController extends Controller
{
    public function dateToFrench($date, $format) 
    {
        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date) ) ) );

        // return str_replace($english_days, $french_days, str_replace($english_months, $french_months, date($format, strtotime($date) ) ) );
    }


    /************************use*****************************/
    # CertificateController
    # Function name : certificateDownload
    # Author        :
    # Created Date  : 21-10-2022
    # Purpose       : Download certificate
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function certificateDownload(Request $request, $encryptCode)
    {
        try{
        $donationId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $getThisDonation = Donation::where('id', $donationId)->first();

        // dd($getThisDonation);

        $projectId = $getThisDonation['project_id'];

        $getThisProject = Project::where('id', $projectId)->first();

        // dd($getThisProject);

        $userId = $getThisDonation['user_id'];
        $this->data['totalAmount'] = $getThisDonation['amount'];
        
        $this->data['projectAmount'] = $getThisDonation['amount_to_project'];
        
        $this->data['ngoAmount'] = $getThisDonation['amount_to_ngo'];
        // dd($this->data);
        if($getThisDonation['payment_date']!=NULL)
        {
            $this->data['payment_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation['payment_date'])->format('d/m/Y');
        }
        else
        {
            $this->data['payment_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation['created_at'])->format('d/m/Y');
        }
        

        $this->data['company_name'] = $getThisProject['associate_name'];


        $this->data['company_logo'] = $getThisProject['associate_logo'];
        
        $this->data['company_address'] = $getThisProject['associate_address'];

        $this->data['company_email'] = $getThisProject['associate_email'];
        $this->data['company_phone'] = $getThisProject['associate_phone'];

        $this->data['getThisDonation'] = $getThisDonation;

        // dd($this->data);

        if($getThisDonation['doner_type'] == 'Particular')
        {
            $deducted_amount = ($getThisDonation['amount_to_project']-($getThisDonation['amount_to_project'] *  (66/100)));
            $tax = $getThisDonation['amount_to_project'] - $deducted_amount;
            $tax_percent = 66;
        }
        else
        {
            $deducted_amount = $getThisDonation['amount_to_project'] - ($getThisDonation['amount_to_project'] *  (60/100));
            $tax = $getThisDonation['amount_to_project'] - $deducted_amount;
            $tax_percent = 60;
        }

        $this->data['deducted_amount'] = $deducted_amount;
        $this->data['tax'] = $tax;
        $this->data['tax_percent'] = $tax_percent;


       
        $this->data['projectTitle'] = $getThisProject['project_title'];

        $signature_date = date('d/m/Y');
        $this->data['signature_date'] = $signature_date;



        /***********/
                    // $numlength = strlen((string)$getThisDonation['id']);
                    // dd($getThisDonation['donation_count_no']);
                    // dd($getThisDonation['donation_count_no']);

                    $numlength = strlen((string)$getThisDonation['donation_count_no']);
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
                    // dd($extention);

                    // dd($data);
                    // dd($getThisDonation['payment_date']);

                    // $serial = 'FFD'.Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation['payment_date'])->format('Y').'-02-'.$extention.''.$getThisDonation['donation_count_no'];

                    if($getThisDonation['payment_date']!=null)
                    {
                        $serial = 'FFD'.Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation['payment_date'])->format('Y').'-02-'.$extention.''.$getThisDonation['donation_count_no'];
                    }
                    else
                    {
                        $serial = 'FFD'.Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation['created_at'])->format('Y').'-02-'.$extention.''.$getThisDonation['donation_count_no'];
                    }

                    // dd($serial);

                    $this->data['invoice_no'] = $serial;

                /**********/
        
        // $this->data['pdf_footer'] = Config::get('yourdata.pdf_footer');
        // $data = [
        //     'title' => 'Welcome to ItSolutionStuff.com',
        //     'date' => date('m/d/Y')
        // ];

        // dd($this->data);
          
        $pdf = PDF::loadView('pdf/certificate', $this->data);
        $file = 'Reçu_'.$this->data['invoice_no'];
        // dd($this->data);
        // for download
        return $pdf->download($file.'.pdf');
        // for view
        // return $pdf->setPaper('a4')->stream();

        //save
        //return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile11.pdf')->stream('download.pdf');
        }
        catch(\Exception $e)
        {
            // echo 'ee'; die;
            dd($e->getMessage().'Line - '.$e->getLine());
            // return redirect()->back()
            //         ->with('error',$e->getMessage())
            //         ->with('alert-class', 'alert-danger')
            //         ->withInput();
        }

    }

    /*public function certificateDownload(Request $request, $encryptCode)
    {
        try{
        $donationId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $getThisDonation = Donation::where('id', $donationId)->first();

        $projectId = $getThisDonation['project_id'];

        $getThisProject = Project::where('id', $projectId)->first();

        $userId = $getThisDonation['user_id'];
        $this->data['totalAmount'] = $getThisDonation['amount'];
        $this->data['projectAmount'] = $getThisDonation['amount_to_project'];
        $this->data['ngoAmount'] = $getThisDonation['amount_to_ngo'];
        $this->data['payment_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation['updated_at'])->format('d/m/Y');
        $this->data['company_name'] = $getThisProject['associate_name'];
        $this->data['company_logo'] = $getThisProject['associate_logo'];
        $this->data['company_address'] = $getThisProject['associate_address'];
        $this->data['company_email'] = $getThisProject['associate_email'];
        $this->data['company_phone'] = $getThisProject['associate_phone'];

        if($getThisDonation['doner_type'] == 'Particular')
        {
            $deducted_amount = ($getThisDonation['amount_to_project']-($getThisDonation['amount_to_project'] *  (66/100)));
            $tax = $getThisDonation['amount_to_project'] - $deducted_amount;
            $tax_percent = 66;
        }
        else
        {
            $deducted_amount = $getThisDonation['amount_to_project'] - ($getThisDonation['amount_to_project'] *  (60/100));
            $tax = $getThisDonation['amount_to_project'] - $deducted_amount;
            $tax_percent = 60;
        }

        $this->data['deducted_amount'] = $deducted_amount;
        $this->data['tax'] = $tax;
        $this->data['tax_percent'] = $tax_percent;

       
        $this->data['projectTitle'] = $getThisProject['project_title'];
        
        // $this->data['pdf_footer'] = Config::get('yourdata.pdf_footer');
        // $data = [
        //     'title' => 'Welcome to ItSolutionStuff.com',
        //     'date' => date('m/d/Y')
        // ];
          
        $pdf = PDF::loadView('pdf/certificate', $this->data);
        $file = 'Certificate_'.$getThisDonation['id'];
        // dd($this->data);
        // for download
        return $pdf->download($file.'.pdf');
        // for view
        // return $pdf->setPaper('a4')->stream();

        //save
        //return $pdf->setPaper('a4', 'landscape')->setWarnings(false)->save('myfile11.pdf')->stream('download.pdf');
        }
        catch(\Exception $e)
        {
            // echo 'ee'; die;
            dd($e->getMessage().' '.$e->getLine());
            // return redirect()->back()
            //         ->with('error',$e->getMessage())
            //         ->with('alert-class', 'alert-danger')
            //         ->withInput();
        }

    }*/
   

}