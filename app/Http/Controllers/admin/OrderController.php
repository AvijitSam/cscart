<?php

/*****************************************************/
# OrderController
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
use Exception;


/*
|--------------------------------------------------------------------------
|DonationController
|--------------------------------------------------------------------------
|
| donation
|
*/
class OrderController extends Controller
{
	public function OrderList(Request $request)
	{
		$this->data['page_title']="Order List";
        $this->data['panel_title']="Order List";
        $data1 = DB::table('store_orders')->orderBy('order_id', 'desc')->where('order_id',29083)->first();
        $deserialize_data = unserialize($data1->promotions);
        return view('admin.ordermanagement.order-list',$this->data);

	}
	public function OrderListTable(Request $request)
	{
        DB::enableQueryLog();
        $query = DB::table('store_orders')
        ->join('store_promotion_descriptions', 'store_orders.promotion_ids', '=', 'store_promotion_descriptions.promotion_id')
        ->select(
            'store_orders.order_id as order_number',
            'store_orders.discount',
            'store_orders.promotions',
            DB::raw("CONCAT(store_orders.firstname, ' ', store_orders.lastname) as name"),
            'store_orders.total as order_total',
            'store_orders.promotion_ids',
            'store_orders.timestamp',
            'store_promotion_descriptions.name as coupon_name'
        );
        // Apply date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
    
            // Convert the date inputs to timestamps if needed
            $start_timestamp = strtotime($start_date . ' 00:00:00');
            $end_timestamp = strtotime($end_date . ' 23:59:59');
    
            // Apply date range filter
            $query->whereBetween('store_orders.timestamp', [$start_timestamp, $end_timestamp]);
        }
        $data = $query->whereNotNull('store_orders.promotion_ids')
                  ->orderBy('store_orders.order_id', 'desc')
                  ->get(); 
        //dd(DB::getQueryLog());
			
		$finalResponse= Datatables::of($data)
        // dd(unserialize($model->promotions));
            ->addColumn('orderid', function ($model){
                
                    // return $model['order_id'];
                return $model->order_number;
                
            })
            ->addColumn('name', function ($model){
                return $model->name;
            })
            ->addColumn('total', function ($model){
                return $model->order_total;
            })
            ->addColumn('discount', function ($model){
                //unserialize($model->promotions);
               // $deserialize_data = unserialize($model->promotions);
                // if(isset($deserialize_data[$model->promotion_ids])){
                //     if(isset($deserialize_data[$model->promotion_ids]['total_discount'])){
                //         return $deserialize_data[$model->promotion_ids]['total_discount'];
                //     }
                //     return 0;
                // }
                // return 0;
                return $model->discount;
            })
            ->addColumn('coupon', function ($model){
                return ($model->coupon_name);
            })
            ->addColumn('purchase_date', function ($model){
                // return $model->timestamp;
                return date('d/m/Y H:i:s', $model->timestamp);
            })

            ->rawColumns(['orderid','name','total','discount','coupon','purchase_date'])
            ->make(true);
            
            return $finalResponse;
	}
    public function CouponList(Request $request)
	{
		$this->data['page_title']="Coupon List";
        $this->data['panel_title']="Coupon List";
       // dd(unserialize($data->promotions));
        return view('admin.ordermanagement.coupon-list',$this->data);

	}
    public function CouponDetails(Request $request)
	{
        DB::enableQueryLog();
        $query = DB::table('store_orders')
        ->join('store_promotion_descriptions', 'store_orders.promotion_ids', '=', 'store_promotion_descriptions.promotion_id')
        ->select(
            'store_promotion_descriptions.name as coupon_name',
            DB::raw('SUM(store_orders.total) as order_total'),
            DB::raw('COUNT(store_orders.order_id) as order_count') 
        );
       // Apply date range filter
       if ($request->filled('start_date') && $request->filled('end_date')) {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            // Convert the date inputs to timestamps if needed
            $start_timestamp = strtotime($start_date . ' 00:00:00');
            $end_timestamp = strtotime($end_date . ' 23:59:59');

            // Apply date range filter
            $query->whereBetween('store_orders.timestamp', [$start_timestamp, $end_timestamp]);
        }
        $data = $query->whereNotNull('store_orders.promotion_ids')
                    ->groupBy('store_promotion_descriptions.name', 'store_promotion_descriptions.promotion_id') 
                    ->get(); 
        //dd(DB::getQueryLog());
			
		$finalResponse= Datatables::of($data)
            ->addColumn('coupon_name', function ($model){
                return $model->coupon_name;
            })
            ->addColumn('total', function ($model){
                return $model->order_total;
            })
            ->addColumn('order_count', function ($model){
                return $model->order_count;
            })
            ->rawColumns(['coupon_name','total','order_count'])
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
}