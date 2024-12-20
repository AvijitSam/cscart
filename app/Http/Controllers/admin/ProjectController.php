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
use App\Models\DonationCount;
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

use App\Http\Helpers\DonationHelper;



class ProjectController extends Controller
{
    
    private $prstats=array('pending'=>'Révision',//pending=pending
                           'active'=>'Approuvé',//approved=active
                           'rejected'=>'Rejeté',//cancelled=inactive
                           'succeed'=>'Terminé - réussi',//rejected=rejected
                           'failed'=>'Terminé - échoué',//completed=close
                           'archived'=>'Archivé'//completed=close
                           );
    // 'pending','active','rejected','succeed','failed'

    public function dateToFrench($date, $format) 
    {
        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date) ) ) );

        // return str_replace($english_days, $french_days, str_replace($english_months, $french_months, date($format, strtotime($date) ) ) );
    }

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
    # ProjectController
    # Function name : ProjectList
    # Author        :
    # Created Date  : 26-07-2022
    # Purpose       : Display project listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function ProjectList(Request $request){
        //echo Auth::id(); die;
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Projets";
        $this->data['panel_title']="Projets";

        //dd($this->data);

       

        if($request->has('export')){
            $projectID = decrypt($request->project_id, Config::get('Constant.ENC_KEY'));
            $thisProject = Project::where('id', $projectID)->first();
            // $sheetHeading   =  $thisProject['project_title']." ".date('jS F, Y');
            $sheetHeading   =  $thisProject['project_title']." ".$this->dateToFrench(date('j F Y'), 'j F Y');
            $fileName       =  $thisProject['project_slug']."-".date('d-M-Y').".xlsx";

             $data['works'] = Donation::where('id', $projectID)
                ->orderBy('created_at','desc')
                ->get();

            return Excel::download(new \App\Exports\DonationExport($sheetHeading,$data['works']->count(), $projectID), $fileName);
        }
        
        return view('admin.projectmanagement.project-list',$this->data);
    }

    public function ProjectListByUser(Request $request,$encryptCode)
    {
        $thisAdmin = User::where('id', Auth::id())->first();
        $userID = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Projets";
        $this->data['panel_title']="Projets";
        // $this->data['userID']=$userID;
        $this->data['userID']=$encryptCode;



        // if($request->has('export')){
        //     $projectID = decrypt($request->project_id, Config::get('Constant.ENC_KEY'));
        //     $thisProject = Project::where('id', $projectID)->first();
        //     // $sheetHeading   =  $thisProject['project_title']." ".date('jS F, Y');
        //     $sheetHeading   =  $thisProject['project_title']." ".$this->dateToFrench(date('j F, Y'), 'j F, Y');
        //     $fileName       =  $thisProject['project_slug']."-".date('d-M-Y').".xlsx";

        //     $data['works'] = Donation::where('id', $projectID)
        //         ->orderBy('created_at','desc')
        //         ->get();

        //     return Excel::download(new \App\Exports\DonationExport($sheetHeading,$data['works']->count(), $projectID), $fileName);
        // }


        return view('admin.projectmanagement.project-list-user',$this->data);
    }

    /**************************use***************************/
    # ProjectController
    # Function name : ProjectListTable
    # Author        :
    # Created Date  : 26-01-2022
    # Purpose       : Display project listing table
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function ProjectListTable(Request $request){
        
        // $datas = MasterBooking::where('booking_status','!=','init')->orderBy('booking_date','desc')->get();
        $userID=$request->user;
        
        if($userID>0)
        {
            $data = Project::where('user_id',$userID)->orderBy('created_at', 'desc')->get();
        }
        else
        {
            $data = Project::orderBy('created_at', 'desc')->get();
        }
        // dd($data);
        
        $finalResponse= Datatables::of($data)

        // dd($data);

            ->addColumn('fullname', function ($model){
                $getUser = User::where('id',$model->user_id)->first();
                $name = $getUser['first_name'].' '.$getUser['last_name'];
                
                return $name;
            })

            ->addColumn('domain', function ($model){

                // return $model->domain_id;

                if($model->domain_id != NULL or $model->domain_id != '')
                {
                    $domains = explode(',',$model->domain_id);
                    $domain_name='';
                    $cnt = 1;
                    $domain_size = sizeof($domains);
                    foreach($domains as $domain)
                    {
                        $thisDomain = Domain::where('id',$domain)->first();
                        $domain_name.=$thisDomain['domain_name'];
                        if($cnt != $domain_size)
                        {
                            $domain_name.= ',';
                        }
                        $cnt++;
                    }
                }
                else
                {
                    return $domain_name = '';
                }
                
                
                
                return $domain_name;
            })

            ->addColumn('project_status_name', function ($model){
                //enum('pending', 'active', 'inactive', 'rejected', ... 
                $proj_status = '';
                return $this->prstats[$model->project_status];
            })
            
            
            ->addColumn('created_time', function ($model){
                $raw = $model->created_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return '<span style="display:none;">'.$timestamp.'</span>'.$localTime;
            })


            ->addColumn('double', function ($model) {
                
                $doubleamount = route('admin.project-management.set-double-amount',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));
                
               
                $actions='<div class="btn-group btn-group-sm ">';
            
               
                if($model->is_double == '1')
                {
                    $actions .='<a href="' . $doubleamount . '" class="btn" id="" title="Cette réservation client"><i class="fas fa-hand-holding-usd"></i> Yes ('.$model->double_amount_limit.'€)</a>';
                }
                else
                {
                    $actions .='<a href="' . $doubleamount . '" class="btn" id="" title="Cette réservation client"><i class="fas fa-hand-holding-usd"></i> No</a>';
                }
                
                $actions .='</div>';
                return $actions;
            })

            ->addColumn('recom_amount', function ($model){
                $type=$model->number_of_goal;
                if($type==1)
                {
                    if($model->single_goal_amount == '')
                    {
                        $amt = '0';
                    }
                    else
                    {
                        $amt = $model->single_goal_amount;
                    }
                   return $amt.'€'; 
                }
                else
                {
                    if($model->third_goal_amount == '')
                    {
                        $amt = '0';
                    }
                    else
                    {
                        $amt = $model->third_goal_amount;
                    }
                   return $amt.'€'; 
                    // return $model->third_goal_amount.'€';
                }
            })
            ->addColumn('double_amount_limit',function($model){
                $d=$model->is_double;
                if($d=='1')
                {
                    return $model->double_amount_limit.'€';
                }
                else
                {
                    return 'Non';
                }
            })

            // ->addColumn('donation', function ($model){
            //         $double = Donation::where('project_id',$model->id)
            //           ->whereNotIn('donation_status',['canceled','fail','processing'])
            //           ->where('donation_type','double')
            //           ->get()->sum('amount_to_project');
            //         $double=$double*2;

            //         $single=Donation::where('project_id',$model->id)
            //           ->whereNotIn('donation_status',['canceled','fail','processing'])
            //           ->where('donation_type','!=','double')
            //           ->get()->sum('amount_to_project');
            //         $sum=$double+$single;
            //     // $sum = Donation::where('project_id',$model->id)->where('donation_status','completed')->sum('amount');
            //     $donationLink=route('admin.project-management.donation-list',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
            //     return '<a style="color:#007bff" href="'.$donationLink.'" class="btn" id="" title="Donation">'.$sum.'€</a>';
            // })

            ->addColumn('donation', function ($model){
                    // $double = Donation::where('project_id',$model->id)
                    //   ->whereNotIn('donation_status',['canceled','fail','processing'])
                    //   ->where('donation_type','double')
                    //   ->get()->sum('amount_to_project');
                    // $double=$double*2;

                    // $single=Donation::where('project_id',$model->id)
                    //   ->whereNotIn('donation_status',['canceled','fail','processing'])
                    //   ->where('donation_type','!=','double')
                    //   ->get()->sum('amount_to_project');
                    // $sum=$double+$single;
                    /*$proj_id = $model->id;
                    $getThisDonationsCount = Donation::where('project_id',$proj_id)->where('donation_status','completed')->count();

                    $thisDonation = 0;

                    if($getThisDonationsCount > 0)
                    {
                         $getThisDonations = Donation::where('project_id',$proj_id)->where('donation_status','completed')->get();
                         foreach($getThisDonations as $getThisDonation)
                         {
                            $thisDonation += $getThisDonation['amount_to_project'];
                         }
                    }

                    $getThisDonationsDoubles = DoubleDonation::where('project_id',$proj_id)->where('double_donation_status','!=','canceled')->get();

                    if(!empty($getThisDonationsDoubles))
                    {
                         
                         foreach($getThisDonationsDoubles as $getThisDonationsDouble)
                         {
                            $thisDonation += $getThisDonationsDouble['double_amount'];
                         }
                    }*/

                    // $data['till_donation'] = $thisDonation;
                // $sum = Donation::where('project_id',$model->id)->where('donation_status','completed')->sum('amount');

                $thisDonation = DonationHelper::donationCalculation($model->id);
                $donationLink=route('admin.project-management.donation-list',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                return '<a style="color:#007bff" href="'.$donationLink.'" class="btn" id="" title="Donation">'.$thisDonation.'€</a>';
            })

            //<span>YYYYMMDD</span>

            ->addColumn('this_start_date', function ($model){
                
                    // return '<span>YYYYMMDD</span>'.$model->start_date;
                // return Carbon::createFromFormat('d-m-Y', $model->start_date)->format('Ymd');
                    if($model->start_date!='')
                    {
                        $start = $model->start_date;
                        return Carbon::createFromFormat('d-m-Y', $start)->format('Ymd');
                    }
                    else
                    {
                        return '';
                    }
                
            })

            ->addColumn('this_end_date', function ($model){
                    if($model->end_date!='')
                    {
                        $end = $model->end_date;
                        return Carbon::createFromFormat('d-m-Y', $end)->format('Ymd');
                    }
                    else
                    {
                        return '';
                    }
                    
                // return Carbon::createFromFormat('d-m-Y', $model->end_date)->format('Ymd');
                
            })

            

           ->addColumn('action', function ($model) {
                $viewlink = route('admin.project-management.project-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $editlink = route('admin.project-management.project-modify',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $statuschangelink= route('admin.project-management.project-status-change',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                $thisdonation = route('admin.project-management.donation-list',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));

                $donationdownload = route('admin.project-management.donation-download',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));

                $doubleamount = route('admin.project-management.set-double-amount',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));

                $addDonation = route('admin.project-management.add-donation',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                
                $front=url('/').'/'.$model->project_slug;
               
                $actions='<div class="btn-group btn-group-sm ">';
            
                $actions .='<a href="' . $viewlink . '" class="btn" id="" title="détails de la project"><i class="fas fa-eye"></i></a>';
               
                $actions .='<a href="' . $editlink . '" class="btn" id="" title="modifier la project"><i class="fas fa-pencil-alt"></i></a>';
                
                $actions .='<a href="'.$statuschangelink.'" class="btn" id="button" title="changer de statut"><i class="fas fa-hourglass"></i></a>';
                
                //$donationdownload
                // $actions .='<a href="#" class="btn" id="" title="télécharger le détail des dons"><i class="fas fa-download"></i></a>';

                // if($request->user>0)
                // {
                //     $actions .="<form action='".route('admin.project-management.project-list-user',  encrypt($request->user, Config::get('Constant.ENC_KEY')))."' method='GET'  id='Credit_User'>".csrf_field()."
                //                            <button type='submit' name='export' tabindex='8' class='btn export'>Export</button>
                //                         </form>";
                // }
                // else
                // {
                    $actions .="<form action='".route('admin.project-management.project-list')."' method='GET'  id='Credit_User'>".csrf_field()."<input type='hidden' name='project_id' value='".encrypt($model->id, Config::get('Constant.ENC_KEY'))."'/>
                                           <button type='submit' name='export' tabindex='8' class='btn export'><i class='fas fa-download'></i></button>
                                        </form>";
                // }
                


                $actions .='<a href="'.$front.'" target="_blank" class="btn" id="" title="télécharger le détail des dons"><i class="fa fa-link"></i></a>';

                $actions .='<a href="'.$addDonation.'" target="_blank" class="btn" id="" title="télécharger le détail des dons"><i class="fa fa-plus"></i></a>';


                

                //$actions .='<a href="' . $doubleamount . '" class="btn" id="" title="ensemble de montant double"><i class="fas fa-euro-sign"></i></a>';
                
                
                
                //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
                //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
                //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;
            })
            //->rawColumns(['updated','action','status'])
            ->rawColumns(['fullname','project_status_name', 'double', 'domain', 'donation', 'recom_amount','created_time','action', 'this_start_date', 'this_end_date'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }

    public function ProjectListTableUser(Request $request,$encryptCode){
        
        // $datas = MasterBooking::where('booking_status','!=','init')->orderBy('booking_date','desc')->get();
        // $userID=$request->user;
        $userID=decrypt($encryptCode, Config::get('Constant.ENC_KEY'));
        // dd($userID);
        if($userID>0)
        {
            $data = Project::where('user_id',$userID)->orderBy('created_at', 'desc')->get();
        }
        else
        {
            $data = Project::orderBy('created_at', 'desc')->get();
        }

        // dd($data);

        
        $finalResponse= Datatables::of($data)

            ->addColumn('fullname', function ($model){
                $getUser = User::where('id',$model->user_id)->first();
                $name = $getUser['first_name'].' '.$getUser['last_name'];
                
                return $name;
            })

            ->addColumn('domain', function ($model){

                // return $model->domain_id;

                if($model->domain_id != NULL or $model->domain_id != '')
                {
                    $domains = explode(',',$model->domain_id);
                    $domain_name='';
                    $cnt = 1;
                    $domain_size = sizeof($domains);
                    foreach($domains as $domain)
                    {
                        $thisDomain = Domain::where('id',$domain)->first();
                        $domain_name.=$thisDomain['domain_name'];
                        if($cnt != $domain_size)
                        {
                            $domain_name.= ',';
                        }
                        $cnt++;
                    }
                }
                else
                {
                    return $domain_name = '';
                }
                
                
                
                return $domain_name;
            })

            ->addColumn('project_status_name', function ($model){
                //enum('pending', 'active', 'inactive', 'rejected', ... 
                $proj_status = '';
                return $this->prstats[$model->project_status];
            })
            
            
            ->addColumn('created_time', function ($model){
                $raw = $model->created_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return '<span style="display:none;">'.$timestamp.'</span>'.$localTime;
            })


            ->addColumn('double', function ($model) {
                
                $doubleamount = route('admin.project-management.set-double-amount',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));
                
               
                $actions='<div class="btn-group btn-group-sm ">';
            
               
                if($model->is_double == '1')
                {
                    $actions .='<a href="' . $doubleamount . '" class="btn" id="" title="Cette réservation client"><i class="fas fa-hand-holding-usd"></i> Yes ('.$model->double_amount_limit.'€)</a>';
                }
                else
                {
                    $actions .='<a href="' . $doubleamount . '" class="btn" id="" title="Cette réservation client"><i class="fas fa-hand-holding-usd"></i> No</a>';
                }
                
                $actions .='</div>';
                return $actions;
            })

            // ->addColumn('recom_amount', function ($model){
            //     $type=$model->number_of_goal;
            //     if($type==1)
            //     {
            //        return $model->single_goal_amount.'€'; 
            //     }
            //     else
            //     {
            //         return $model->third_goal_amount.'€';
            //     }
            // })
            ->addColumn('recom_amount', function ($model){
                $type=$model->number_of_goal;
                if($type==1)
                {
                    if($model->single_goal_amount == '')
                    {
                        $amt = '0';
                    }
                    else
                    {
                        $amt = $model->single_goal_amount;
                    }
                   return $amt.'€'; 
                }
                else
                {
                    if($model->third_goal_amount == '')
                    {
                        $amt = '0';
                    }
                    else
                    {
                        $amt = $model->third_goal_amount;
                    }
                   return $amt.'€'; 
                    // return $model->third_goal_amount.'€';
                }
            })
            ->addColumn('double_amount_limit',function($model){
                $d=$model->is_double;
                if($d=='1')
                {
                    return $model->double_amount_limit.'€';
                }
                else
                {
                    return 'Non';
                }
            })

            // ->addColumn('donation', function ($model){
            //         $double = Donation::where('project_id',$model->id)
            //           ->whereNotIn('donation_status',['canceled','fail','processing'])
            //           ->where('donation_type','double')
            //           ->get()->sum('amount_to_project');
            //         $double=$double*2;

            //         $single=Donation::where('project_id',$model->id)
            //           ->whereNotIn('donation_status',['canceled','fail','processing'])
            //           ->where('donation_type','!=','double')
            //           ->get()->sum('amount_to_project');
            //         $sum=$double+$single;
            //     // $sum = Donation::where('project_id',$model->id)->where('donation_status','completed')->sum('amount');
            //     $donationLink=route('admin.project-management.donation-list',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
            //     return '<a style="color:#007bff" href="'.$donationLink.'" class="btn" id="" title="Donation">'.$sum.'€</a>';
            // })

            ->addColumn('donation', function ($model){
                    // $double = Donation::where('project_id',$model->id)
                    //   ->whereNotIn('donation_status',['canceled','fail','processing'])
                    //   ->where('donation_type','double')
                    //   ->get()->sum('amount_to_project');
                    // $double=$double*2;

                    // $single=Donation::where('project_id',$model->id)
                    //   ->whereNotIn('donation_status',['canceled','fail','processing'])
                    //   ->where('donation_type','!=','double')
                    //   ->get()->sum('amount_to_project');
                    // $sum=$double+$single;
                    /*$proj_id = $model->id;
                    $getThisDonationsCount = Donation::where('project_id',$proj_id)->where('donation_status','completed')->count();

                    $thisDonation = 0;

                    if($getThisDonationsCount > 0)
                    {
                         $getThisDonations = Donation::where('project_id',$proj_id)->where('donation_status','completed')->get();
                         foreach($getThisDonations as $getThisDonation)
                         {
                            $thisDonation += $getThisDonation['amount_to_project'];
                         }
                    }

                    $getThisDonationsDoubles = DoubleDonation::where('project_id',$proj_id)->where('double_donation_status','!=','canceled')->get();

                    if(!empty($getThisDonationsDoubles))
                    {
                         
                         foreach($getThisDonationsDoubles as $getThisDonationsDouble)
                         {
                            $thisDonation += $getThisDonationsDouble['double_amount'];
                         }
                    }*/

                    // $data['till_donation'] = $thisDonation;
                // $sum = Donation::where('project_id',$model->id)->where('donation_status','completed')->sum('amount');

                $thisDonation = DonationHelper::donationCalculation($model->id);
                $donationLink=route('admin.project-management.donation-list',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                return '<a style="color:#007bff" href="'.$donationLink.'" class="btn" id="" title="Donation">'.$thisDonation.'€</a>';
            })

            ->addColumn('this_start_date', function ($model){
                
                    // return '<span>YYYYMMDD</span>'.$model->start_date;
                // return Carbon::createFromFormat('d-m-Y', $model->start_date)->format('Ymd');
                    if($model->start_date!='')
                    {
                        $start = $model->start_date;
                        return Carbon::createFromFormat('d-m-Y', $start)->format('Ymd');
                    }
                    else
                    {
                        return '';
                    }
                
            })

            ->addColumn('this_end_date', function ($model){
                    if($model->end_date!='')
                    {
                        $end = $model->end_date;
                        return Carbon::createFromFormat('d-m-Y', $end)->format('Ymd');
                    }
                    else
                    {
                        return '';
                    }
                    
                // return Carbon::createFromFormat('d-m-Y', $model->end_date)->format('Ymd');
                
            })


            

           ->addColumn('action', function ($model) {
                $viewlink = route('admin.project-management.project-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $editlink = route('admin.project-management.project-modify',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $statuschangelink= route('admin.project-management.project-status-change',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                $thisdonation = route('admin.project-management.donation-list',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));

                $donationdownload = route('admin.project-management.donation-download',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));

                $doubleamount = route('admin.project-management.set-double-amount',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));

                $addDonation = route('admin.project-management.add-donation',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                
                $front=url('/').'/'.$model->project_slug;
               
                $actions='<div class="btn-group btn-group-sm ">';
            
                $actions .='<a href="' . $viewlink . '" class="btn" id="" title="détails de la project"><i class="fas fa-eye"></i></a>';
               
                $actions .='<a href="' . $editlink . '" class="btn" id="" title="modifier la project"><i class="fas fa-pencil-alt"></i></a>';
                
                $actions .='<a href="'.$statuschangelink.'" class="btn" id="button" title="changer de statut"><i class="fas fa-hourglass"></i></a>';
                
                //$donationdownload
                // $actions .='<a href="#" class="btn" id="" title="télécharger le détail des dons"><i class="fas fa-download"></i></a>';

                // if($request->user>0)
                // {
                //     $actions .="<form action='".route('admin.project-management.project-list-user',  encrypt($request->user, Config::get('Constant.ENC_KEY')))."' method='GET'  id='Credit_User'>".csrf_field()."
                //                            <button type='submit' name='export' tabindex='8' class='btn export'>Export</button>
                //                         </form>";
                // }
                // else
                // {
                    $actions .="<form action='".route('admin.project-management.project-list')."' method='GET'  id='Credit_User'>".csrf_field()."<input type='hidden' name='project_id' value='".encrypt($model->id, Config::get('Constant.ENC_KEY'))."'/>
                                           <button type='submit' name='export' tabindex='8' class='btn export'><i class='fas fa-download'></i></button>
                                        </form>";
                // }
                


                $actions .='<a href="'.$front.'" target="_blank" class="btn" id="" title="télécharger le détail des dons"><i class="fa fa-link"></i></a>';

                $actions .='<a href="'.$addDonation.'" target="_blank" class="btn" id="" title="télécharger le détail des dons"><i class="fa fa-plus"></i></a>';


                

                //$actions .='<a href="' . $doubleamount . '" class="btn" id="" title="ensemble de montant double"><i class="fas fa-euro-sign"></i></a>';
                
                
                
                //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
                //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
                //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
                $actions .='</div>';
                return $actions;
            })
            //->rawColumns(['updated','action','status'])
            ->rawColumns(['fullname','project_status_name', 'double', 'domain', 'donation', 'recom_amount','created_time','action','this_start_date','this_end_date'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }
    /**************************use***************************/
    # BookingController
    # Function name : DonationList
    # Author        :
    # Created Date  : 26-07-2022
    # Purpose       : Display donation list for projects
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/
    public function DonationList(Request $request,$encryptCode)
    {
        $projectId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $thisProject = Project::where('id',$projectId)->first();
        

        $proj_id = $projectId;
        $getThisDonationsCount = Donation::where('project_id',$proj_id)->where('donation_status','completed')->count();

        /*$thisDonation = 0;

        if($getThisDonationsCount > 0)
        {
            $getThisDonations = Donation::where('project_id',$proj_id)->where('donation_status','completed')->get();
            foreach($getThisDonations as $getThisDonation)
            {
                $thisDonation += $getThisDonation['amount_to_project'];
            }
        }

        $getThisDonationsDoubles = DoubleDonation::where('project_id',$proj_id)->where('double_donation_status','!=','canceled')->get();

        if(!empty($getThisDonationsDoubles))
        {
                         
            foreach($getThisDonationsDoubles as $getThisDonationsDouble)
            {
                $thisDonation += $getThisDonationsDouble['double_amount'];
            }
        }*/
        $thisDonation = DonationHelper::donationCalculation($proj_id);
        $donationTot=$thisDonation;
        
        $this->data['project'] = $thisProject;
        $this->data['sum']=$donationTot.'€';
        $this->data['page_title']="Donation List";
        $this->data['panel_title']="Donation List";

        $this->data['project_id']=$encryptCode;

        //dd($this->data);

        if($request->has('export')){
            $projectID = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));
            $thisProject = Project::where('id', $projectID)->first();
            // $sheetHeading   =  $thisProject['project_title']." ".date('jS F, Y');
            $sheetHeading   =  $thisProject['project_title']." ".$this->dateToFrench(date('j F Y'), 'j F Y');

            $fileName       =  $thisProject['project_slug']."-".date('d-M-Y').".xlsx";

            $data['works'] = Donation::where('id', $projectID)
                ->orderBy('created_at','desc')
                ->get();

            return Excel::download(new \App\Exports\DonationExport($sheetHeading,$data['works']->count(), $projectID), $fileName);
        }
        
        return view('admin.projectmanagement.donation-list',$this->data);
    }
    public function DonationStatus(Request $request,$encryptCode)
    {
        $response['has_error']=1;
        $response['msg']="Quelque chose c'est mal passé. Merci d'essayer plus tard.";
        $response['link']="";

        $donationId = decrypt($request->encryptCode, Config::get('Constant.ENC_KEY'));
        $response['has_error']=0;
        $response['msg']="Statut changé avec succès.";
        $donation = Donation::find($donationId);
        $st=$request->st;
        $donation->donation_status = $st;
        $donation->admin_modified = 'yes';
        if($donation->save()){

            /////////working on double ////////////////
            if($st == 'completed')
            {
                $getDonation = Donation::where('id', $donationId)->first();
                $getProject = Project::where('id', $getDonation['project_id'])->first();

                $updatePayment = Payment::find($getProject['id']);
                $updatePayment->payment_status = 'completed';

                if($getProject['is_double'] == '1')
                {
                    $checkIfDoubleExistCount = DoubleDonation::where('donation_id',$donationId)->count();

                    if($checkIfDoubleExistCount == 0)
                    {
                        DonationHelper::doDouble($getDonation['project_id'], $donationId, $getDonation['amount_to_project'], 'init');
                    }
                    else
                    {
                        // if($checkIfDoubleExistCount == 1)
                        // {
                        //     //old code before disscussion
                        //     $getDbl = DoubleDonation::where('donation_id', $donationId)->first();
                        //     $modDbl = DoubleDonation::find($getDbl['id']);
                        //     $modDbl->double_donation_status = 'init';
                        //     $modDbl->save();

                        // }
                        // else
                        // {
                        //     if($checkIfDoubleExistCount > 1)
                        //     {
                                DoubleDonation::where('donation_id', $donationId)->delete();
                                DonationHelper::doDouble($getDonation['project_id'], $donationId, $getDonation['amount_to_project'], 'init');
                        //     }
                        // }
                    }
                }
            }
            else
            {
                $getDonation = Donation::where('id', $donationId)->first();
                $getProject = Project::where('id', $getDonation['project_id'])->first();

                $updatePayment = Payment::find($getProject['id']);
                $updatePayment->payment_status = $st;

                if($getProject['is_double'] == '1')
                {
                    DoubleDonation::where('donation_id', $donationId)->delete();
                    // DonationHelper::doDouble($getDonation['project_id'], $donationId, $getDonation['amount_to_project'], 'canceled');
                }
            }


            //////////////////////////////////////////

            $response['has_error']=0;
            $response['msg']="Statut changé avec succès.";
            $statuslink= route('admin.project-management.donation-status',  encrypt($donationId, Config::get('Constant.ENC_KEY')));
            $donationReminder=route('admin.donation-management.donation-reminder',  encrypt($donationId, Config::get('Constant.ENC_KEY')) );
            $steps=array('init'=>"<span style='color:blue' class='changeStatus' data-redirect-url=".$statuslink." id='".$donationId."-stat'>En cours</span>",
                'completed'=>"<span style='color:green' class='changeStatus' data-redirect-url=".$statuslink." id='".$donationId."-stat'>Validé</span>",
                'canceled'=>"<span style='color:orange' class='changeStatus' data-redirect-url=".$statuslink." id='".$donationId."-stat'>Cancelled</span>",
                'fail'=>"<span style='color:red' class='changeStatus' data-redirect-url=".$statuslink." id='".$donationId."-stat'>Failed</span>&nbsp;&nbsp;<a class='trigger-reminder' href='".$donationReminder."'><i class='fa fa-envelope'></i></a>");
                
            $response['link']=$steps[$st];
        }
        return $response;

    }
    public function DonationAddManual(Request $request)
    {
        // $donationId = decrypt($request->encryptCode, Config::get('Constant.ENC_KEY'));
        // $donationDtata= Donation::where('id',$donationId)->first();
        // $this->data['donation']=$donationDtata;
        $projectId = decrypt($request->encryptCode, Config::get('Constant.ENC_KEY'));
        $project=Project::where('id',$projectId)->first();
        $this->data['project']=$project;
        $this->data['panel_title']='Pour le projet '.$project->project_title;
        $prefields = explode(',', $project['predefine_amount']);
        $this->data['prefields'] = $prefields;
        $initamount = $prefields[0];
        $this->data['initamount'] = $initamount;
        $this->data['initdouble'] = $initamount * 2;
        if(round(((8/100)*$initamount),2) > 5)
        {
            $init_ngo = round(((8/100)*$initamount),2);
        }
        else
        {
            $init_ngo = 5;
        }
        $this->data['init_ngo']=$init_ngo;
        $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

        $this->data['countries'] = $countries;
        $percent_particular = round($initamount - ($initamount*(66/100)),2);
                 
        $this->data['percent_particular'] = str_replace('.', ',', $percent_particular);

        $percent_enterprise = round($initamount - ($initamount*(60/100)),2);
         
        $this->data['percent_enterprise'] = str_replace('.', ',', $percent_enterprise);
        return view('admin.projectmanagement.donation-add',$this->data);
    }
    public function DonationAddManualSave(Request $request)
    {
        // dd($request);
            if($request->doner_type == "particular")
            {
                $contact_email = $request->contact_email;
                $fullname = $request->contact_first_name.' '.$request->contact_last_name;
                $contact_first_name = $request->contact_first_name;
                $contact_last_name = $request->contact_last_name;
                $contact_address = $request->contact_address;
                $contact_additional_address = $request->contact_additional_address;
                $contact_postalcode = $request->contact_postalcode;
                $contact_country = $request->contact_country;
                $contact_village = $request->contact_village;

                $other_reason = '';
                if($request->other_reason)
                {
                    $other_reason = $request->other_reason;
                }

                $is_newsletter = '0';
                if($request->is_newsletter)
                {
                    $is_newsletter = '1';
                }

                $want_refund = 'yes';
                if($request->want_refund)
                {
                    $want_refund = 'no';
                }

                $is_internet = '0';
                if($request->is_internet)
                {
                    $is_internet = '1';
                }

                $is_brochure = '0';
                if($request->is_brochure)
                {
                    $is_brochure = '1';
                }

                $is_email = '0';
                if($request->is_email)
                {
                    $is_email = '1';
                }

                $is_ad = '0';
                if($request->is_ad)
                {
                    $is_ad = '1';
                }

                $is_others = '0';
                if($request->is_others)
                {
                    $is_others = '1';
                }

                $company_name = '';
            }
            else
            {
                $contact_email = $request->contact_email_en;
                $fullname = $request->contact_first_name_en.' '.$request->contact_last_name_en;

                $contact_first_name = $request->contact_first_name_en;
                $contact_last_name = $request->contact_last_name_en;
                $contact_address = $request->contact_address_en;
                $contact_additional_address = $request->contact_additional_address_en;
                $contact_postalcode = $request->contact_postalcode_en;
                $contact_country = $request->contact_country_en;
                $contact_village = $request->contact_village_en;

                $other_reason = '';
                if($request->other_reason_en)
                {
                    $other_reason = $request->other_reason_en;
                }

                $is_newsletter = '0';
                if($request->is_newsletter_en)
                {
                    $is_newsletter = '1';
                }

                $want_refund = 'yes';
                if($request->want_refund)
                {
                    $want_refund = 'no';
                }

                $is_internet = '0';
                if($request->is_internet_en)
                {
                    $is_internet = '1';
                }

                $is_brochure = '0';
                if($request->is_brochure_en)
                {
                    $is_brochure = '1';
                }

                $is_email = '0';
                if($request->is_email_en)
                {
                    $is_email = '1';
                }

                $is_ad = '0';
                if($request->is_ad_en)
                {
                    $is_ad = '1';
                }

                $is_others = '0';
                if($request->is_others_en)
                {
                    $is_others = '1';
                }
                
                $company_name = $request->company_name;
            }
            $amount = $request->amount;
            $amount_to_project = $request->amount_to_project;
            $amount_to_ngo = $request->amount_to_ngo;
            $project_id = $request->project_id;
            $donation_method=$request->donation_method;

            if($request->is_anonymous and $request->is_anonymous=='yes')
            {
                $is_anonymous = 'yes';
            }
            else
            {
                $is_anonymous = 'no';
            }

            $donation_date = Carbon::createFromFormat('d-m-Y', $request->donation_date)->format('Y-m-d H:i:s');

            $user_id = 0;

            /**donation count**/

                $thisYear = date('Y');

                $isInDonationCount = DonationCount::where('year',$thisYear)->count();

                $thisCount = 0;

                if($isInDonationCount == 0)
                {
                    $thisCount = 1;

                    $add_count = new DonationCount;

                    $add_count->year = $thisYear;
                    $add_count->last_count = $thisCount;

                    $add_count->save();
                }
                else
                {
                    $inDonationCount = DonationCount::where('year',$thisYear)->first();

                    $thisCount = $inDonationCount['last_count']+1;

                    $updateDonationCount = DonationCount::find($inDonationCount['id']);
                    $updateDonationCount->last_count = $thisCount;
                    $updateDonationCount->save();
                }

                /******************/


            $donation_status=$request->donation_status;
            $donation_type=$request->donation_type;
            $doner_type = $request->doner_type;
            $donation = new Donation;
            $donation->project_id = $project_id;
            $donation->user_id = $user_id;
            $donation->amount = $amount;
            $donation->amount_to_project = $amount_to_project;
            $donation->amount_to_ngo = $amount_to_ngo;
            $donation->donation_status = $donation_status;
            $donation->donation_type = $donation_type;
            $donation->doner_type = $doner_type;
            $donation->company_name = $company_name;
            $donation->is_newsletter = $is_newsletter;
            $donation->contact_email = $contact_email;
            $donation->contact_first_name = $contact_first_name;
            $donation->contact_last_name = $contact_last_name;
            $donation->contact_address = $contact_address;
            $donation->contact_additional_address = $contact_additional_address;
            $donation->contact_postalcode = $contact_postalcode;
            $donation->contact_village = $contact_village;
            $donation->contact_country = $contact_country;
            $donation->is_others = $is_others;
            $donation->is_internet = $is_internet;
            $donation->is_brochure = $is_brochure;
            $donation->is_email = $is_email;
            $donation->is_ad = $is_ad;
            $donation->donation_method=$request->donation_method;
            $donation->is_anonymous = $is_anonymous;
            $donation->want_refund = $want_refund;
            $donation->payment_date = $donation_date;
            $donation->updated_at = $donation_date;
            $donation->other_reason = $other_reason;
            $donation->donation_count_no = $thisCount;
            $donation->save();


            /**********for double ***********/

                if($donation_status == 'completed')
                {
                    $double_status = 'init';
                }
                else
                {
                    $double_status = 'canceled';
                }

                DonationHelper::doDouble($project_id, $donation->id, $amount_to_project, $double_status);

                /*$getProject = Project::where('id', $project_id)->first();

                if($getProject['is_double'] == '1')
                {
                    $till_total = 0;
                    $getDirectDonations = Donation::where('project_id', $project_id)->where('donation_status', 'completed')->get();
                    foreach($getDirectDonations as $getDirectDonation)
                    {
                        $till_total += $getDirectDonation->amount_to_project;
                    }

                    $getDirectDoubles = DoubleDonation::where('project_id', $project_id)->where('double_donation_status', '!=', 'canceled')->get();
                    $dbl_fund = 0;
                    foreach($getDirectDoubles as $getDirectDouble)
                    {
                        $till_total += $getDirectDouble->double_amount;
                        $dbl_fund += $getDirectDouble->double_amount;
                    }

                    $double = $dbl_fund*2; //40*2 = 80

                    if($getProject['double_amount_limit'] > $double)
                    {
                        $remain_donation = $getProject['double_amount_limit'] - $double; //110-80=30

                        $remain_donation_for_double = round($remain_donation/2, 2); //30/2 = 15

                        if($donation_status == 'completed')
                        {
                            $double_status = 'init';
                        }
                        else
                        {
                            $double_status = 'canceled';
                        }

                        if($remain_donation_for_double > $amount_to_project)
                        {
                            $doublerow = new DoubleDonation;
                            $doublerow->project_id = $project_id;
                            $doublerow->donation_id = $donation->id;
                            // $double->double_amount = $getProject['double_amount_limit'];
                            $doublerow->double_amount = $amount_to_project;
                            $doublerow->double_donation_status = $double_status;
                            $doublerow->save();
                        }
                        else
                        {
                            $doublerow = new DoubleDonation;
                            $doublerow->project_id = $project_id;
                            $doublerow->donation_id = $donation->id;
                            // $double->double_amount = $getProject['double_amount_limit'];
                            $doublerow->double_amount = $remain_donation_for_double;
                            $doublerow->double_donation_status = $double_status;
                            $doublerow->save();
                        }
                    }

                }*/
                /***********************************/

            return redirect()->back()
                ->with('success','Don ajouté avec succès.')
                ->with('alert-class', 'alert-danger');

        die;
    }
    public function DonationListTable(Request $request,$encryptCode)
    {
        $projectId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $data = Donation::where('project_id',$projectId)->orderBy('created_at', 'desc')->get();

        $finalResponse= Datatables::of($data)
            ->addColumn('fullname', function ($model){
                if($model['doner_type'] == 'Particular')
                {
                    return $model['contact_first_name'].' '.$model['contact_last_name'];
                }
                else
                {
                    return $model['company_name'];
                }
                
            })
            ->addColumn('amount', function ($model){
                return $model->amount.'€';
            })
            ->addColumn('amount_to_project', function ($model){
                // $a=($model->donation_type=='double'?$model->amount_to_project*2:$model->amount_to_project);
                $a=$model->amount_to_project;
                return $a.'€';
            })
            ->addColumn('amount_to_ngo', function ($model){
                return $model->amount_to_ngo.'€';
            })
            ->addColumn('donation_status', function ($model){
                $statuslink= route('admin.project-management.donation-status',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                $donationReminder=route('admin.donation-management.donation-reminder',  encrypt($model->id, Config::get('Constant.ENC_KEY')) );
                $steps=array('init'=>"<span style='color:blue' class='changeStatus' data-redirect-url=".$statuslink." id='".$model->id."-stat'>En cours</span>",
                    'completed'=>"<span style='color:green' class='changeStatus' data-redirect-url=".$statuslink." id='".$model->id."-stat'>Validé</span>",
                    'canceled'=>"<span style='color:orange' class='changeStatus' data-redirect-url=".$statuslink." id='".$model->id."-stat'>Cancelled</span>",
                    'fail'=>"<span style='color:red' class='changeStatus' data-redirect-url=".$statuslink." id='".$model->id."-stat'>Failed</span>&nbsp;&nbsp;<a class='trigger-reminder' href='".$donationReminder."'><i class='fa fa-envelope'></i></a>");
                return $steps[$model->donation_status];
            })
            ->addColumn('donation_type', function ($model){
               $types=array('self'=>'Non','double'=>'Oui');
                return $types[$model['donation_type']];
            })
            ->addColumn('doner_type', function ($model){
                $types=array('Particular'=>'Particulier','Enterprise'=>'Entreprise');
                return $types[$model['doner_type']];
            })
            ->addColumn('is_refund', function ($model){
               $types=array('no'=>'Oui','yes'=>'Non'); //according to column name
                return $types[$model['want_refund']];
            })
            ->addColumn('is_anonym', function ($model){
               $types=array('no'=>'Non','yes'=>'Oui');
                return $types[$model['is_anonymous']];
            })
            ->addColumn('created_time', function ($model){
               //  date_default_timezone_set("Europe/Paris");
               // return $timestamp=date('d/m/Y H:i:s',strtotime($model->created_at));

                $raw = $model->created_at.'+08';
                $date = substr($raw,0,19);
                $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
                $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
                $localTime = date('d/m/Y H:i:s',$timestamp);
                return '<span style="display:none;">'.$timestamp.'</span>'.$localTime;
               
            })
            ->addColumn('action', function ($model) {
                $viewlink =  route('admin.project-management.donation-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

                $certificate = route('cerificate-download',  encrypt($model->id, Config::get('Constant.ENC_KEY')));


                $actions='<div class="btn-group btn-group-sm ">';
                $actions .='<a href="' . $viewlink . '" class="btn" id="" title="Voir le détail du paiement"><i class="fas fa-eye"></i></a>';

                if($model->donation_status == 'completed')
                {
                    $actions .='<a href="'.$certificate.'" target="_blank" class="btn" id="" title="télécharger le détail des dons"><i class="fa fa-cloud-download"></i></a>';
                }

                $actions .='</div>';
                return $actions;
            })
        ->rawColumns(['fullname','amount', 'amount_to_project', 'amount_to_ngo', 'donation_status','donation_type','doner_type','is_anonym','is_refund','created_time','action'])
        ->make(true);
        
        return $finalResponse;
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
    public function DonationDetails(Request $request,$encryptCode)
    {
        $donationId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));
        $donation = Donation::where('id',$donationId)->first();
        $thisProject = Project::where('id',$donation->project_id)->first();
        $user='';
        /*if($donation->user_id>0)
        {
            $getUser = User::where('id',$donation->user_id)->first();
            $name = $getUser['first_name'].' '.$getUser['last_name'];     
        }
        else
        {
            $name='Anonymous';
        }*/
        $stats=array('init'=>"<span style='color:blue'>En cours</span>",'completed'=>"<span style='color:green'>Validé</span>",'canceled'=>"<span style='color:orange'>Cancelled</span>",'fail'=>"<span style='color:red'>Failed</span>");

        $payment = Payment::where('donation_id', $donation->id)->first();
        if(!is_null($payment))
        {
            $stripe_id = $payment['p_id'];
        }
        else
        {
            $stripe_id = '';
        }

        if($donation['donation_type'] == 'self')
        {
            $donation_type = 'Non abondé';
        }
        else
        {
            $get_double = DoubleDonation::where('donation_id', $donationId)->where('double_donation_status', '!=', 'canceled')->first();

            // $donation_type = $get_double['double_amount'];
            if(!is_null($get_double))
            {
                $donation_type = 'Double';
            }
            else
            {
                $donation_type = 'Non abondé';
            }
        }
        
        $this->data['donation'] = $donation;
        $this->data['project'] = $thisProject;
        // $this->data['user']=$name;
        $this->data['stripe_id'] = $stripe_id;
        $this->data['stats']=$stats[$donation->donation_status];
        $this->data['page_title']="Donation Details";
        $this->data['panel_title']="Donation Details";
        $this->data['donation_type']=$donation_type;
        // dd($this->data);
        return view('admin.projectmanagement.donation-detail',$this->data);
    }
    public function DonationExcelDownload(Request $request,$encryptCode)
    {
        $projectId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));
        $title = Project::where('id',$projectId)->pluck('project_title')->toArray();
        $titleTxt=$title['0'];
        $titleTxt=strtolower($titleTxt);
        $titleTxt=str_replace(' ', '-', $titleTxt);
        $titleTxt=$titleTxt.'-'.date('d-M-Y');
        $data = Donation::where('project_id',$projectId)->get()->toArray();
        $label=array('Utilisatrice','Montante','Montant du projet','Montant ONG','Statut du don','Type de don','Type de donneur','Nom de l\'entreprise','Inscrit à la newsletter','Email du contact','Nom et prénom','Adresse de contact','Adresse supplémentaire de contact','Code postal','Village','Pays','Comment avez-vous connu le Fonds ?');
        $html=implode(',', $label).PHP_EOL;
        $temp=array_map(function($v) use ($html) {
            $v['user_id']=$v['contact_first_name'].' '.$v['contact_last_name'];
            $v['amount']=$v['amount'].'€';
            $v['amount_to_project']=$v['amount_to_project'].'€';
            $v['amount_to_ngo']=$v['amount_to_ngo'].'€';
            $steps=array('init'=>"En cours",'completed'=>"Validé",'canceled'=>"Cancelled",'fail'=>"Failed");
            $v['donation_status']=$steps[$v['donation_status']];
            $v['is_newsletter']=($v['is_newsletter'] ? 'Yes': 'No');
            $v['contact_first_name']=ucfirst($v['contact_first_name']).' '.ucfirst($v['contact_last_name']);

            $stats=array('is_internet'=>$v['is_internet'],'is_brochure'=>$v['is_brochure'],'is_email'=>$v['is_email'],'is_ad'=>$v['is_ad'],'is_others'=>$v['is_others']);
            $st=array('is_internet'=>'Internet','is_brochure'=>'Bouche à oreille','is_email'=>'Emailing','is_ad'=>'Publicité','is_others'=>'Autre');
            $k=array_search('1',$stats);
            if($k>0)
            {
                $v['is_others']=$st[$k];
            }
            else
            {
                $v['is_others']='';
            }
            

            unset($v['id']);
            unset($v['project_id']);
            unset($v['is_internet']);
            unset($v['is_brochure']);
            unset($v['is_email']);
            unset($v['is_ad']);
            unset($v['contact_last_name']);
            unset($v['created_at']);
            unset($v['updated_at']);
            unset($v['deleted_at']);

            $v=array_values($v);
            return implode(',',$v);
        },$data);
        $html=$html.implode(PHP_EOL, $temp);
        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename='.$titleTxt.'.csv');
        echo $html;
    }
   /**************************use***************************/
    # BookingController
    # Function name : ProjectDetail
    # Author        :
    # Created Date  : 26-07-2022
    # Purpose       : Display project detail table
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function ProjectDetail(Request $request, $encryptCode){
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['page_title']="Détail de la project";
        $this->data['panel_title']="Détail de la project";
        $projectId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $thisProject = Project::where('id',$projectId)->first();


        $getUser = User::where('id',$thisProject['user_id'])->first();
        $fullname = $getUser['first_name'].' '.$getUser['last_name'];

        $proj_status = '';
        $proj_status= $this->prstats[$thisProject['project_status']];
        

        $double_status = '';

        if($thisProject['is_double'] == '1')
        {
                    $double_status = '<i class="fas fa-hand-holding-usd"></i> Oui ('.$thisProject['double_amount_limit'].'€)';
        }
        else
        {
                    $double_status = '<i class="fas fa-hand-holding-usd"></i> Non';
        }

        $sum = Donation::where('donation_status', 'completed')->where('project_id',$projectId)->sum('amount');

        
        if($thisProject['domain_id'] != '')
        {
            $domains = explode(',',$thisProject['domain_id']);
            $domain_name='';
            $cnt = 1;
            $domain_size = sizeof($domains);
            foreach($domains as $domain)
            {
                $thisDomain = Domain::where('id',$domain)->first();
                $domain_name.=$thisDomain['domain_name'];
                if($cnt != $domain_size)
                {
                    $domain_name.= ',';
                }
                $cnt++;
            }
        }
        else
        {
            $domain_name = '';
        }
        


        $galleries = Gallery::where('project_id',$projectId)->where('gal_status', '1')->get();

        $this->data['thisProject'] = $thisProject;
        $this->data['fullname'] = $fullname;
        $this->data['proj_status'] = $proj_status;
        $this->data['double_status'] = $double_status;
        $this->data['sum'] = $sum;
        $this->data['domain_name'] = $domain_name;
        $this->data['galleries'] = $galleries;

        // dd($data);

        return view('admin.projectmanagement.project-detail',$this->data);

    }


    /************************use*****************************/
    # ProjectController
    # Function name : ProjectStatusChange
    # Author        :
    # Created Date  : 27-07-2022
    # Purpose       : Change project status form
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function ProjectStatusChange(Request $request,$encryptCode){
        //echo Auth::id(); die;
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Projets";
        $this->data['panel_title']="Projets";

        //dd($this->data);

        $projectId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));
        $thisProject = Project::where('id',$projectId)->first();

        $getUser = User::where('id',$thisProject['user_id'])->first();
        $fullname = $getUser['first_name'].' '.$getUser['last_name'];

        $proj_status = '';
        $proj_status=$this->prstats[$thisProject['project_status']];
        // if($thisProject['project_status'] == 'pending')
        // {
        //    $proj_status = 'En attente';
        // }
        // else
        // {
        //     if($thisProject['project_status'] == 'active')
        //     {
        //         $proj_status = 'En cours';
        //     }
        //     else
        //     {
        //         if($thisProject['project_status'] == 'inactive')
        //         {
        //             $proj_status = 'Inactif';
        //         }
        //         else
        //         {
        //             if($thisProject['project_status'] == 'rejected')
        //             {
        //                 $proj_status = 'Rejeté';
        //             }
        //             else
        //             {
        //                 if($thisProject['project_status'] == 'close')
        //                 {
        //                     $proj_status = 'Rejeté';
        //                 }
        //             }
        //         }
        //     }
        // }

        $double_status = '';

        if($thisProject['is_double'] == '1')
        {
                    $double_status = '<i class="fas fa-hand-holding-usd"></i> Oui ('.$thisProject['double_amount_limit'].'€)';
        }
        else
        {
                    $double_status = '<i class="fas fa-hand-holding-usd"></i> Non';
        }

        $sum = Donation::where('donation_status', 'completed')->where('project_id',$projectId)->sum('amount');

        
        $domain_name='';

        if($thisProject['domain_id']!='')
        {
            $domains = explode(',',$thisProject['domain_id']);
            $cnt = 1;
            $domain_size = sizeof($domains);
            if($domain_size > 0)
            {
                foreach($domains as $domain)
                {
                    $thisDomain = Domain::where('id',$domain)->first();
                    $domain_name.=$thisDomain['domain_name'];
                    if($cnt != $domain_size)
                    {
                        $domain_name.= ',';
                    }
                    $cnt++;
                }
            }
        }
        
        

        $this->data['thisProject'] = $thisProject;
        $this->data['fullname'] = $fullname;
        $this->data['proj_status'] = $proj_status;
        $this->data['double_status'] = $double_status;
        $this->data['sum'] = $sum;
        $this->data['domain_name'] = $domain_name;

        //$this->data['statuses'] = array('pending'=>'En attente', 'active'=>'En cours', 'inactive'=>'Inactif', 'rejected'=>'Rejeté', 'close'=>'Terminé');
        $this->data['statuses']=$this->prstats;


        $this->data['projectId']=$encryptCode;
        $this->data['thisProject']=$thisProject;
        if($thisProject->number_of_goal==1)
        {
            $maxDouble=$thisProject['single_goal_amount'];
        }
        else
        {
            $maxDouble=$thisProject['third_goal_amount'];
        }
        $this->data['maxDouble']=$maxDouble;
        
        // dd($this->data);
        return view('admin.projectmanagement.project-status-change',$this->data);
    }


    /**************************use***************************/
    # ProjectController
    # Function name : ProjectStatusUpdate
    # Author        :
    # Created Date  : 27-07-2022
    # Purpose       : project status save
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function ProjectStatusUpdate(Request $request){

        try {

            // $projectId = $request->project_id;

            $validator = Validator::make($request->all(), [
                        'project_status' => 'required',
                        'is_double'=> 'required',
                        'double_amount_limit'=> 'required',
                    ]);
                    
            if ($validator->fails()) { 
                return redirect('securepanel/project-management/project-status-change/'.$request->project_id)
                                    ->withErrors($validator)
                                    ->withInput();
            }
            else
            {

                $projectId = decrypt($request->project_id, Config::get('Constant.ENC_KEY'));

                $isDonationPendingCount = Donation::where('project_id', $projectId)->where('donation_status','init')->count();

                if($isDonationPendingCount == 0)
                {
                    $thisProject = Project::where('id', $projectId)->first();
                    $old_status = $thisProject['project_status'];
                    
                    $thisProject = Project::find($projectId);
                    $thisProject->project_status = $request->project_status;
                    $thisProject->is_double = $request->is_double;
                    $thisProject->double_amount_limit = $request->double_amount_limit;

                    // $thisProject->save();
                    if($thisProject->save())
                    {
                        if($old_status != $thisProject->project_status)
                        {
                            if($thisProject->project_status == 'succeed')
                            {
                                // dd('hello');


                                //mail of certificates
                                $getThisProject = Project::where('id', $projectId)->first();
                                $this->data['company_name'] = $getThisProject['associate_name'];
                                $this->data['company_logo'] = $getThisProject['associate_logo'];
                                $this->data['company_address'] = $getThisProject['associate_address'];
                                $this->data['company_email'] = $getThisProject['associate_email'];
                                $this->data['company_phone'] = $getThisProject['associate_phone'];
                                $this->data['projectTitle'] = $getThisProject['project_title'];

                                $getThisDonations = Donation::where('project_id', $projectId)->where('donation_status', 'completed')->get();
                                // dd($getThisDonations);
                                
                                foreach($getThisDonations as $getThisDonation)
                                {
                                    echo $getThisDonation->id;
                                    // dd('hello');
                                    $this->data['totalAmount'] = $getThisDonation->amount;
                                    $this->data['projectAmount'] = $getThisDonation->amount_to_project;
                                    $this->data['ngoAmount'] = $getThisDonation->amount_to_ngo;
                                    // $this->data['payment_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('d/m/Y');

                                    if($getThisDonation->payment_date!=NULL)
                                    {
                                        $this->data['payment_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('d/m/Y');
                                    }
                                    else
                                    {
                                        $this->data['payment_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->created_at)->format('d/m/Y');
                                    }

                                    $this->data['company_name'] = $getThisProject->associate_name;
                                    $this->data['company_logo'] = $getThisProject->associate_logo;
                                    $this->data['company_address'] = $getThisProject->associate_address;
                                    $this->data['company_email'] = $getThisProject->associate_email;
                                    $this->data['company_phone'] = $getThisProject->associate_phone;
                                    $this->data['getThisDonation'] = $getThisDonation;

                                    // $donation_date =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('d/m/Y');
                                    // $donation_year =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('Y');
                                    // $next_donation_year =  date('Y', strtotime("+12 months $donation_year"));

                                    if($getThisDonation->payment_date!=NULL)
                                    {
                                        $donation_date =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('d/m/Y');
                                    }
                                    else
                                    {
                                        $donation_date =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->created_at)->format('d/m/Y');
                                    }

                                    if($getThisDonation->payment_date!=NULL)
                                    {
                                        $donation_year =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('Y');
                                    }
                                    else
                                    {
                                        $donation_year =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->created_at)->format('Y');
                                    }

                                    if($getThisDonation->payment_date!=NULL)
                                    {
                                        $next_donation_date =  date('Y-m-d H:i:s', strtotime("+12 months $getThisDonation->payment_date"));

                                    }
                                    else
                                    {
                                        $next_donation_date =  date('Y-m-d H:i:s', strtotime("+12 months $getThisDonation->created_at"));
                                    }

                                    $next_donation_year = Carbon::createFromFormat('Y-m-d H:i:s', $next_donation_date)->format('Y');

                                    
                                    $signature_date = date('d/m/Y');
                                    $this->data['signature_date'] = $signature_date;

                                    if($getThisDonation->doner_type == 'Particular')
                                    {
                                        $deducted_amount = ($getThisDonation->amount_to_project-($getThisDonation->amount_to_project *  (66/100)));
                                        $tax = $getThisDonation->amount_to_project - $deducted_amount;
                                        $tax_percent = 66;
                                    }
                                    else
                                    {
                                        $deducted_amount = $getThisDonation->amount_to_project - ($getThisDonation->amount_to_project *  (60/100));
                                        $tax = $getThisDonation->amount_to_project - $deducted_amount;
                                        $tax_percent = 60;
                                    }

                                    $this->data['deducted_amount'] = $deducted_amount;
                                    $this->data['tax'] = $tax;
                                    $this->data['tax_percent'] = $tax_percent;

                                    $this->data['projectTitle'] = $getThisProject->project_title;


                                    /***********/
                                    // $numlength = strlen((string)$getThisDonation->id);
                                    $numlength = strlen((string)$getThisDonation->donation_count_no);
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
                                    }

                                    $extention = $add_width;

                                    // $serial = 'FFD'.Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('Y').'-02-'.$extention.''.$getThisDonation->donation_count_no;

                                    if($getThisDonation['payment_date']!=null)
                                    {
                                        $serial = 'FFD'.Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('Y').'-02-'.$extention.''.$getThisDonation['donation_count_no'];
                                    }
                                    else
                                    {
                                        $serial = 'FFD'.Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->created_at)->format('Y').'-02-'.$extention.''.$getThisDonation['donation_count_no'];
                                    }

                                    $this->data['invoice_no'] = $serial;
                                    /**********/



                              
                                    $pdf = PDF::loadView('pdf/certificate', $this->data);
                                    // dd($this->data);
                                    // return $pdf->download('aaa.pdf');
                                    
                                    // // dd($pdf);
                                    // dd($getThisProject['associate_email']);

                                    $fromUser = Config::get('yourdata.admin_email_from');
                                    $toUser = $getThisDonation->contact_email;
                                    // $toUser = 'samarpita@matrixnmedia.com';
                                    // $subject = 'Attestation fiscale de don de projet '.$getThisProject['project_title'];
                                    $subject = 'Fraternité pour Demain - votre reçu fiscal';
                                    $replyTo = Config::get('yourdata.reply_to_donation_payment');
                                    $mailData = array('project_title' => $getThisProject['project_title'],'timedate'=>$donation_date,'donation_amount'=>$getThisDonation->amount_to_project, 'donation_year'=>$donation_year, 'next_donation_year'=>$next_donation_year);

                                    dd($mailData);

                                    Mail::send('email.certificate_send', $mailData, function ($sent) use ($toUser, $fromUser, $subject, $pdf, $replyTo) {
                                        $sent->from($fromUser)->subject($subject);
                                        $sent->replyTo($replyTo);
                                        $sent->to($toUser);
                                        $sent->attachData($pdf->output(), "Reçu.pdf");
                                    });/**/

                                    /****update tax sent date****/
                                    $updateTaxSent = Donation::find($getThisDonation['id']);
                                    $thisDate = date('Y-m-d H:i:s');
                                    $updateTaxSent->tax_certificate_sent_at = $thisDate;
                                    $updateTaxSent->save();
                              
                                   
                                }
                                // die;
                            }
                            else
                            {
                                if($thisProject->project_status == 'failed')
                                {

                                    //mail of certificates
                                    $getThisProject = Project::where('id', $projectId)->first();
                                    $this->data['company_name'] = $getThisProject['associate_name'];
                                    $this->data['company_logo'] = $getThisProject['associate_logo'];
                                    $this->data['company_address'] = $getThisProject['associate_address'];
                                    $this->data['company_email'] = $getThisProject['associate_email'];
                                    $this->data['company_phone'] = $getThisProject['associate_phone'];
                                    $this->data['projectTitle'] = $getThisProject['project_title'];

                                    $getThisDonations = Donation::where('project_id', $projectId)->where('donation_status', 'completed')->get();
                                    // dd($getThisDonations);

                                    foreach($getThisDonations as $getThisDonation)
                                    {
                                        if($getThisDonation->want_refund == 'yes')
                                        {
                                            // echo 'yes';
                                            $donation_date =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('d/m/Y');
                                            $donation_year =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('Y');
                                            $next_donation_year =  date('Y', strtotime("+12 months $donation_year"));

                                            $fromUser = Config::get('yourdata.admin_email_from');
                                            $toUser = $getThisDonation->contact_email;
                                            // $toUser = 'samarpita@matrixnmedia.com';
                                            // $subject = 'Attestation fiscale de don de projet '.$getThisProject['project_title'];
                                            $subject = 'Fraternité pour Demain - remboursement de votre don';
                                            $replyTo = Config::get('yourdata.reply_to_donation_payment');
                                            $mailData = array('project_title' => $getThisProject['project_title'],'timedate'=>$donation_date, 'donation_year'=>$donation_year, 'next_donation_year'=>$next_donation_year);
                                            // dd($mailData);

                                            Mail::send('email.cancel_send_without_certificate', $mailData, function ($sent) use ($toUser, $fromUser, $subject, $replyTo) {
                                                $sent->from($fromUser)->subject($subject);
                                                $sent->replyTo($replyTo);
                                                $sent->to($toUser);
                                            });

                                            
                                            // dd($getThisDonation->contact_email);


                                        }
                                        else
                                        {
                                            //mail of certificates
                                            $getThisProject = Project::where('id', $projectId)->first();
                                            $this->data['company_name'] = $getThisProject['associate_name'];
                                            $this->data['company_logo'] = $getThisProject['associate_logo'];
                                            $this->data['company_address'] = $getThisProject['associate_address'];
                                            $this->data['company_email'] = $getThisProject['associate_email'];
                                            $this->data['company_phone'] = $getThisProject['associate_phone'];
                                            $this->data['projectTitle'] = $getThisProject['project_title'];

                                            $getThisDonations = Donation::where('project_id', $projectId)->where('donation_status', 'completed')->get();
                                            // dd($getThisDonations);

                                            foreach($getThisDonations as $getThisDonation)
                                            {
                                                // dd('hello');
                                                $this->data['totalAmount'] = $getThisDonation->amount;
                                                $this->data['projectAmount'] = $getThisDonation->amount_to_project;
                                                $this->data['ngoAmount'] = $getThisDonation->amount_to_ngo;
                                                // $this->data['payment_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('d/m/Y');

                                                if($getThisDonation->payment_date!=NULL)
                                                {
                                                    $this->data['payment_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('d/m/Y');
                                                }
                                                else
                                                {
                                                    $this->data['payment_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->created_at)->format('d/m/Y');
                                                }

                                                $this->data['company_name'] = $getThisProject->associate_name;
                                                $this->data['company_logo'] = $getThisProject->associate_logo;
                                                $this->data['company_address'] = $getThisProject->associate_address;
                                                $this->data['company_email'] = $getThisProject->associate_email;
                                                $this->data['company_phone'] = $getThisProject->associate_phone;
                                                $this->data['getThisDonation'] = $getThisDonation;

                                                $donation_date =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('d/m/Y');
                                                $donation_year =  Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('Y');
                                                $next_donation_year =  date('Y', strtotime("+12 months $donation_year"));

                                                $signature_date = date('d/m/Y');
                                                $this->data['signature_date'] = $signature_date;

                                                if($getThisDonation->doner_type == 'Particular')
                                                {
                                                    $deducted_amount = ($getThisDonation->amount_to_project-($getThisDonation->amount_to_project *  (66/100)));
                                                    $tax = $getThisDonation->amount_to_project - $deducted_amount;
                                                    $tax_percent = 66;
                                                }
                                                else
                                                {
                                                    $deducted_amount = $getThisDonation->amount_to_project - ($getThisDonation->amount_to_project *  (60/100));
                                                    $tax = $getThisDonation->amount_to_project - $deducted_amount;
                                                    $tax_percent = 60;
                                                }

                                                $this->data['deducted_amount'] = $deducted_amount;
                                                $this->data['tax'] = $tax;
                                                $this->data['tax_percent'] = $tax_percent;

                                                $this->data['projectTitle'] = $getThisProject->project_title;


                                                /***********/
                                                // $numlength = strlen((string)$getThisDonation->id);
                                                $numlength = strlen((string)$getThisDonation->donation_count_no);
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

                                                // $serial = 'FFD'.Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('Y').'-02-'.$extention.''.$getThisDonation->donation_count_no;

                                                if($getThisDonation['payment_date']!=null)
                                                {
                                                    $serial = 'FFD'.Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->payment_date)->format('Y').'-02-'.$extention.''.$getThisDonation['donation_count_no'];
                                                }
                                                else
                                                {
                                                    $serial = 'FFD'.Carbon::createFromFormat('Y-m-d H:i:s', $getThisDonation->created_at)->format('Y').'-02-'.$extention.''.$getThisDonation['donation_count_no'];
                                                }

                                                $this->data['invoice_no'] = $serial;
                                                /**********/



                                          
                                                $pdf = PDF::loadView('pdf/certificate', $this->data);
                                                // dd($this->data);
                                                // return $pdf->download('aaa.pdf');
                                                
                                                // // dd($pdf);
                                                // dd($getThisProject['associate_email']);

                                                $fromUser = Config::get('yourdata.admin_email_from');
                                                $toUser = $getThisDonation->contact_email;
                                                // $toUser = 'samarpita@matrixnmedia.com';
                                                // $subject = 'Attestation fiscale de don de projet '.$getThisProject['project_title'];
                                                $subject = 'Fraternité pour Demain - votre reçu fiscal';
                                                $replyTo = Config::get('yourdata.reply_to_donation_payment');
                                                $mailData = array('project_title' => $getThisProject['project_title'],'timedate'=>$donation_date,'donation_amount'=>$getThisDonation->amount_to_project, 'donation_year'=>$donation_year, 'next_donation_year'=>$next_donation_year);

                                                Mail::send('email.cancel_send_with_certificate', $mailData, function ($sent) use ($toUser, $fromUser, $subject, $pdf, $replyTo) {
                                                    $sent->from($fromUser)->subject($subject);
                                                    $sent->replyTo($replyTo);
                                                    $sent->to($toUser);
                                                    $sent->attachData($pdf->output(), "Reçu.pdf");
                                                });

                                                /****update tax sent date****/
                                                $updateTaxSent = Donation::find($getThisDonation['id']);
                                                $thisDate = date('Y-m-d H:i:s');
                                                $updateTaxSent->tax_certificate_sent_at = $thisDate;
                                                $updateTaxSent->save();
                                            }

                                            
                                            // dd($mailData);

                                            // Mail::send('email.cancel_send_with_certificate', $mailData, function ($sent) use ($toUser, $fromUser, $subject, $pdf, $replyTo) {
                                            //     $sent->from($fromUser)->subject($subject);
                                            //     $sent->replyTo($replyTo);
                                            //     $sent->to($toUser);
                                            //     $sent->attachData($pdf->output(), "certificate.pdf");
                                            // });


                                        }
                                        
                                  
                                       
                                    }
                                }
                            }

                            
                        }
                        
                    }

                    session()->flash('success', 'Statut mis à jour avec succès');
                    Session::flash('alert-class', 'alert-success'); 
                    return redirect('securepanel/project-management/project-list');

                }
                else
                {
                    session()->flash('error', 'Pardon! vous ne pouvez pas changer le statut du projet tant que des paiements ont toujours le statut "en cours". Merci de mettre à jour les statuts des paiements avant de changer le statut du projet.');
                    Session::flash('alert-class', 'alert-error'); 
                    return redirect('securepanel/project-management/project-status-change/'.$request->project_id);
                }

                
            }
        }
        catch (\Exception $e) {

            return redirect('securepanel/project-management/project-status-change/'.$request->project_id)
            ->with('message',$e->getMessage())
            ->with('alert-class', 'alert-danger')
            ->withInput();
           
        }
    }


    /************************use*****************************/
    # ProjectController
    # Function name : SetDoubleAmount
    # Author        :
    # Created Date  : 27-07-2022
    # Purpose       : Change or set double amount form
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function SetDoubleAmount(Request $request,$encryptCode){
        //echo Auth::id(); die;
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Projets";
        $this->data['panel_title']="Projets";

        //dd($this->data);

        $projectId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));
        $thisProject = Project::where('id',$projectId)->first();

        $getUser = User::where('id',$thisProject['user_id'])->first();
        $fullname = $getUser['first_name'].' '.$getUser['last_name'];

        $proj_status = '';
        $proj_status=$this->prstats[$thisProject['project_status']];

        // if($thisProject['project_status'] == 'pending')
        // {
        //    $proj_status = 'En attente';
        // }
        // else
        // {
        //     if($thisProject['project_status'] == 'active')
        //     {
        //         $proj_status = 'En cours';
        //     }
        //     else
        //     {
        //         if($thisProject['project_status'] == 'inactive')
        //         {
        //             $proj_status = 'Inactif';
        //         }
        //         else
        //         {
        //             if($thisProject['project_status'] == 'rejected')
        //             {
        //                 $proj_status = 'Rejeté';
        //             }
        //             else
        //             {
        //                 if($thisProject['project_status'] == 'close')
        //                 {
        //                     $proj_status = 'Terminé';
        //                 }
        //             }
        //         }
        //     }
        // }

        $double_status = '';

        $is_double= '';
        $double_amount = '';

        if($thisProject['is_double'] == '1')
        {
                    $double_status = '<i class="fas fa-hand-holding-usd"></i> Oui ('.$thisProject['double_amount_limit'].'€)';
                    $is_double= 'yes';
                    $double_amount = $thisProject['double_amount_limit'];
        }
        else
        {
                    $double_status = '<i class="fas fa-hand-holding-usd"></i> Non';
                    $is_double= 'no';
                    $double_amount = '0';
        }

        $sum = Donation::where('donation_status', 'completed')->where('project_id',$projectId)->sum('amount');

        $domains = explode(',',$thisProject['domain_id']);
        $domain_name='';
        $cnt = 1;
        $domain_size = sizeof($domains);
        foreach($domains as $domain)
        {
            $thisDomain = Domain::where('id',$domain)->first();
            $domain_name.=$thisDomain['domain_name'];
            if($cnt != $domain_size)
            {
                $domain_name.= ',';
            }
            $cnt++;
        }

        $this->data['thisProject'] = $thisProject;
        $this->data['fullname'] = $fullname;
        $this->data['proj_status'] = $proj_status;
        $this->data['double_status'] = $double_status;
        $this->data['sum'] = $sum;
        $this->data['domain_name'] = $domain_name;
        //$this->data['statuses'] = array('pending'=>'En attente', 'active'=>'En cours', 'inactive'=>'Inactif', 'rejected'=>'Rejeté', 'close'=>'Terminé');
        $this->data['statuses']=$this->prstats;

        $this->data['is_double']=$is_double;
        $this->data['double_amount']=$double_amount;


        $this->data['projectId']=$encryptCode;
        $this->data['thisProject']=$thisProject;
        
        // dd($this->data);
        return view('admin.projectmanagement.project-double-amount',$this->data);
    }

    /**************************use***************************/
    # ProjectController
    # Function name : SaveDoubleAmount
    # Author        :
    # Created Date  : 27-07-2022
    # Purpose       : project status save
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function SaveDoubleAmount(Request $request){

        try {

            // $projectId = $request->project_id;

            $validator = Validator::make($request->all(), [
                        'is_double' => 'required'
                    ]);
                    
            if ($validator->fails()) { 
                return redirect('securepanel/project-management/set-double-amount/'.$request->project_id)
                                    ->withErrors($validator)
                                    ->withInput();
            }
            else
            {

                $projectId = decrypt($request->project_id, Config::get('Constant.ENC_KEY'));
                
                $thisProject = Project::find($projectId);
                // dd($request);
                $thisProject->is_double = $request->is_double;
                if($request->is_double == '1')
                {
                    $thisProject->double_amount_limit = trim($request->double_amount_limit);
                }
                else
                {
                    $thisProject->double_amount_limit = 0;
                }

                // dd($thisProject);
                // dd($thisProject->save());
                if($thisProject->save())
                {
                    session()->flash('success', 'Double montant mis à jour avec succès');
                    Session::flash('alert-class', 'alert-success'); 
                    return redirect('securepanel/project-management/project-list');
                }
                else
                {
                    return redirect('securepanel/project-management/set-double-amount/'.$request->project_id)
                    ->with('message','no')
                    ->with('alert-class', 'alert-danger')
                    ->withInput();
                }

                
                /*email*/
                // $thisUser = User::where('id',$getMaster['user_id'])->first();
                // $fromUser = Config::get('yourdata.admin_email_from');
                // $toUser = $thisUser['email'];
                // $subject = 'Réservation de créneau annulée - Coworking';
                // $mailData = array('first_name' => $thisUser['first_name'], 'last_name' => $thisUser['last_name'], 'email' => $thisUser['email'], 'booking_dt' => $booking_dt, 'slot'=>$slot);
                // Mail::send('email.cancelbyadmin', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                //         $sent->from($fromUser)->subject($subject);
                //         $sent->to($toUser);
                //     });
                /****/

                

            }
        }
        catch (\Exception $e) {

            return redirect('securepanel/project-management/set-double-amount/'.$request->project_id)
            ->with('message',$e->getMessage())
            ->with('alert-class', 'alert-danger')
            ->withInput();
           
        }
    }
    

    // /**************************use***************************/
    // # BookingController
    // # Function name : bookingCancel
    // # Author        :
    // # Created Date  : 27-01-2022
    // # Purpose       : Display Customer booking cancel form
    // #                 
    // #                 
    // # Params        : Request $request
    // /*****************************************************/

    // public function bookingCancel(Request $request, $encryptCode){
    //     $thisAdmin = User::where('id', Auth::id())->first();
    //     $this->data['page_title']="Annuler Réserver";
    //     $this->data['panel_title']="Annuler Réserver";
    //     $masterBookId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

    //     $getDetail = MasterBooking::where('id', $masterBookId)->first();
    //     $this->data['getDetail'] = $getDetail;

    //     $getUser = User::where('id', $getDetail['user_id'])->first();
    //     $this->data['getUser'] = $getUser;

    //     $booking_date = Carbon::createFromFormat('n-j-Y', $getDetail['booking_date'])->format('d/m/Y');
    //     $this->data['booking_date'] = $booking_date;

    //     $purchase_date = Carbon::createFromFormat('Y-m-d H:i:s', $getDetail['created_at'])->format('d/m/Y');
    //     $this->data['purchase_date'] = $purchase_date;

    //     $this->data['bookingid'] = $encryptCode;

    //     return view('admin.bookingmanagement.booking-cancel',$this->data);

    // }

    // /**************************use***************************/
    // # BookingController
    // # Function name : bookingCancelSave
    // # Author        :
    // # Created Date  : 27-01-2022
    // # Purpose       : Display Customer booking cancel save
    // #                 
    // #                 
    // # Params        : Request $request
    // /*****************************************************/

    // public function bookingCancelSave(Request $request){

    //     try {

    //         //dd($request);
    //         //$userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
    //         $userId = $request->user_id;

    //         $validator = Validator::make($request->all(), [
    //                     'reason' => 'required'
    //                 ]);
                    
    //         if ($validator->fails()) { 
    //             return redirect('securepanel/booking-management/booking-cancel/'.$request->booking_id)
    //                                 ->withErrors($validator)
    //                                 ->withInput();
    //         }
    //         else
    //         {

    //             $masterBookId = decrypt($request->booking_id, Config::get('Constant.ENC_KEY'));
                

    //             $getMaster = MasterBooking::where('id',$masterBookId)->first();

    //             $booking_dt = Carbon::createFromFormat('n-j-Y', $getMaster['booking_date'])->format('d/m/Y');



             

    //             $slot = '';

    //             if($getMaster['slot_half'] == 'first')
    //             {
    //                 $slot = 'Matinée ('.Config::get('yourdata.first_half_start').' - '.Config::get('yourdata.first_half_end').')';
    //             }
    //             else
    //             {
    //                 if($getMaster['slot_half'] == 'second')
    //                 {
    //                     $slot = 'Après-midi ('.Config::get('yourdata.second_half_start').' - '.Config::get('yourdata.second_half_end').')';
    //                 }
    //                 else
    //                 {
    //                     if($getMaster['slot_half'] == 'full')
    //                     {
    //                         $slot = 'Journée entière ('.Config::get('yourdata.full_day_start').' - '.Config::get('yourdata.full_day_end').')';
    //                     }
    //                 }
    //             }

    //             // dd($slot);


    //             $thisMaster = MasterBooking::find($masterBookId);
    //             $thisMaster->booking_status = 'cancel';
    //             $thisMaster->cancel_reason = $request->reason;
    //             $thisMaster->refund_process = 'money';

    //             $thisMaster->save();

    //             // if($thisMaster->save())
    //             // {
    //             //     dd('11');
    //             // }
    //             // else
    //             // {
    //             //     dd('22');
    //             // }

    //             $getBooks = Booking::where('master_booking_id',$masterBookId)->get();

    //             if($getBooks)
    //             {
    //                 foreach($getBooks as $getBook)
    //                 {
    //                     $thisBook = Booking::find($getBook->id);
    //                     $thisBook->booking_status = 'cancel';

    //                     $thisBook->save();
    //                 }
    //             }

    //             /*email*/
    //             $thisUser = User::where('id',$getMaster['user_id'])->first();
    //             $fromUser = Config::get('yourdata.admin_email_from');
    //             $toUser = $thisUser['email'];
    //             $subject = 'Réservation de créneau annulée - Coworking';
    //             $mailData = array('first_name' => $thisUser['first_name'], 'last_name' => $thisUser['last_name'], 'email' => $thisUser['email'], 'booking_dt' => $booking_dt, 'slot'=>$slot);
    //             Mail::send('email.cancelbyadmin', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
    //                     $sent->from($fromUser)->subject($subject);
    //                     $sent->to($toUser);
    //                 });
    //             /****/

    //             session()->flash('success', 'Réservation de créneau annulée avec succès');
    //             Session::flash('alert-class', 'alert-success'); 
    //             return redirect('securepanel/booking-management/booking-list');

    //         }
    //     }
    //     catch (\Exception $e) {
    //         //Log::error($e->getMessage());
    //         //session()->flash('message', $e->getMessage());
            
    //         // session()->flash('error', $e->getMessage());
    //         // Session::flash('alert-class', 'alert-danger');
    //         // return redirect('securepanel/user-management/user-admin-add');

    //         return redirect('securepanel/booking-management/booking-cancel/'.$request->booking_id)
    //         ->with('message',$e->getMessage())
    //         ->with('alert-class', 'alert-danger')
    //         ->withInput();
           
    //     }
    // }



    // /************************use*****************************/
    // # BookingController
    // # Function name : CustomerBookingList
    // # Author        :
    // # Created Date  : 28-01-2022
    // # Purpose       : Display Customer booking listing specific customer
    // #                 
    // #                 
    // # Params        : Request $request
    // /*****************************************************/

    // public function CustomerBookingList(Request $request, $encryptCode){
    //     //echo Auth::id(); die;
    //     $thisAdmin = User::where('id', Auth::id())->first();
    //     $this->data['this_user_type'] = $thisAdmin['user_type'];
    //     $this->data['page_title']="Réservations";
    //     $this->data['panel_title']="Réservations";
    //     $this->data['encryptCode']=$encryptCode;

    //     //dd(decrypt($encryptCode, Config::get('Constant.ENC_KEY')));

    //     $getUser = User::where('id', decrypt($encryptCode, Config::get('Constant.ENC_KEY')))->first();
    //     $this->data['getUser'] = $getUser;

    //     if($getUser['user_status'] == '1')
    //     {
    //         $user_status = 'Actif';
    //     }
    //     else
    //     {
    //         $user_status = 'Inactif';
    //     }

    //     $this->data['user_status'] = $user_status;

    //     //dd($this->data);
        
    //     return view('admin.bookingmanagement.customer-booking-list',$this->data);
    // }

    // /**************************use***************************/
    // # BookingController
    // # Function name : CustomerBookingListTable
    // # Author        :
    // # Created Date  : 28-01-2022
    // # Purpose       : Display Customer booking listing table specific customer
    // #                 
    // #                 
    // # Params        : Request $request
    // /*****************************************************/

    // public function CustomerBookingListTable(Request $request, $encryptCode){
        
    //     // $data = MasterBooking::where('booking_status','!=','init')->where('user_id', decrypt($encryptCode, Config::get('Constant.ENC_KEY')))->get();

    //     $datas = MasterBooking::where('booking_status','!=','init')->where('user_id', decrypt($encryptCode, Config::get('Constant.ENC_KEY')))->get();
    //     $unorderdata = array();

    //     foreach($datas as $dat)
    //     {
    //         // $unorderdata[$dat->id] = Carbon::createFromFormat('n-j-Y', $dat->booking_date)->format('d-m-Y');
    //         $unorderdata[] = array('id'=>$dat->id,'user_id'=>$dat->user_id,'booking_date'=>$dat->booking_date,'booking_status'=>$dat->booking_status,'slot_id'=>$dat->slot_id,'booking_type'=>$dat->booking_type,'slot_half'=>$dat->slot_half,'booking_init_time'=>$dat->booking_init_time,'cancel_reason'=>$dat->cancel_reason,'refund_process'=>$dat->refund_process,'created_at'=>$dat->created_at,'updated_at'=>$dat->updated_at,'deleted_at'=>$dat->deleted_at,'booked_on'=>Carbon::createFromFormat('n-j-Y', $dat->booking_date)->format('Y-m-d H:i:s'));
    //     }
    //     $collection = collect($unorderdata);
    //     $sorted = $collection->sortBy('booked_on',SORT_REGULAR, true);
    //     $sorted->all();

    //     $data = $sorted;
  
       
    //     $finalResponse= Datatables::of($data)

    //         ->addColumn('fullname', function ($model){
    //             $getUser = User::where('id',$model['user_id'])->first();
    //             $name = $getUser['first_name'].' '.$getUser['last_name'];
    //             return $name;
    //         })

    //         ->addColumn('user_email', function ($model){
    //             $getUser = User::where('id',$model['user_id'])->first();
    //             $thisEmail = $getUser['email'];
    //             return $thisEmail;
    //         })
            
            
    //         ->addColumn('created_time', function ($model){
    //             $raw = $model['created_at'].'+08';
    //             $date = substr($raw,0,19);
    //             $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
    //             $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
    //             $localTime = date('d/m/Y H:i:s',$timestamp);
    //             return $localTime;
    //         })

    //         ->addColumn('reserve_date', function ($model) {
    //             $dateHtml = '';
    //             $dateHtml = Carbon::createFromFormat('n-j-Y', $model['booking_date'])->format('d/m/Y');
                
    //             return  $dateHtml;
    //         })


    //         ->addColumn('booking_status', function ($model) {
    //             $statusHtml = '';
    //             if($model['booking_status'] == 'active')
    //             {
    //                 $statusHtml = 'Actif';
    //             }
    //             else
    //             {
    //                 if($model['booking_status'] == 'inactive')
    //                 {
    //                     $statusHtml = 'Inactif';
    //                 }
    //                 else
    //                 {
    //                     if($model['booking_status'] == 'cancel')
    //                     {
    //                         $statusHtml = 'Cancel';
    //                     }
    //                 }
    //             }
                
    //             return  $statusHtml;
    //         })

    //         ->addColumn('slot', function ($model) {
    //             $statusHtml = '';
    //             if($model['slot_half'] == 'first')
    //             {
    //                 $statusHtml = 'Matinée ('.Config::get('yourdata.first_half_start').' - '.Config::get('yourdata.first_half_end').')';
    //             }
    //             else
    //             {
    //                 if($model['slot_half'] == 'second')
    //                 {
    //                     $statusHtml = 'Après-midi ('.Config::get('yourdata.second_half_start').' - '.Config::get('yourdata.second_half_end').')';
    //                 }
    //                 else
    //                 {
    //                     if($model['slot_half'] == 'full')
    //                     {
    //                         $statusHtml = 'Journée entière ('.Config::get('yourdata.full_day_start').' - '.Config::get('yourdata.full_day_end').')';
    //                     }
    //                 }
    //             }
                
    //             return  $statusHtml;
    //         })

    //        ->addColumn('action', function ($model) {
    //             $viewlink = route('admin.booking-management.booking-detail',  encrypt($model['id'], Config::get('Constant.ENC_KEY')));
    //             $editlink = route('admin.booking-management.booking-modify',  encrypt($model['id'], Config::get('Constant.ENC_KEY')));
    //             $cancellink= route('admin.booking-management.booking-cancel',  encrypt($model['id'], Config::get('Constant.ENC_KEY')));

    //             $thiscustomer = route('admin.booking-management.customer-booking-list',  encrypt($model['user_id'], Config::get('Constant.ENC_KEY')));
                
               
    //             $actions='<div class="btn-group btn-group-sm ">';
            
    //             $actions .='<a href="' . $viewlink . '" class="btn" id="" title="détails de la réservation"><i class="fas fa-eye"></i></a>';
               
    //             $actions .='<a href="' . $editlink . '" class="btn" id="" title="modifier la réservation"><i class="fas fa-calendar-alt"></i></a>';
    //             if($model['booking_status'] == 'active')
    //             {
    //                 $actions .='<a href="'.$cancellink.'" class="btn" id="button" title="annuler la réservation"><i class="fas fa-calendar-times"></i></a>';
    //             }

    //             $actions .='<a href="' . $thiscustomer . '" class="btn" id="" title="Cette réservation client"><i class="fas fa-book"></i></a>';
                
                
                
    //             //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
    //             //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
    //             //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
    //             $actions .='</div>';
    //             return $actions;
    //         })
    //         //->rawColumns(['updated','action','status'])
    //         ->rawColumns(['fullname','user_email','reserve_date','booking_status','slot','created_time','action'])
    //         ->make(true);
    //         //dd($finalResponse);
    //         return $finalResponse;
    //     // // $data =DB::table('users')->
    //     // // where(function($query)
    //     // // {
    //     // //     $query->where('users.user_type', 'client');
                                                
    //     // // })
    //     // // ->where('users.deleted_at', NULL)->orderBy('created_at', 'desc')
    //     // //         ->get();
    //     // //dd($data);
    //     // $finalResponse= Datatables::of($data)

    //     //     ->addColumn('fullname', function ($model){
    //     //         $getUser = User::where('id',$model->user_id)->first();
    //     //         $name = $getUser['first_name'].' '.$getUser['last_name'];
    //     //         return $name;
    //     //     })

    //     //     ->addColumn('user_email', function ($model){
    //     //         $getUser = User::where('id',$model->user_id)->first();
    //     //         $thisEmail = $getUser['email'];
    //     //         return $thisEmail;
    //     //     })
            
            
    //     //     ->addColumn('created_time', function ($model){
    //     //         $raw = $model->created_at.'+08';
    //     //         $date = substr($raw,0,19);
    //     //         $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
    //     //         $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
    //     //         $localTime = date('d/m/Y H:i:s',$timestamp);
    //     //         return $localTime;
    //     //     })

    //     //     ->addColumn('reserve_date', function ($model) {
    //     //         $dateHtml = '';
    //     //         $dateHtml = Carbon::createFromFormat('n-j-Y', $model->booking_date)->format('d/m/Y');
                
    //     //         return  $dateHtml;
    //     //     })


    //     //     ->addColumn('booking_status', function ($model) {
    //     //         $statusHtml = '';
    //     //         if($model->booking_status == 'active')
    //     //         {
    //     //             $statusHtml = 'Actif';
    //     //         }
    //     //         else
    //     //         {
    //     //             if($model->booking_status == 'inactive')
    //     //             {
    //     //                 $statusHtml = 'Inactif';
    //     //             }
    //     //             else
    //     //             {
    //     //                 if($model->booking_status == 'cancel')
    //     //                 {
    //     //                     $statusHtml = 'Cancel';
    //     //                 }
    //     //             }
    //     //         }
                
    //     //         return  $statusHtml;
    //     //     })

    //     //     ->addColumn('slot', function ($model) {
    //     //         $statusHtml = '';
    //     //         if($model->slot_half == 'first')
    //     //         {
    //     //             $statusHtml = 'Matinée ('.Config::get('yourdata.first_half_start').' - '.Config::get('yourdata.first_half_end').')';
    //     //         }
    //     //         else
    //     //         {
    //     //             if($model->slot_half == 'second')
    //     //             {
    //     //                 $statusHtml = 'Après-midi ('.Config::get('yourdata.second_half_start').' - '.Config::get('yourdata.second_half_end').')';
    //     //             }
    //     //             else
    //     //             {
    //     //                 if($model->slot_half == 'full')
    //     //                 {
    //     //                     $statusHtml = 'Journée entière ('.Config::get('yourdata.full_day_start').' - '.Config::get('yourdata.full_day_end').')';
    //     //                 }
    //     //             }
    //     //         }
                
    //     //         return  $statusHtml;
    //     //     })

    //     //    ->addColumn('action', function ($model) {
    //     //         $viewlink = route('admin.booking-management.booking-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
    //     //         $editlink = route('admin.booking-management.booking-modify',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
    //     //         $cancellink= route('admin.booking-management.booking-cancel',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                
               
    //     //         $actions='<div class="btn-group btn-group-sm ">';
            
    //     //         $actions .='<a href="' . $viewlink . '" class="btn" id="" title="détails de la réservation"><i class="fas fa-eye"></i></a>';
               
    //     //         $actions .='<a href="' . $editlink . '" class="btn" id="" title="modifier la réservation"><i class="fas fa-calendar-alt"></i></a>';
    //     //         if($model->booking_status == 'active')
    //     //         {
    //     //             $actions .='<a href="'.$cancellink.'" class="btn" id="button" title="annuler la réservation"><i class="fas fa-calendar-times"></i></a>';
    //     //         }
                
                
                
    //     //         //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
    //     //         //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
    //     //         //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
    //     //         $actions .='</div>';
    //     //         return $actions;
    //     //     })
    //     //     //->rawColumns(['updated','action','status'])
    //     //     ->rawColumns(['fullname','user_email','reserve_date','booking_status','slot','created_time','action'])
    //     //     ->make(true);
    //     //     //dd($finalResponse);
    //     //     return $finalResponse;

    // }


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

    public function ProjectModify(Request $request, $encryptCode){
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Project Modify";
        $this->data['panel_title']="Project Modify";

        //dd($this->data);
        
        $masterProjectId = decrypt($encryptCode, Config::get('Constant.ENC_KEY'));

        $getDetail = Project::where('id', $masterProjectId)->first();
        $selected_domains=(!empty($getDetail) && strlen($getDetail->domain_id)>0?explode(',', $getDetail->domain_id):'');
        $this->data['getDetail'] = $getDetail;
        $this->data['domains'] = Domain::where('domain_status','1')->get();
        $gal = Gallery::where('project_id',$masterProjectId)->get();
        $this->data['selected_domains']=$selected_domains;
        $this->data['galleries']=$gal;
        $file_list=array();
        foreach($gal as $g)
        {
            $file_list[] = array('id'=>$g->id,'name'=>$g->gal_image,'size'=>'','path'=>asset('upload/gallery').'/'.$g->gal_image);
        }
        $this->data['galleries_json']=$file_list;

        if($getDetail->number_of_goal==1)
        {
            $maxDouble=$getDetail->single_goal_amount;
        }
        else
        {
            $maxDouble=$getDetail->third_goal_amount;
        }
        $this->data['maxDouble']=$maxDouble;
        

        return view('admin.projectmanagement.project-modify',$this->data);

    }
    public function ProjectEditSave(Request $request)
    {
        try{
            if(Auth::id() and Auth::user()->user_type == 0 and Auth::user()->account_status == 1)
            {
                $rules=array('project_title' => 'required',
                            'location' => 'required',
                            'short_description' => 'required',
                            'description' => 'required',
                            'domain_id' => 'required',
                            'campain_end_stage' => 'required',
                            'start_date' => 'required',
                            'end_date' => 'required',
                            'video'=>'sometimes',
                            'recomended_amount' => 'required',
                            'associate_name' => 'required',
                            'associate_address' => 'required',
                            'associate_phone' => 'required',
                            'associate_email' => 'required|email',
                            'predefine_amount'=>'sometimes',
                            // 'associate_logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
                            'associate_purpose' => 'required',
                            // 'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp',
                            'is_double'=>'required',
                            'double_amount_limit'=>'required',
                             
                             );
                if($request->number_of_goal == '1')
                {
                    $rules['number_of_goal']='required';
                    $rules['single_goal']='required';
                    $rules['single_goal_amount']='required';  
                }
                else
                {
                    $rules['number_of_goal']='required';
                    $rules['first_goal']='required';
                    $rules['first_goal_amount']='required';
                    $rules['second_goal']='required';  
                    $rules['second_goal_amount']='required';  
                    $rules['third_goal']='required';  
                    $rules['third_goal_amount']='required';        
                }
                //$validator = Validator::make($request->all(), $rules);
                

                $imageChk=0;
                $er=array();
                $chks=array('associate_logo'=>'Associate Logo','cover_image'=>'Cover Image');
                foreach ($chks as $key=>$label) {
                    if(request()->hasFile($key)) {
                        $fileArray= array('image' => $request->file($key));
                        $rules[$key.'_blob']='required';
                        $Imagerules = array(
                          'image' => 'mimes:jpeg,png,jpg,gif,svg,webp' // max 10000kb
                        );
                        
                        $validate = Validator::make($fileArray, $Imagerules);
                        if ($validate->fails())
                        {
                            $imageChk++; 
                            $er[]=$label.' is a required field';
                        } 
                    }
                    else
                    {
                        
                        $v=$key.'_old';
                        $chk=(strlen($request->$v)>0?'':'error');
                        if($chk=='error')
                        {
                           $imageChk++; 
                           $er[]=$label.' is a required field';
                        }
                    }
                 }

                 // print_r($rules);
                 // die;
                 $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) { 
                    return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
                    
                }
                elseif($imageChk>0)
                {
                    return redirect()->back()
                            ->withErrors(implode(', ', $er))
                            ->withInput();
                    
                }
                else
                {
                    $newData=array();
                    $rules['video']='required';
                    unset($rules['associate_logo_blob']);
                    unset($rules['cover_image_blob']);
                    foreach($rules as $key=>$rule)
                    {
                        
                        
                        if(is_array($request->$key) || is_string($request->$key))
                        {
                            if($key=='domain_id')
                            {
                                $newData[$key]=implode(',',$request->domain_id);
                            }
                            else
                            {
                                $newData[$key]=$request->$key;
                            }
                            
                        }
                        else
                        {
                            $newData[$key]='';
                            
                        }
                    }
                    
                    if($newData['number_of_goal']==1)
                    {
                        $skip=array('first_goal','first_goal_amount','second_goal','second_goal_amount','third_goal','third_goal_amount');
                    }
                    else
                    {
                        $skip=array('single_goal','single_goal_amount');
                    }
                    foreach($skip as $s)
                    {
                        $newData[$s]='';
                    }
                    $description=$newData['description'];

                    $description=html_entity_decode($newData['description']);
                    $dom = new \DomDocument('1.0', 'utf-8');
                    @$dom->loadHtml('<?xml encoding="utf-8" ?>'.$description);
                    $imageFile = $dom->getElementsByTagName('img');
                    $startString='data:image/';
                    
                    $len = strlen($startString);
                    foreach($imageFile as $item => $image){
                       $data = $image->getAttribute('src');
                       
                       if(substr($data, 0, $len) === $startString)
                       {
                            list($type, $data) = explode(';', $data);
                            list(, $data)      = explode(',', $data);
                            $imgeData = base64_decode($data);
                            $image_name= "/front/fromfront/" . time().$item.'.webp';
                            $path = public_path() . $image_name;
                            //($path, $imgeData);
                           Image::make($imgeData)->encode('webp', 90)->save($path);
                           $image->removeAttribute('src');
                           $image->setAttribute('src', $image_name);
                       }
                      
                       
                       
                    }
                   
             
                   $description = $dom->saveHTML();
                   $description=str_replace('<?xml encoding="utf-8" ?>','',$description);
                   $description=str_replace('="/front/fromfront/','="'.asset('front/fromfront/').'/',$description);
                    $newData['description']=$description;
                    //echo $newData['description'];
                  
                    
                    $project = Project::find($request->pro_id);
                    //echo '<pre>';
                    //print_r($newData);
                    //echo '</pre>';
                    //die;
                    foreach($newData as $key=>$d)
                    {
                        if($key=='double_amount_limit')
                        {
                            $project->$key=(int)$d;
                        }
                        else
                        {
                            $project->$key=$d;
                        }
                    }
                    
                    
                    $chks=array('associate_logo'=>'logo','cover_image'=>'cover');
                    foreach($chks as $c=>$path)
                    {
                        if(request()->hasFile($c)) {
                            $file = $request->file($c);
                            $k=$c.'_blob';
                            $blob=$request->$k;

                             $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'_'.time().'.webp';
                             $fileName=str_replace(' ','',$fileName);
                            $destinationPath = public_path('upload/'.$path.'/');
                            //$file->move($destinationPath, $fileName);
                            $p=public_path('/upload/'.$path.'/'.$fileName);
                            Image::make($blob)->encode('webp', 90)->save(public_path('/upload/'.$path.'/'.$fileName));

                            if($path == 'cover')
                            {
                                Image::make($blob)->encode('webp', 100)->resize(800, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    })->save(public_path('/upload/cover_resize/'.$fileName));
                            }
                            $project->$c=$fileName;
                            
                        }
                    }
                    
                    $project->save();

                    $remove=$request->gal_image_remove;

                    if(strlen($remove)>0)
                    {
                        $removed=array();
                        $removeData=explode(',', $remove);
                        $imgs=Gallery::whereIn('id',$removeData)->get();
                        foreach($imgs as $im)
                        {
                            $image_path = public_path('upload/gallery/'.$im->gal_image);  // Value is not URL but directory file path
                            if(File::exists($image_path)) {
                                File::delete($image_path);
                                
                            }
                            $removed[]=$im->id;
                        }
                        
                        Gallery::whereIn('id', $removed)->delete();
                    }
                    
                    return redirect()->back()
                            ->with('success','Projet modifié avec succès.')
                            ->with('alert-class', 'alert-danger');
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
    public function ProjectEditGallerySave(Request $request)
    {
        if($request->hasFile('file')) {
            $images = $request->file('file');
            $projectID=$request->projectID;
            $destinationPath = public_path('upload/gallery/');
            foreach ($images as $key => $file) {
                $gallery_imagename = '';
                $gallery = $file;
                if(!empty($gallery)) {
                    $gallery_imagename = pathinfo($gallery->getClientOriginalName(), PATHINFO_FILENAME).'_'.time().'.webp';
                    //$file->move($destinationPath, $gallery_imagename); 
                    Image::make($gallery)->encode('webp', 90)->resize(1047,569,function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })->save(public_path('/upload/gallery/'  .  $gallery_imagename));
                    $new_gal = new Gallery;
                    $new_gal->project_id = $projectID;
                    $new_gal->gal_image = $gallery_imagename;
                    $new_gal->gal_status = '1';
                    $new_gal->save();                              
                }

            }
        }
        
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

    // public function getThatDateSlot(Request $request)
    // { 
    //     //return($request->selected_date);
    //     // $thisDate = $request->selected_date;
    //     $slottype = $request->slot_type;

    //     // $modify_date = Carbon::createFromFormat('n-j-Y', $thisDate)->format('d/m/Y');
    //     $modify_date = $request->selected_date;
    //     $thisDate = Carbon::createFromFormat('d/m/Y', $request->selected_date)->format('n-j-Y');


    //     // return $modify_date;

    //     $html = '<select name="slot_half" class="form-select" style="height: 30px !important;" required>';

    //     if($slottype == 'half')
    //     {
    //             // $first_half = Booking::where('booking_status', 'active')->where('booking_date','>=',$thisDate)->where('slot_half','first')->count();
    //             $first_half = Booking::where('booking_status', 'active')->where('booking_date',$thisDate)->where('slot_half','first')->count();

    //             //return $first_half;

    //             if($first_half < 8)
    //             {
    //                 $html .= '<option value="first">'.$modify_date.' '.Config::get('yourdata.first_half_start').'-'.Config::get('yourdata.first_half_end').'</option>';
    //             }
    //             else
    //             {
    //                 $html .= '<option value="first" disabled>'.$modify_date.' '.Config::get('yourdata.first_half_start').'-'.Config::get('yourdata.first_half_end').'</option>';
    //             }

    //             $second_half = Booking::where('booking_status', 'active')->where('booking_date',$thisDate)->where('slot_half','second')->count();

    //             if($second_half < 8)
    //             {
    //                 $html .= '<option value="second">'.$modify_date.' '.Config::get('yourdata.second_half_start').'-'.Config::get('yourdata.second_half_end').'</option>';
    //             }
    //             else
    //             {
    //                 $html .= '<option value="second" disabled>'.$modify_date.' '.Config::get('yourdata.second_half_start').'-'.Config::get('yourdata.second_half_end').'</option>';
    //             }

    //     }
    //     else
    //     {
    //         $first_half = Booking::where('booking_status', 'active')->where('booking_date',$thisDate)->where('slot_half','first')->count();

    //         $second_half = Booking::where('booking_status', 'active')->where('booking_date',$thisDate)->where('slot_half','second')->count();

    //         //return $first_half;

    //         if($first_half < 8 and $first_half < 8)
    //         {
    //             $html .= '<option value="full">'.$modify_date.' '.Config::get('yourdata.full_day_start').'-'.Config::get('yourdata.full_day_end').'</option>';
    //         }
    //         else
    //         {
    //             $html .= '<option value="full" disabled>'.$modify_date.' '.Config::get('yourdata.full_day_start').'-'.Config::get('yourdata.full_day_end').'</option>';
    //         }

    //     }

    //     $html .= '</select>';

    //     return $html;


    // }


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

//     public function bookingModifySave(Request $request){

//         try {

//             //dd($request);
//             //$userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
//             $userId = $request->user_id;

//             $validator = Validator::make($request->all(), [
//                         'booking_date' => 'required',
//                         'slot_half' => 'required'
//                     ]);
                    
//             if ($validator->fails()) { 
//                 return redirect('securepanel/booking-management/booking-modify/'.encrypt($request->booking_id, Config::get('Constant.ENC_KEY')))
//                                     ->withErrors($validator)
//                                     ->withInput();
//             }
//             else
//             {
//                 // $modify_date = Carbon::createFromFormat('j-n-Y', $thisDate)->format('n-j-Y');
//                 //
//                 $this_date = Carbon::createFromFormat('d/m/Y', $request->booking_date)->format('n-j-Y');
//                 $masterBookId = $request->booking_id;

//                 $thisMaster = MasterBooking::find($masterBookId);

//                 if($request->slot_half_type == 'half')
//                 {
//                     // $thisMaster->booking_date = $request->booking_date;
//                     $thisMaster->booking_date = $this_date;
//                     $thisMaster->slot_half = $request->slot_half;

//                     $thisMaster->save();

//                     $getBooking = Booking::where('master_booking_id', $masterBookId)->first();

//                     $thisBooking = Booking::find($getBooking['id']);

//                     // $thisBooking->booking_date = $request->booking_date;
//                     $thisBooking->booking_date = $this_date;
//                     $thisBooking->slot_half = $request->slot_half;

//                     $thisBooking->save();

//                     session()->flash('success', 'Date modifiée avec succès');
//                     Session::flash('alert-class', 'alert-success'); 
//                     return redirect('securepanel/booking-management/booking-modify/'.encrypt($request->booking_id, Config::get('Constant.ENC_KEY')));


//                 }
//                 else
//                 {
//                     // $thisMaster->booking_date = $request->booking_date;
//                     // $thisMaster->slot_half = $request->slot_half;
//                     // echo $masterBookId; die;
//                     $thisMaster->booking_date = $this_date;

//                     $thisMaster->save();

//                     $getBooking = Booking::where('master_booking_id', $masterBookId)->get();

//                     $bookids = array();

//                     foreach($getBooking as $getBook)
//                     {
//                         $bookids[] = $getBook->id;
//                     }
                    

//                     foreach($bookids as $bookid)
//                     {
//                         $thisBooking = Booking::find($bookid);
// // dd($thisBooking);
//                         // $thisBooking->booking_date = $request->booking_date;
//                         $thisBooking->booking_date = $this_date;
//                         // $thisBooking->slot_half = $request->slot_half;

//                         $thisBooking->save();
//                     }

//                     session()->flash('success', 'Date modifiée avec succès');
//                     Session::flash('alert-class', 'alert-success'); 
//                     return redirect('securepanel/booking-management/booking-modify/'.encrypt($request->booking_id, Config::get('Constant.ENC_KEY')));

                    
//                 }

//             }
//         }
//         catch (\Exception $e) {
//             //Log::error($e->getMessage());
//             //session()->flash('message', $e->getMessage());
            
//             // session()->flash('error', $e->getMessage());
//             // Session::flash('alert-class', 'alert-danger');
//             // return redirect('securepanel/user-management/user-admin-add');

//             return redirect('securepanel/booking-management/booking-modify/'.encrypt($request->booking_id, Config::get('Constant.ENC_KEY')))
//             ->with('message',$e->getMessage())
//             ->with('alert-class', 'alert-danger')
//             ->withInput();
           
//         }
//     }

// //=================================================================
//     /*****************************************************/
//     # BookingController
//     # Function name : theCalendar
//     # Author        :
//     # Created Date  : 31-01-2022
//     # Purpose       : get available solt ajax
//     #                 
//     #                 
//     # Params        : Request $request
//     /*****************************************************/

//     public function theCalendar(Request $request)
//     {
//         /**************/

//         $today = date('n-j-Y');

//         $booked_date = array();

        
//             $mybookingdate = MasterBooking::where('booking_status', 'active')->get();

//             foreach($mybookingdate as $mybookingdate_each)
//             {
//                 if(Carbon::createFromFormat('n-j-Y', $mybookingdate_each->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
//                 {
//                     $booked_date[] = $mybookingdate_each->booking_date;
//                 }
                
//             }

//         $myholidays = Holiday::all();

//         $not_date = array();

//         foreach($myholidays as $myholidays_each)
//         {
                
//             $not_date[] = $myholidays_each->holiday_dt;
                
                
//         }
//         $this->data['day_unavailable'] = $not_date;


//         $firsthalfbooked = Booking::where('booking_status', 'active')->where('slot_half','first')->get();
//         $is_first_book = array();

//         foreach($firsthalfbooked as $fhalfbooked)
//         {
//             if(Carbon::createFromFormat('n-j-Y', $fhalfbooked->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
//             {
//                 $is_first_book[] = $fhalfbooked->booking_date;
//             }
            
//         }

//         $unique_is_first_book = array_unique($is_first_book);

//         $confirm_unavail_first = array();

//         foreach($unique_is_first_book as $unique_is_f_book)
//         {
//             $is_first_full =  Booking::where('booking_status', 'active')->where('slot_half','first')->where('booking_date',$unique_is_f_book)->count();

//             if($is_first_full >= 8)
//             {
//                 $confirm_unavail_first[] = $unique_is_f_book;
//             }
//         }
//         //dd($data);

//         $secondhalfbooked = Booking::where('booking_status', 'active')->where('slot_half','second')->get();
//         $is_second_book = array();

//         foreach($secondhalfbooked as $shalfbooked)
//         {
//             if(Carbon::createFromFormat('n-j-Y', $shalfbooked->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
//             {
//                 $is_second_book[] = $shalfbooked->booking_date;
//             }
            
//         }

//         $unique_is_second_book = array_unique($is_second_book);

//         $confirm_unavail_second = array();

//         foreach($unique_is_second_book as $unique_is_s_book)
//         {
//             $is_second_full =  Booking::where('booking_status', 'active')->where('slot_half','second')->where('booking_date',$unique_is_s_book)->count();

//             if($is_second_full >= 8)
//             {
//                 $confirm_unavail_second[] = $unique_is_s_book;
//             }
//         }

//         $half_day_unavailable_inter = array_intersect($confirm_unavail_first,$confirm_unavail_second);

//         $this->data['day_highlight_unavailable'] = $half_day_unavailable_inter;

        
//         $this->data['booked_date'] = $booked_date;
//         $this->data['month'] = date('n');
//         $this->data['day'] = date('j');
//         $this->data['year'] = date('Y');

//         $this->data['month'] = date('n');
//         $this->data['day'] = date('j');
//         $this->data['year'] = date('Y');
//         $this->data['this_date'] = date('d/m/Y');;

//         // dd($this->data);

//         return view('admin.bookingmanagement.calendar',$this->data);
//     }


    // //=================================================================
    // /*****************************************************/
    // # BookingController
    // # Function name : theCalendar
    // # Author        :
    // # Created Date  : 31-01-2022
    // # Purpose       : get available solt ajax
    // #                 
    // #                 
    // # Params        : Request $request
    // /*****************************************************/

    // public function theCalendar(Request $request)
    // {
    //     /**************/

    //     $today = date('n-j-Y');

    //     $booked_date = array();

        
    //         $mybookingdate = MasterBooking::where('booking_status', 'active')->get();

    //         foreach($mybookingdate as $mybookingdate_each)
    //         {
    //             if(Carbon::createFromFormat('n-j-Y', $mybookingdate_each->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
    //             {
    //                 $booked_date[] = $mybookingdate_each->booking_date;
    //             }
                
    //         }

    //     $myholidays = Holiday::all();

    //     $not_date = array();

    //     foreach($myholidays as $myholidays_each)
    //     {
                
    //         $not_date[] = $myholidays_each->holiday_dt;
                
                
    //     }
    //     // $this->data['day_unavailable'] = $not_date;
    //     $this->data['day_holiday'] = $not_date;



    //     $firsthalfbooked = Booking::where('booking_status', 'active')->where('slot_half','first')->get();
    //     $is_first_book = array();

    //     foreach($firsthalfbooked as $fhalfbooked)
    //     {
    //         if(Carbon::createFromFormat('n-j-Y', $fhalfbooked->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
    //         {
    //             $is_first_book[] = $fhalfbooked->booking_date;
    //         }
            
    //     }

    //     $unique_is_first_book = array_unique($is_first_book);

    //     $confirm_unavail_first = array();

    //     foreach($unique_is_first_book as $unique_is_f_book)
    //     {
    //         $is_first_full =  Booking::where('booking_status', 'active')->where('slot_half','first')->where('booking_date',$unique_is_f_book)->count();

    //         if($is_first_full >= 8)
    //         {
    //             $confirm_unavail_first[] = $unique_is_f_book;
    //         }
    //     }
    //     //dd($data);

    //     $secondhalfbooked = Booking::where('booking_status', 'active')->where('slot_half','second')->get();
    //     $is_second_book = array();

    //     foreach($secondhalfbooked as $shalfbooked)
    //     {
    //         if(Carbon::createFromFormat('n-j-Y', $shalfbooked->booking_date)->format('m-d-Y') >= Carbon::createFromFormat('n-j-Y', $today)->format('m-d-Y'))
    //         {
    //             $is_second_book[] = $shalfbooked->booking_date;
    //         }
            
    //     }

    //     $unique_is_second_book = array_unique($is_second_book);

    //     $confirm_unavail_second = array();

    //     foreach($unique_is_second_book as $unique_is_s_book)
    //     {
    //         $is_second_full =  Booking::where('booking_status', 'active')->where('slot_half','second')->where('booking_date',$unique_is_s_book)->count();

    //         if($is_second_full >= 8)
    //         {
    //             $confirm_unavail_second[] = $unique_is_s_book;
    //         }
    //     }

    //     $half_day_unavailable_inter = array_intersect($confirm_unavail_first,$confirm_unavail_second);

    //     $this->data['day_unavailable'] = $half_day_unavailable_inter;

        
    //     $this->data['booked_date'] = $booked_date;
    //     $this->data['month'] = date('n');
    //     $this->data['day'] = date('j');
    //     $this->data['year'] = date('Y');

    //     $this->data['month'] = date('n');
    //     $this->data['day'] = date('j');
    //     $this->data['year'] = date('Y');
    //     $this->data['this_date'] = date('d/m/Y');;

    //     // dd($this->data);

    //     return view('admin.bookingmanagement.calendar',$this->data);
    // }


    // /**************************use***************************/
    // # BookingController
    // # Function name : BookingListThisDateTable
    // # Author        :
    // # Created Date  : 03-03-2022
    // # Purpose       : Display Customer booking listing table for this date
    // #                 
    // #                 
    // # Params        : Request $request
    // /*****************************************************/

    // public function BookingListThisDateTable(Request $request,$month,$day,$year){
    //     // dd($month);
    //     $thisDate = $month.'-'.$day.'-'.$year;
    //     $data = MasterBooking::where('booking_status','active')->where('booking_date', $thisDate)->get();
    //     // $data =DB::table('users')->
    //     // where(function($query)
    //     // {
    //     //     $query->where('users.user_type', 'client');
                                                
    //     // })
    //     // ->where('users.deleted_at', NULL)->orderBy('created_at', 'desc')
    //     //         ->get();
    //     //dd($data);
    //     $finalResponse= Datatables::of($data)

    //         ->addColumn('fullname', function ($model){
    //             $getUser = User::where('id',$model->user_id)->first();
    //             $name = $getUser['first_name'].' '.$getUser['last_name'];
    //             return $name;
    //         })

    //         ->addColumn('user_email', function ($model){
    //             $getUser = User::where('id',$model->user_id)->first();
    //             $thisEmail = $getUser['email'];
    //             return $thisEmail;
    //         })
            
            
    //         ->addColumn('created_time', function ($model){
    //             $raw = $model->created_at.'+08';
    //             $date = substr($raw,0,19);
    //             $tzOffset = (strlen($raw) > 19) ? substr($raw,-3) : 0;
    //             $timestamp = strtotime($date) + (60 * 60 * $tzOffset);
    //             $localTime = date('d/m/Y H:i:s',$timestamp);
    //             return $localTime;
    //         })

    //         ->addColumn('reserve_date', function ($model) {
    //             $dateHtml = '';
    //             $dateHtml = Carbon::createFromFormat('n-j-Y', $model->booking_date)->format('d/m/Y');
                
    //             return  $dateHtml;
    //         })


    //         ->addColumn('booking_status', function ($model) {
    //             $statusHtml = '';
    //             if($model->booking_status == 'active')
    //             {
    //                 $statusHtml = 'Active';
    //             }
    //             else
    //             {
    //                 if($model->booking_status == 'inactive')
    //                 {
    //                     $statusHtml = 'Inactive';
    //                 }
    //                 else
    //                 {
    //                     if($model->booking_status == 'cancel')
    //                     {
    //                         $statusHtml = 'Cancel';
    //                     }
    //                 }
    //             }
                
    //             return  $statusHtml;
    //         })

    //         ->addColumn('slot', function ($model) {
    //             $statusHtml = '';
    //             if($model->slot_half == 'first')
    //             {
    //                 $statusHtml = 'Matinée ('.Config::get('yourdata.first_half_start').' - '.Config::get('yourdata.first_half_end').')';
    //             }
    //             else
    //             {
    //                 if($model->slot_half == 'second')
    //                 {
    //                     $statusHtml = 'Après-midi ('.Config::get('yourdata.second_half_start').' - '.Config::get('yourdata.second_half_end').')';
    //                 }
    //                 else
    //                 {
    //                     if($model->slot_half == 'full')
    //                     {
    //                         $statusHtml = 'Journée entière ('.Config::get('yourdata.full_day_start').' - '.Config::get('yourdata.full_day_end').')';
    //                     }
    //                 }
    //             }
                
    //             return  $statusHtml;
    //         })

    //        ->addColumn('action', function ($model) {
    //             $viewlink = route('admin.booking-management.booking-detail',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
    //             $editlink = route('admin.booking-management.booking-modify',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
    //             $cancellink= route('admin.booking-management.booking-cancel',  encrypt($model->id, Config::get('Constant.ENC_KEY')));

    //             $thiscustomer = route('admin.booking-management.customer-booking-list',  encrypt($model->user_id, Config::get('Constant.ENC_KEY')));
                
               
    //             $actions='<div class="btn-group btn-group-sm ">';
            
    //             $actions .='<a href="' . $viewlink . '" class="btn" id="" title="Détail"><i class="fas fa-eye"></i></a>';
               
    //             $actions .='<a href="' . $editlink . '" class="btn" id="" title="Modifier la date"><i class="fas fa-calendar-alt"></i></a>';
    //             if($model->booking_status == 'active')
    //             {
    //                 $actions .='<a href="'.$cancellink.'" class="btn" id="button" title="Annuler"><i class="fas fa-calendar-times"></i></a>';
    //             }

    //             $actions .='<a href="' . $thiscustomer . '" class="btn" id="" title="Cette réservation client"><i class="fas fa-book"></i></a>';
                
                
                
    //             //$actions .='<a href="' . $matchlink . '" class="btn" id=""><i class="fas fa-heart"></i></a>';
    //             //$actions .='<a href="' . $invitationlink . '" class="btn" id=""><i class="fas fa-envelope-open-text"></i></a>';
    //             //$actions .='<a href="' . $changepassword . '" class="btn" id=""><i class="fa fa-key"></i></a>';
    //             $actions .='</div>';
    //             return $actions;
    //         })
    //         //->rawColumns(['updated','action','status'])
    //         ->rawColumns(['fullname','user_email','reserve_date','booking_status','slot','created_time','action'])
    //         ->make(true);
    //         // dd($finalResponse);
    //         return $finalResponse;

    // }

}