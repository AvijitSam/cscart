<?php

/*****************************************************/
# DonationController
# Page/Class name   : DonationController
# Author            :
# Created Date      : 5-08-2022
# Functionality     : donation management
# Purpose           : 
/*****************************************************/

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

use App\Http\Requests;
use Validator;
use Cookie;
use Session;
use DB;
use Crypt;
use Mail;
use View;
use Hash;
use Auth;
use DateTime;
use File, Image;
use Config;
use Route;
use Carbon\Carbon;
use Redirect;
use Webp;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\Project;
use App\Models\Gallery;
use App\Models\CampainStage;
use App\Models\CampainType;
use App\Models\Domain;
use App\Models\DoubleDonation;
use App\Models\Payment;
use App\Models\Donation;

use Illuminate\Support\Facades\Log;
use Exception;


/*
|--------------------------------------------------------------------------
|DonationController
|--------------------------------------------------------------------------
|
| donation
|
*/
class DonationController extends Controller
{
	public function DonationList(Request $request)
	{
		$this->data['page_title']="Liste des dons";
        $this->data['panel_title']="Liste des dons";

        return view('admin.donationmanagement.donation-list',$this->data);


	}
	public function DonationListTable(Request $request)
	{
		$data = Donation::join('projects', 'donations.project_id', '=', 'projects.id')
			->select('donations.id','donations.contact_first_name', 'donations.contact_last_name','donations.amount','donations.company_name','donations.amount_to_project','donations.amount_to_ngo','donations.donation_status','donations.donation_type','donations.doner_type','donations.want_refund','donations.other_reason','donations.is_anonymous','donations.created_at','projects.project_title','projects.id as project_id')
            ->orderBy('created_at', 'desc')
			->get()->toArray();
			
			
		$finalResponse= Datatables::of($data)
            ->addColumn('fullname', function ($model){
                
                // return $model['contact_first_name'].' '.$model['contact_last_name'];
                if($model['doner_type'] == 'Particular')
                {
                    return $model['contact_first_name'].' '.$model['contact_last_name'];
                }
                else
                {
                    return $model['company_name'];
                }
            })
            ->addColumn('project', function ($model){
                $viewlink =  route('admin.project-management.project-detail',  encrypt($model['project_id'], Config::get('Constant.ENC_KEY')));
                $add=route('admin.project-management.add-donation',  encrypt($model['project_id'], Config::get('Constant.ENC_KEY')));

                return '<a href="'.$viewlink.'">'.$model['project_title'].'</a><a href="' . $add . '" class="btn"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>';
            })
            ->addColumn('amount', function ($model){
                return $model['amount'].'€';
            })
            ->addColumn('amount_to_project', function ($model){
                // $a=($model['donation_type']=='double'?$model['amount_to_project']*2:$model['amount_to_project']);
                $a=$model['amount_to_project'];
                return $a.'€';
            })
            ->addColumn('amount_to_ngo', function ($model){
                return $model['amount_to_ngo'].'€';
            })
            ->addColumn('donation_status', function ($model){
                $statuslink= route('admin.project-management.donation-status',  encrypt($model['id'], Config::get('Constant.ENC_KEY')));
                $donationReminder=route('admin.donation-management.donation-reminder',  encrypt($model['id'], Config::get('Constant.ENC_KEY')) );
                $steps=array('init'=>"<span style='color:blue' class='changeStatus' data-redirect-url=".$statuslink." id='".$model['id']."-stat'>En cours</span>",
                    'completed'=>"<span style='color:green' class='changeStatus' data-redirect-url=".$statuslink." id='".$model['id']."-stat'>Validé </span>",
                    'canceled'=>"<span style='color:orange' class='changeStatus' data-redirect-url=".$statuslink." id='".$model['id']."-stat'>Cancelled</span>",
                    'fail'=>"<span style='color:red' class='changeStatus' data-redirect-url=".$statuslink." id='".$model['id']."-stat'>Failed</span>&nbsp;&nbsp;<a class='trigger-reminder' href='".$donationReminder."'><i class='fa fa-envelope'></i></a>");
                return (isset($steps[$model['donation_status']])?$steps[$model['donation_status']]:'');
                //return $model['donation_status'];
            })
            ->addColumn('donation_type', function ($model){
                $types=array('self'=>'Non','double'=>'Oui');
                return $types[$model['donation_type']];
            })
            ->addColumn('doner_type', function ($model){
                $types=array('Particular'=>'Particulier','Enterprise'=>'Entreprise');
                return $types[$model['doner_type']];
            })
            ->addColumn('is_anonym', function ($model){
               $types=array('no'=>'Non','yes'=>'Oui');
                return $types[$model['is_anonymous']];
            })
            ->addColumn('is_refund', function ($model){
               $types=array('no'=>'Oui','yes'=>'Non'); //according to column name
                return $types[$model['want_refund']];
            })
            ->addColumn('created_time', function ($model){
                // date_default_timezone_set("Europe/Paris");
                // return $timestamp=date('d/m/Y H:i:s',strtotime($model['created_at']));
                $raw = $model['created_at'].'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return '<span style="display:none;">'.$timestamp.'</span>'.$localTime;
               
            })
            ->addColumn('action', function ($model) {
                $viewlink =  route('admin.project-management.donation-detail',  encrypt($model['id'], Config::get('Constant.ENC_KEY')));

                 $certificate = route('cerificate-download',  encrypt($model['id'], Config::get('Constant.ENC_KEY')));
                
                $actions='<div class="btn-group btn-group-sm ">';
                $actions .='<a href="' . $viewlink . '" class="btn" id="" title="Voir le détail du paiement"><i class="fas fa-eye"></i></a>';

                if($model['donation_status'] == 'completed')
                {
                    $actions .='<a href="'.$certificate.'" target="_blank" class="btn" id="" title="télécharger le détail des dons"><i class="fa fa-cloud-download"></i></a>';
                }

                $actions .='</div>';
                return $actions;
            })
        ->rawColumns(['fullname','project','amount', 'amount_to_project', 'amount_to_ngo', 'donation_status','donation_type','doner_type','is_anonym','is_refund','created_time','action'])
        ->make(true);
        
       	return $finalResponse;
	}
    public function DonationReminderMail(Request $request,$encryptCode)
    {
        
        $donationId=decrypt($encryptCode, Config::get('Constant.ENC_KEY'));
        $donation = Donation::where('id',$donationId)->get()->toArray();
        $donation=$donation['0'];
        $project=Project::where('id',$donation['project_id'])->get()->toArray();
        $project=$project['0'];
        date_default_timezone_set("Europe/Paris");
        $timeDate=date('d/m',strtotime($donation['created_at']));
        $timeHours=date('H',strtotime($donation['created_at']));
        $timemins=date('i',strtotime($donation['created_at']));
        $t=($timeHours>0?$timeHours.'h':'');
        $t=$t.$timemins;
        $link=url('donation').'/'.encrypt($donation['project_id'], Config::get('Constant.ENC_KEY'));



        
        $fromUser = Config::get('yourdata.admin_email_from');
        $toUser = $donation['contact_email'];
        //$toUser = 'vishakha@matrixnmedia.com';
        $subject = 'Incident CB sur don / Fonds Fraternité pour Demain';
        $replyTo = Config::get('yourdata.reply_to_donation_payment');
        $mailData = array('first_name' => $donation['contact_first_name'], 'last_name' => $donation['contact_last_name'], 'email' => $donation['contact_email'], 'project_title' => $project['project_title'],'timedate'=>$timeDate,'timemins'=>$t,'link'=>$link);
        Mail::send('email.payment_retry_reminder', $mailData, function ($sent) use ($toUser, $fromUser, $subject, $replyTo) {
                $sent->from($fromUser)->subject($subject);
                $sent->replyTo($replyTo);
                $sent->to($toUser);
            });
        die;
    }
    public function human_time_diff( $from, $to = 0 ) {
        if ( empty( $to ) ) {
            $to = time();
        }
        $MINUTE_IN_SECONDS=60;
        $HOUR_IN_SECONDS=60 * $MINUTE_IN_SECONDS;
        $DAY_IN_SECONDS=24 * $HOUR_IN_SECONDS ;
        $MONTH_IN_SECONDS=30 * $DAY_IN_SECONDS;
        $WEEK_IN_SECONDS=7 * $DAY_IN_SECONDS;
        $YEAR_IN_SECONDS= 365 * $DAY_IN_SECONDS;
        $diff = (int) abs( $to - $from );
        $since='';
        if ( $diff < $MINUTE_IN_SECONDS ) {
            $secs = $diff;
            if ( $secs <= 1 ) {
                $secs = 1;
            }
            /* translators: Time difference between two dates, in seconds. %s: Number of seconds. */
            $since = $secs .' '.($secs>1?'seconds':'second');
        } elseif ( $diff < $HOUR_IN_SECONDS && $diff >= $MINUTE_IN_SECONDS ) {
            $mins = round( $diff / $MINUTE_IN_SECONDS );
            if ( $mins <= 1 ) {
                $mins = 1;
            }
            /* translators: Time difference between two dates, in minutes (min=minute). %s: Number of minutes. */
            $since = $mins .' '.($mins>1?'minutes':'minute');
        } elseif ( $diff < $DAY_IN_SECONDS && $diff >= $HOUR_IN_SECONDS ) {
            $hours = round( $diff / $HOUR_IN_SECONDS );
            if ( $hours <= 1 ) {
                $hours = 1;
            }
            /* translators: Time difference between two dates, in hours. %s: Number of hours. */
            $since = $hours.' '.($hours>1?'hours':'hour');
        } elseif ( $diff < $WEEK_IN_SECONDS && $diff >= $DAY_IN_SECONDS ) {
            $days = round( $diff / $DAY_IN_SECONDS );
            if ( $days <= 1 ) {
                $days = 1;
            }
            /* translators: Time difference between two dates, in days. %s: Number of days. */
            $since = sprintf( _n( '%s day', '%s days', $days ), $days );
        } elseif ( $diff < $MONTH_IN_SECONDS && $diff >= $WEEK_IN_SECONDS ) {
            $weeks = round( $diff / $WEEK_IN_SECONDS );
            if ( $weeks <= 1 ) {
                $weeks = 1;
            }
            /* translators: Time difference between two dates, in weeks. %s: Number of weeks. */
            $since = $weeks.' '.($weeks>1?'weeks':'week');
        } elseif ( $diff < $YEAR_IN_SECONDS && $diff >= $MONTH_IN_SECONDS ) {
            $months = round( $diff / $MONTH_IN_SECONDS );
            if ( $months <= 1 ) {
                $months = 1;
            }
            /* translators: Time difference between two dates, in months. %s: Number of months. */
            $since = $months;
        } elseif ( $diff >= $YEAR_IN_SECONDS ) {
            $years = round( $diff / $YEAR_IN_SECONDS );
            if ( $years <= 1 ) {
                $years = 1;
            }
            /* translators: Time difference between two dates, in years. %s: Number of years. */
            $since =  $years.' '.($years>1?'years':'year');
        }
        return $since;
    }
}