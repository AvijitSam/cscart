<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
// use App\Models\AdminLoginLog;
use Auth;
use Config;
use Mail;
use Redirect;
use Validator;
use Cookie;
use Illuminate\Support\Facades\Hash;
use Session;

class   AuthController extends Controller
{

    public $data = array();             // set global class object

    /*****************************************************/
    # AuthController
    # Function name : indexFirst
    # Author        :
    # Created Date  : 29-11-2024
    # Purpose       : index display
    #
    #
    # Params        : Request $request
    /*****************************************************/
    public function indexFirst(Request $request)
    {
        
        if (Auth::check()) {
            // If admin is logged in, redirect him to dashboard page //
            return Redirect::route('admin.dashboard');
        } else {
            // return view('admin.login.admin_login', $this->data);
            return Redirect::route('login');
        }
    }


    /*****************************************************/
    # AuthController
    # Function name : index
    # Author        :
    # Created Date  : 29-11-2024
    # Purpose       : Login page display
    #
    #
    # Params        : Request $request
    /*****************************************************/
    public function index(Request $request)
    {
        $this->data['page_title'] = 'Cs Cart:Login';
        $this->data['panel_title'] = 'Cs Cart:Login';
        if (Auth::check()) {
            // If admin is logged in, redirect him to dashboard page //
            return Redirect::route('admin.dashboard');
        } else {
            return view('admin.login.admin_login', $this->data);
        }
    }



    /*****************************************************/
    # AuthController
    # Function name : verifyCredentials
    # Author        :
    # Created Date  : 29-11-2024
    # Purpose       : Verify Credentials exits or not
    #
    #
    # Params        : Request $request
    /*****************************************************/

    

    // public function verifyCredentials(Request $request)
    // {
    //     if (Auth::guard('admin')->check()) {
    //         // If admin is logged in, redirect him/her to dashboard page //
    //         return Redirect::Route('admin.dashboard');
    //     } else {
    //         try {
    //             if ($request->isMethod('post')) {
    //                 // Checking validation
    //                 $validationCondition = array(
    //                     'email' => 'required',
    //                     'password' => 'required',
    //                 );
    //                 $Validator = Validator::make($request->all(), $validationCondition);
    //                 if ($Validator->fails()) {
                      
    //                     // If validation error occurs, load the error listing
    //                     return Redirect::route('admin.login')->withErrors($Validator);
    //                 } else {
    //                     $rememberMe = false; // set default boolean value for remember me

    //                     if ($request->input('remember_me')) // if user checked remember me
    //                         $rememberMe = true; // set user value

    //                     $email = $request->input('email');
    //                     $password = $request->input('password');

    //                     /* Check if user with same email exists, who is:-
    //                     1. Blocked or Not
    //                      */
                        
    //                     $userExists = User::where('email', $email)
    //                                  ->where('user_type', '0')
    //                                 // ->orWhere('user_type', 'admin')
    //                                 // ->where(function($query)
    //                                 // {
    //                                 //     $query->where('user_type', 'super admin');
    //                                 //     $query->orWhere('user_type', 'admin');
                                        
    //                                 // })
    //                                 ->where('account_status','1')
    //                                 ->first();

                       
                       
    //                     if (!is_null($userExists)) {
    //                         // if user exists, check the password
    //                         /*$auth = auth()->guard('admin')->attempt([
    //                             'email' => $email,
    //                             'password' => md5($password),
    //                         ], $rememberMe);

    //                         if ($auth) {
    //                             return Redirect::Route('admin.dashboard');
    //                         } else {
    //                             $request->session()->flash('error', 'Invalid Password');
    //                             return Redirect::Route('admin.login');
    //                         }*/
    //                         if (Hash::check($request->input('password'), $userExists->password)) {
    //                             $credentials = array('email' => $email, 'password' => md5($password));
    //                             if(Auth::login($userExists))
    //                             {
    //                                 //echo 'abc'; die;
    //                                 $remember = ($request->input('remember')) ? 1 : 0;
    //                                 if($remember==1)
    //                                 {
    //                                     Cookie::queue('remember', '1', 5400);
    //                                     Cookie::queue('user', trim($request->input('email')), 5400);
    //                                     Cookie::queue('pass', trim($request->input('password')), 5400);
    //                                 }
    //                                 else
    //                                 {
    //                                     Cookie::queue('remember', '', 5400);
    //                                     Cookie::queue('user', '', 5400);
    //                                     Cookie::queue('pass', '', 5400);
    //                                 }
    //                                 return Redirect::Route('admin.dashboard');
                                    
