<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Gregwar\Captcha\CaptchaBuilder;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use App\Mailers\AppMailer;
use Auth;

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
     * Redirect a user to this specific URI after login.
     * 
     * @var string
     */
    public $redirectPath = '/';
    /**
     * Redirect a user to this specific URI after logout.
     * 
     * @var string
     */
    public $redirectAfterLogout = '/';
    /**
     * Redirect a user to this specific URI after unsuccessful login.
     * 
     * @var string
     */
    public $loginPath = '/auth/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        
        // Prepare redirect paths (because of locale query parameter)
        $this->prepareRedirectPaths($request);
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
            'password' => 'required|confirmed|min:6',
            'captcha' => 'in:' . session('captcha')
        ]);
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

    public function getRegister()
    {
        $captcha = (new CaptchaBuilder())->build();

        session(['captcha' => $captcha->getPhrase()]);

        return view('auth.register', compact('captcha'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request, AppMailer $mailer)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        
        // Create the user
        $user = $this->create($request->all());
        // Ok, we need to set the token. We could do this easily here and save the user,
        // but we'll use Model events for this to learn how that works. Check the 
        // boot() method on User model.
        
        $locale = \App::getLocale();
        
        // Send mail to the user
        $mailer->sendEmailConfirmationTo($user, $locale);
        // Flash the message
        return redirect($this->loginPath)->with([
                    'alert' => trans('alerts.checkmail'),
                    'alert_class' => 'alert alert-warning'
        ]);

    }
    /**
     * Confirm user email.
     * 
     * @param type $token
     * @return type
     */
    public function getConfirm($token)
    {
        $user = User::where('token', $token)->firstOrFail();
        $user->verified = true;
        $user->token = null;
        $user->save();
        
        return redirect(resolveUrlAsUrl('auth/login'))->with([
                    'alert' => trans('alerts.verified'),
                    'alert_class' => 'alert alert-warning'
        ]);
    }
    
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();
        
        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }
              
        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }
        
        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }
    
    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        return $request->only($this->loginUsername(), 'password')
            + ['verified' => true, 'banned' => false];
    }
    
    /**
     * Prepare paths that will be used for redirecting users when logging in, 
     * logging out, and unsecessful login. This is necessary because of the 
     * locale query parameter in URL.
     * 
     * @param Request $request
     */
    protected function prepareRedirectPaths(Request $request) {
        if ($request->has('locale')) {
            $this->loginPath = '/auth/login?locale=' . $request->get('locale');
            $this->redirectAfterLogout = '/?locale=' . $request->get('locale');
            $this->redirectPath = '/?locale=' . $request->get('locale');
        }
    }
}
