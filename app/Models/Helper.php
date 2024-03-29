<?php
namespace App\Models;

use App\CallCenterModel;
use Illuminate\Support\Facades\DB;
/***
 *
 * class
 *
 */
class Helper{
/***
 *
 * function សរុបការទាក់ទង(Total call)
 *
 */
    public static function CallCenterStartedToNow_TotalCall(){
        $result =  CallCenterModel::all();
        return count($result);
    }
/***
 *
 * function ការហៅចូល(Incoming call)
 *
 */
    public static function CallCenterStartedToNow_IncomingCall(){
        $result =  CallCenterModel::where('callType','incoming')->get();
        return count($result);
    }
/***
 *
 * function ការហៅចេញ(Out going call)
 *
 */
    public static function CallCenterStartedToNow_OutgoingCall(){
        $result =  CallCenterModel::where('callType','outgoing')->get();
        return count($result);
    }
/***
 *
 * function ការហៅចេញចូលក្នុងតំបន់(Internal call)
 *
 */
    public static function CallCenterStartedToNow_InternalCall(){
        $result =  CallCenterModel::where('callType','internal')->get();
        return count($result);
    }
/***
 *
 * function ទទួលការទាក់ទង(Answer call)
 *
 */
    public static function CallCenterStartedToNow_AnswerCall(){
        $result =  CallCenterModel::where('callStatus','ANSWERED')->get();
        return count($result);
    }
/***
 *
 * function ទំនាក់ទំនងជាប់រវល់(Busy)
 *
 */
    public static function CallCenterStartedToNow_BusyCall(){
        $result =  CallCenterModel::where('callStatus','BUSY')->get();
        return count($result);
    }
/***
 *
 * function គ្មានការទទួលការទាក់ទង(Miss call)
 *
 */
    public static function CallCenterStartedToNow_MissCall(){
        $result =  CallCenterModel::where('callStatus','NO ANSWER')->get();
        return count($result);
    }
/***
*
* function total call for this year
*
*/
       public static function CallCenterCurrentYear_TotalCall(){
           $current_year = date("Y");
           $result =  DB::table('tblcallcenter')->whereRAW("DATE_FORMAT(starttime, '%Y') = '".$current_year."'")->get();
           return count($result);
       }
/***
*
* function incoming call for this year
*
*/
    public static function CallCenterCurrentYear_IncomingCall(){
        $current_year = date("Y");
        $result =  DB::table('tblcallcenter')->where('callType','incoming')->whereRAW("DATE_FORMAT(starttime, '%Y') = '".$current_year."'")->get();
        return count($result);
    }
/***
*
* function outgoing call for this year
*
*/
    public static function CallCenterCurrentYear_OutgoingCall(){
        $current_year = date("Y");
        $result =  DB::table('tblcallcenter')->where('callType','outgoing')->whereRAW("DATE_FORMAT(starttime, '%Y') = '".$current_year."'")->get();
        return count($result);
    }
/***
*
* function internal call for this year
*
*/
    public static function CallCenterCurrentYear_InternalCall(){
        $current_year = date("Y");
        $result =  DB::table('tblcallcenter')->where('callType','internal')->whereRAW("DATE_FORMAT(starttime, '%Y') = '".$current_year."'")->get();
        return count($result);
    }
/***
*
* function answer call for this year
*
*/
    public static function CallCenterCurrentYear_AnswerCall(){
        $current_year = date("Y");
        $result =  DB::table('tblcallcenter')->where('callStatus','ANSWERED')->whereRAW("DATE_FORMAT(starttime, '%Y') = '".$current_year."'")->get();
        return count($result);
    }
/***
*
* function busy call for this year
*
*/
    public static function CallCenterCurrentYear_BusyCall(){
        $current_year = date("Y");
        $result =  DB::table('tblcallcenter')->where('callStatus','BUSY')->whereRAW("DATE_FORMAT(starttime, '%Y') = '".$current_year."'")->get();
        return count($result);
    }
/***
*
* function miss call for this year
*
*/
    public static function CallCenterCurrentYear_MissCall(){
        $current_year = date("Y");
        $result =  DB::table('tblcallcenter')->where('callStatus','NO ANSWER')->whereRAW("DATE_FORMAT(starttime, '%Y') = '".$current_year."'")->get();
        return count($result);
    }
    /***
*
* function total call for this year - month
*
*/
public static function CallCenterCurrentYearMonth_TotalCall(){
    $current_year = date("Y-m");
    $result =  DB::table('tblcallcenter')->whereRAW("DATE_FORMAT(starttime, '%Y-%m') = '".$current_year."'")->get();
    return count($result);
}
/***
*
* function incoming call for this year-month
*
*/
public static function CallCenterCurrentYearMonth_IncomingCall(){
    $current_year = date("Y-m");
 $result =  DB::table('tblcallcenter')->where('callType','incoming')->whereRAW("DATE_FORMAT(starttime, '%Y-%m') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function outgoing call for this year-month
*
*/
public static function CallCenterCurrentYearMonth_OutgoingCall(){
    $current_year = date("Y-m");
 $result =  DB::table('tblcallcenter')->where('callType','outgoing')->whereRAW("DATE_FORMAT(starttime, '%Y-%m') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function internal call for this year-month
*
*/
public static function CallCenterCurrentYearMonth_InternalCall(){
    $current_year = date("Y-m");
 $result =  DB::table('tblcallcenter')->where('callType','internal')->whereRAW("DATE_FORMAT(starttime, '%Y-%m') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function answer call for this year-month
*
*/
public static function CallCenterCurrentYearMonth_AnswerCall(){
    $current_year = date("Y-m");
 $result =  DB::table('tblcallcenter')->where('callStatus','ANSWERED')->whereRAW("DATE_FORMAT(starttime, '%Y-%m') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function busy call for this year-month
*
*/
public static function CallCenterCurrentYearMonth_BusyCall(){
    $current_year = date("Y-m");
 $result =  DB::table('tblcallcenter')->where('callStatus','BUSY')->whereRAW("DATE_FORMAT(starttime, '%Y-%m') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function miss call for this year-month
*
*/
public static function CallCenterCurrentYearMonth_MissCall(){
    $current_year = date("Y-m");
 $result =  DB::table('tblcallcenter')->where('callStatus','NO ANSWER')->whereRAW("DATE_FORMAT(starttime, '%Y-%m') = '".$current_year."'")->get();
 return count($result);
}
/*
* function total call for this year - month-day
*
*/
public static function CallCenterCurrentYearMonthDay_TotalCall(){
    $current_year = date("Y-m-d");
    $result =  DB::table('tblcallcenter')->whereRAW("DATE_FORMAT(starttime, '%Y-%m-%d') = '".$current_year."'")->get();
    return count($result);
}
/***
*
* function incoming call for this year-month-day
*
*/
public static function CallCenterCurrentYearMonthDay_IncomingCall(){
    $current_year = date("Y-m-d");
 $result =  DB::table('tblcallcenter')->where('callType','incoming')->whereRAW("DATE_FORMAT(starttime, '%Y-%m-%d') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function outgoing call for this year-month-day
*
*/
public static function CallCenterCurrentYearMonthDay_OutgoingCall(){
    $current_year = date("Y-m-d");
 $result =  DB::table('tblcallcenter')->where('callType','outgoing')->whereRAW("DATE_FORMAT(starttime, '%Y-%m-%d') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function internal call for this year-month-day
*
*/
public static function CallCenterCurrentYearMonthDay_InternalCall(){
    $current_year = date("Y-m-d");
 $result =  DB::table('tblcallcenter')->where('callType','internal')->whereRAW("DATE_FORMAT(starttime, '%Y-%m-%d') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function answer call for this year-month-day
*
*/
public static function CallCenterCurrentYearMonthDay_AnswerCall(){
    $current_year = date("Y-m-d");
 $result =  DB::table('tblcallcenter')->where('callStatus','ANSWERED')->whereRAW("DATE_FORMAT(starttime, '%Y-%m-%d') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function busy call for this year-month-day
*
*/
public static function CallCenterCurrentYearMonthDay_BusyCall(){
    $current_year = date("Y-m-d");
 $result =  DB::table('tblcallcenter')->where('callStatus','BUSY')->whereRAW("DATE_FORMAT(starttime, '%Y-%m-%d') = '".$current_year."'")->get();
 return count($result);
}
/***
*
* function miss call for this year-month-day
*
*/
public static function CallCenterCurrentYearMonthDay_MissCall(){
    $current_year = date("Y-m-d");
 $result =  DB::table('tblcallcenter')->where('callStatus','NO ANSWER')->whereRAW("DATE_FORMAT(starttime, '%Y-%m-%d') = '".$current_year."'")->get();
 return count($result);
}
 public static function CurrentMonth(){
    $jan = 'មករា';    //1
    $feb = 'កុម្ភះ';    //2
    $mar = 'មិនា';    //3
    $apr = 'មេសា';   //4
    $may = 'ឧសភា';   //5
    $jun = 'មិថុនា';   //6
    $jul = 'កក្កដា';  // 7
    $aug = 'សីហា';  //8
    $sep = 'កញ្ញា';  //9
    $oct = 'តុលា';  //១០
    $nov = 'វិច្ឆិកា'; // 11
    $dec = 'ធ្នូ'; //12

   $date = date('m');
   if($date == 1){
       $current_month = $jan;
   }elseif($date == 2){
       $current_month = $feb;
   }elseif($date == 3){
       $current_month = $mar;
   }elseif($date == 4){
       $current_month = $apr;
   }elseif($date == 5){
       $current_month = $may;
   }elseif($date == 6){
       $current_month = $jun;
   }elseif($date == 7){
       $current_month = $jul;
   }elseif($date == 8){
       $current_month = $aug;
   }elseif($date == 9){
       $current_month = $sep;
   }elseif($date == 10){
       $current_month = $oct;
   }elseif($date == 11){
       $current_month = $nov;
   }elseif($date == 12){
       $current_month = $dec;
   }else{
       $current_month = 'ទទេ';
   }
   return $current_month;
 }

}
