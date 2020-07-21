<?php

namespace App\Http\Controllers;
use Session;
use App\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $enquiries= Enquiry::all();
        return response()->json(['data' =>$enquiries ], 200);
    }

    /**
     * this function send the otp
     */
    public function send_otp(Request $request)
    {
        $otp = rand ( 10000 , 99999 );
        $requestParams = array(
            'user' => 'shaikharbaz',
            'password' => 'arbaz0000',
            'mobile'=>$request->mobile,
            'message'=>"Your one Time password is ".$otp,
            'sender' => 'INVITE',
            'type' => '3'
        );
        $apiUrl = "http://login.bulksmsgateway.in/sendmessage.php?";
          foreach($requestParams as $key => $val){
           $apiUrl .= $key.'='.urlencode($val).'&';
          }
          $apiUrl = rtrim($apiUrl, "&");
         
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $apiUrl);
          $response=curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          echo $response;
          Session::put('otp', $otp);
          
          curl_exec($ch);
          curl_close($ch);
         
    }

    /**
     * verify otp 
     *
     * @return \Illuminate\Http\Response
     */
    public function verify_otp(Request $request)
    {
        if($request->otp==Session::get('otp'))
        {
            return response()->json(['success' => true,'otp'=>Session::get('otp')], 200);
        }
        
            return response()->json(['failed' => false], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'otp' => 'required'
        ]);
        Session::forget('otp');
        Enquiry::create($data);

        $enquiry= Enquiry::latest()->first();
        return response()->json(['data' => $enquiry], 200);

    }

}
