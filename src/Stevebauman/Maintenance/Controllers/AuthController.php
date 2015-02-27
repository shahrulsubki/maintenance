<?php

namespace Stevebauman\Maintenance\Controllers;

use Stevebauman\Maintenance\Validators\AuthLoginValidator;
use Stevebauman\Maintenance\Validators\AuthRegisterValidator;
use Stevebauman\Maintenance\Services\SentryService;
use Stevebauman\Maintenance\Services\UserService;
use Stevebauman\Maintenance\Services\LdapService;
use Stevebauman\Maintenance\Services\AuthService;

/**
 * Class AuthController
 * @package Stevebauman\Maintenance\Controllers
 */
class AuthController extends BaseController
{

    /**
     * @var AuthLoginValidator
     */
    protected $loginValidator;

    /**
     * @var AuthRegisterValidator
     */
    protected $registerValidator;

    /**
     * @var SentryService
     */
    protected $sentry;

    /**
     * @var UserService
     */
    protected $user;

    /**
     * @var AuthService
     */
    protected $auth;

    /**
     * @var LdapService
     */
    protected $ldap;

    /**
     * @param AuthLoginValidator $loginValidator
     * @param AuthRegisterValidator $registerValidator
     * @param SentryService $sentry
     * @param UserService $user
     * @param AuthService $auth
     * @param LdapService $ldap
     */
    public function __construct(
        AuthLoginValidator $loginValidator,
        AuthRegisterValidator $registerValidator,
        SentryService $sentry,
        UserService $user,
        AuthService $auth,
        LdapService $ldap
    )
    {
        $this->loginValidator = $loginValidator;
        $this->registerValidator = $registerValidator;
        $this->sentry = $sentry;
        $this->user = $user;
        $this->auth = $auth;
        $this->ldap = $ldap;
    }

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function getLogin()
    {
        return view('maintenance::login', array(
            'title' => 'Sign In',
        ));
    }

    /**
     * Processes logging in a user
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function postLogin()
    {
        if ($this->loginValidator->passes())
        {
            $data = $this->inputAll();

            if (config('maintenance::site.ldap.enabled') === true)
            {
                /*
                 * Check if the user exists on active directory
                 */
                if ($this->ldap->getUserEmail($data['email']))
                {
                    /*
                     * Try authentication
                     */
                    if ($this->auth->ldapAuthenticate($data))
                    {
                        /*
                         * If authentication is good, update their
                         * web profile in case of a password update in AD
                         */
                        $user = $this->user->createOrUpdateUser($data);

                        $data['email'] = $user->email;
                    }
                }
            }

            /*
             * Authenticate with sentry
             */
            $response = $this->auth->sentryAuthenticate(
                array_only($data, array('email', 'password')),
                (array_key_exists('remember', $data) ? $data['remember'] : NULL)
            );

            /*
             * Check the authenticated response
             */
            if ($response['authenticated'] === true)
            {
                /*
                 * Successfully logged in
                 */
                $this->message = 'Successfully logged in. Redirecting...';
                $this->messageType = 'success';
                $this->redirect = route('maintenance.dashboard.index');

            } else
            {
                /*
                 * Login failed, return the response from Sentry
                 */
                $this->message = $response['message'];
                $this->messageType = 'danger';
                $this->redirect = route('maintenance.login');
            }

        } else
        {
            $this->errors = $this->loginValidator->getErrors();
            $this->redirect = route('maintenance.login');
        }

        return $this->response();
    }

    /**
     * Show the form for registering
     *
     * @return mixed
     */
    public function getRegister()
    {
        return view('maintenance::register', array(
            'title' => 'Register',
        ));
    }

    /**
     * Processes registering for an account
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function postRegister()
    {
        if ($this->registerValidator->passes())
        {
            $data = $this->inputAll();
            $data['username'] = uniqid(); //Randomize username since username is only for LDAP logins

            if($this->sentry->createUser($data))
            {
                $this->message = 'Successfully created account. You can now login.';
                $this->messageType = 'success';
                $this->redirect = route('maintenance.login');
            } else
            {
                $this->message = 'There was an error registering for an account. Please try again.';
                $this->messageType = 'danger';
                $this->redirect = route('maintenance.register');
            }
        } else
        {
            $this->errors = $this->registerValidator->getErrors();
            $this->redirect = route('maintenance.register');
        }

        return $this->response();
    }

    /**
     * Processes logging out a user
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function getLogout()
    {
        $this->auth->sentryLogout();

        $this->message = 'Successfully logged out';
        $this->messageType = 'success';
        $this->redirect = route('maintenance.login');

        return $this->response();
    }

}
