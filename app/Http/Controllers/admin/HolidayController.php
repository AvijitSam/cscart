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
use App\Models\Holiday;
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



class HolidayController extends Controller
{
	/************************use*****************************/
    # HolidayController
    # Function name : HolidayList
    # Author        :
    # Created Date  : 16-03-2022
    # Purpose       : Holiday listing
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function HolidayList(Request $request){
        //echo Auth::id(); die;
        $thisAdmin = User::where('id', Auth::id())->first();
        $this->data['this_user_type'] = $thisAdmin['user_type'];
        $this->data['page_title']="Liste des jours fériés";
        $this->data['panel_title']="Liste des jours fériés";

        $mybookingdate = Holiday::all();

        $booked_date = array();

            foreach($mybookingdate as $mybookingdate_each)
            {
                
                    $booked_date[] = $mybookingdate_each->holiday_dt;
                
                
            }
        
        $this->data['booked_date'] = $booked_date;

        $this->data['day_unavailable'] = $booked_date;

        // dd($this->data);

        
        return view('admin.holidaymanagement.index',$this->data);
    }

    /**************************use***************************/
    # HolidayController
    # Function name : HolidayListTable
    # Author        :
    # Created Date  : 16-03-2022
    # Purpose       : Holiday listing table
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function HolidayListTable(Request $request){

    	$data = Holiday::orderBy('holiday_dt','desc')
                ->get();
                // dd($data);
        
        $finalResponse= Datatables::of($data)

            ->addColumn('holidaydate', function ($model){
            	return Carbon::createFromFormat('n-j-Y', $model->holiday_dt)->format('d/m/Y');
            })

            ->addColumn('description', function ($model){
            	return $model->reason;
            })
            

           ->addColumn('action', function ($model) {

                $deletelink= route('admin.holiday-management.holiday-delete',  encrypt($model->id, Config::get('Constant.ENC_KEY')));
                
               
                $actions='<div class="btn-group btn-group-sm ">';
            
                $actions .='<a href="javascript:void(0)" data-redirect-url="'.$deletelink.'" class="btn delete-alert" id="button" title="supprimer le client"><i class="fas fa-trash"></i></a>';
               
                
                
                $actions .='</div>';
                return $actions;
            })
            //->rawColumns(['updated','action','status'])
            ->rawColumns(['holidaydate','description','action'])
            ->make(true);
            //dd($finalResponse);
            return $finalResponse;

    }


    /********************use*********************************/
    # HolidayController
    # Function name : holidayAddSave
    # Author        :
    # Created Date  : 16-03-2022
    # Purpose       : Add holiday
    #                 
    #                 
    # Params        : Request $request
    /*****************************************************/

    public function holidayAddSave(Request $request){
    
        try {

            $validator = Validator::make($request->all(), [
                        'holiday_date' => 'required',
                        'reason' => 'required'
                    ]);
                    
            if ($validator->fails()) { 
                return redirect('securepanel/holiday-management/holiday-list')
                                    ->withErrors($validator)
                                    ->withInput();
            }
            else
            {
                $thisDate = Carbon::createFromFormat('d/m/Y', $request->holiday_date)->format('n-j-Y');

                $addDate = new Holiday;
                $addDate->holiday_dt = $thisDate;
                $addDate->reason = $request->reason;

                if($addDate->save())
                {
                    session()->flash('success', 'Vacances ajoutées avec succès');
                    Session::flash('alert-class', 'alert-success'); 
                    return redirect('securepanel/holiday-management/holiday-list');
                }
                else
                {
                    session()->flash('error', 'Échec des vacances ajoutées');
                    Session::flash('alert-class', 'alert-danger'); 
                    return redirect('securepanel/holiday-management/holiday-list');
                }
            }
        }
        catch (\Exception $e) {
            

            session()->flash('error', $e->getMessage());
                        Session::flash('alert-class', 'alert-danger'); 
                        return redirect('securepanel/holiday-management/holiday-list');
           
        }
    }


    /************************use*****************************/
    # HolidayController
    # Function name : holidayDelete
    # Author        :
    # Created Date  : 16-03-2022
    # Purpose       : Holiday Delete
    #                 
    #                 
    # Params        : Request $request $encryptString
    /*****************************************************/


    public function holidayDelete(Request $request,$encryptString)
    {
       
        $holidayId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.
        

        if (Holiday::where('id', $holidayId)->delete()) {
            return redirect()->route('admin.holiday-management.holiday-list')->with('success','Jour férié supprimé avec succès');
        } else {
            $request->session()->flash('alert-danger', 'Échec de la suppression des vacances');
             return redirect()->back();
        }

        
    }


}