<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Category;
use App\Mailers\AppMailers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\RegistrationRequest;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }
    protected function  getRegistration(){
      $query=Input::get('search');

      $search = \DB::table('products')
              ->where('product_name', 'LIKE', '%' . $query . '%')
              ->paginate(10);

            return view('auth.register', compact('query', 'search'));
    }

    protected function postRegistration(RegistrationRequest $request,AppMailers $mailer)
    {
      $user = User::create([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'verified' => 0,
        ]);
        $mailer->sendEmailConfirmationTo($user);


        flash()->overlay('Info', 'Please confirm your email address in your inbox.');

        return redirect()->back();
    }
    public function confirmEmail($token){
      User::whereToken($token)->
            firstOrFail()->
            confirmEmail();
            flash()->success('Success', 'You are now confirmed. Please sign in.');

        return redirect('/');
    }
    public function getLogin(){
      $query=Input::get('search');
      $search=\DB::table('products')
            ->where('product_name','LIKE','%'.$query.'%')
            ->paginate(10);
      return view('auth.login', compact('query', 'search'));
    }
    public function postLogin(Request $request){
      $this->validate($request,[
        'email'=>'required|email',
        'password'=>'required'
      ]);

      if ($this->signIn($request)) {
           //flash()->success('Success', 'You have successfully signed in.');
           return redirect('/');
       }
      flash()->customErrorOverlay('Error', 'Could not sign you in with those credentials');

        return redirect('login');
    }
    protected function signIn(Request $request){
      return Auth::attempt($this->getCredentials($request),
                          $request->has('remember')
                        );
    }
    public function getCredentials(Request $request){
      return [
        'email'=>$request->input('email'),
        'passward'=>$request->input('password'),
        'verified'=>true
      ];
    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