    //                             }
    //                             else
    //                             {
    //                                 $request->session()->flash('error', 'Password incorrect');
    //                                 return Redirect::Route('admin.login');
    //                             }
    //                         }
    //                         else {
    //                             $request->session()->flash('error', 'Invalid Password');
    //                             return Redirect::Route('admin.login');
    //                         }/**/
    //                     } else {
    //                         $request->session()->flash('error', 'You are not an user');
    //                         return Redirect::Route('admin.login');
    //                     }
    //                 }
    //             }
    //         } catch (Exception $e) {
    //             return Redirect::Route('admin.login')->with('error', $e->getMessage());
    //         }
    //     }
    // }

    public function verifyCredentials(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return Redirect::route('admin.dashboard');
        }

        try {
            if ($request->isMethod('post')) {
                $validationCondition = [
                    'email' => 'required|email',
                    'password' => 'required',
                ];

                $validator = Validator::make($request->all(), $validationCondition);

                if ($validator->fails()) {
                    return Redirect::route('login')->withErrors($validator);
                }

                $email = $request->input('email');
                $password = $request->input('password');
                $rememberMe = $request->input('remember_me') ? true : false;

                $admin = Admin::where('email', $email)
                    ->where('account_status', '1')
                    ->first();

                if ($admin && Hash::check($password, $admin->password)) {
                    Auth::guard('admin')->login($admin, $rememberMe);

                    if ($rememberMe) {
                        Cookie::queue('remember', '1', 5400);
                        Cookie::queue('user', $email, 5400);
                        Cookie::queue('pass', $password, 5400);
                    } else {
                        Cookie::queue('remember', '', 5400);
                        Cookie::queue('user', '', 5400);
                        Cookie::queue('pass', '', 5400);
                    }

                    return Redirect::route('admin.dashboard');
                }

                $request->session()->flash('error', 'Invalid credentials');
                return Redirect::route('login');
            }
        } catch (Exception $e) {
            return Redirect::route('login')->with('error', $e->getMessage());
        }
    }


    /*****************************************************/
    # AuthController
    # Function name : logout
    # Author        :
    # Created Date  : 29-11-2024
    # Purpose       : logout
    #
    #
    # Params        : Request $request
    /*****************************************************/

    public function logout()
    {
        // Log out the currently authenticated admin user
        Auth::guard('admin')->logout();
    
        // Redirect to the admin login page
        return Redirect::route('login');
    }
    /*****************************************************/
    # AuthController
    # Function name : forgotPassword
    # Author        :
    # Created Date  : 29-11-2024
    # Purpose       : Forgot Password
    #
    #
    # Params        : Request $request
    /*****************************************************/
    
    public function forgotPassword(Request $request)
    {
        $this->data['page_title'] = 'Forget Password';
        $this->data['panel_title'] = 'Forget Password';
        if (Auth::check()) {
            // If admin is logged in, redirect him to dashboard page //
            return Redirect::Route('admin.dashboard');
        } else {
            try {
                if ($request->isMethod('post')) {
                    // Checking validation
                    $validationCondition = array(
                        'email' => 'required|email',
                    );
                    $validationMessages = array(
                        'email.required' => 'Please provide email id',
                        'email.email' => 'Please provide a valid email id',
                    );
                    $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

                    if ($Validator->fails()) {
                        // If validation error occurs, load the error listing
                        return Redirect::route('admin.forgot.password')->withErrors($Validator);
                    } else {
                        $email = $request->email;
                        $emailExists = User::where('email', $email)->count();
                        if ($emailExists > 0) // if this is a valid email
                        {
                            $user = User::where('email', $email)->first(); //Fetching Specific user Data
                            if($user->user_type=='0')
                            {
                                $encryptUserId = encrypt($user->id, Config::get('Constant.ENC_KEY')); // Encrypted user id using helper
                                $recoveryLink = route('admin.reset.password' ,['encryptCode'=>$encryptUserId ]); //making recovery link

                                // setting mail configuration
                                $toUser = $email;
                                $fromUser = env('MAIL_FROM_ADDRESS'); // getting data form .env file
                                $subject = 'Password Recovery : Medela ';
                                $mailData = array('recoverLink' => $recoveryLink,'email'=>$email);

                                // Send mail
                                Mail::send('admin.email.forgetpasswordlink', $mailData, function ($sent) use ($toUser, $fromUser, $subject) {
                                    $sent->from($fromUser)->subject($subject);
                                    $sent->to($toUser);
                                });
                                if (Mail::failures()) // if mail sending failed
                                {
                                    return Redirect::Route('admin.forgot.password')->with('error', 'An error occurred while sending you the email containing the password');

                                } else // if password could not be saved successfully
                                {
                                    return Redirect::Route('admin.forgot.password')->with('success', 'Password Recovery Link has been sent to your email.');
                                }
                            }
                            else
                            {
                                return Redirect::Route('admin.forgot.password')->with('error', 'Sorry! You are not admin');
                            }
                            
                        } else // if this email is not registered
                        {
                            return Redirect::Route('admin.forgot.password')->with('error', 'This email id is not registered');
                        }
                    }
                }
            } catch (Exception $e) {
                return Redirect::Route('admin.forgot.password')->with('error', $e->getMessage());
            }
        }
        return view('admin.forgot-password.admin-forgot-password', $this->data);
    }

    /*****************************************************/
    # AuthController
    # Function name : resetPassword
    # Author        :
    # Created Date  : 03-09-2021
    # Purpose       : Reset Password
    #
    #
    # Params        : Request $request $encryptString
    /*****************************************************/

    public function resetPassword(Request $request, $encryptString)
    {
        $this->data['page_title'] = 'Reset password';
        $this->data['panel_title'] = 'Reset password';
        if (Auth::guard('admin')->check()) {
            // If admin is logged in, redirect him to dashboard page //
            return Redirect::Route('admin.dashboard');
        } else {
            try
            {
                if ($request->isMethod('post')) {
                    // Checking validation
                    $validationCondition = array(
                        'new_password' => 'required', // validation for new password
                        'confirm_password' => 'required|same:new_password',
                    );
                    $validationMessages = array(
                        'new_password.required' => 'New Password is required.',
                        'confirm_password.required' => 'Confirm Password is required.',
                        'confirm_password.same' => 'Confirm Password should be same as new password.',
                    );
                    $Validator = Validator::make($request->all(), $validationCondition, $validationMessages);

                    if ($Validator->fails()) {
                        // If validation error occurs, load the error listing
                        return Redirect::Route('admin.reset.password', ['encryptCode' => $encryptString])->withErrors($Validator);
                    } else {

                        $userId = decrypt($encryptString, Config::get('Constant.ENC_KEY')); // get user-id After Decrypt with salt key.

                        $user = User::findOrFail($userId);

                        if (!empty($user)) {
                            $user->password = md5($request->new_password);
                            $user->save();

                            return Redirect::Route('login')->with('success', 'Your new password successfully updated.');
                        } else // if user not found
                        {
                            return Redirect::Route('login')->with('error', 'Something went wrong.Please try again later.');
                        }
                    }
                }
            } catch (Exception $e) {
                return Redirect::Route('admin.reset.password', ['encryptCode' => $encryptString])->with('error', $e->getMessage());
            }
            $this->data['encryptCode'] = $encryptString;
            return view('admin.forgot-password.reset-password', $this->data);
        }
    }
}