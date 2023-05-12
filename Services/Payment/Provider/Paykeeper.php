<?php

namespace App\Services\Payment\Provider;

use Auth;
use Common;
use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\View\View;
use App\Mail\Auth\NewMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\Auth\VerifyMail;
use Illuminate\Support\Facades\Mail;
use App\Services\Payment\AbstractPaymentProcessing;

class Paykeeper extends AbstractPaymentProcessing
{
    private const URL = '';

    private const STATUS_PAYMENT_PENDING = 'pending';

    private $_orderId;
    private $_course;
    private $_user;
    private $_password;
    private $_payment;
    private $_emailToken;
    private $_paymentMethodsView;

    public function __construct(Request $request)
    {
        if (!$request->has('course_id'))
            abort(404);

        $this->_orderId            = $this->generateOrderId();
        $this->_course             = Course::find($request->course_id);
        $this->_user               = $this->findOrCreateUser($request);
        $this->_password           = $this->generateUserPassword();
        $this->_payment            = $this->createPayment();
        $this->_emailToken         = $this->generateEmailToken();
        $this->_paymentMethodsView = $this->sendRequestToProvider();
    }

    protected function sendRequestToProvider(): View
    {
        $payment_parameters = http_build_query([
            "clientid"     => $this->_user->id,
            "orderid"      => $this->_payment->order_id,
            "sum"          => $this->_course->price,
            "client_phone" => $this->_user->phone,
            "client_email" => $this->_user->email,
            "service_name" => $this->_course->name
        ]);

        $options = [
            "http" => [
                "method"  => "POST",
                "header"  => "Content-type: application/x-www-form-urlencoded",
                "content" => $payment_parameters
            ]
        ];

        $context = stream_context_create($options);
        
        $html = file_get_contents(static::URL, FALSE, $context);

        return view('payment.method')->with([
            'html' => $html,
        ]);
    }

    protected function generateOrderId()
    {
        $orderId = Common::generateRandomString();
        if (Payment::where('order_id', $orderId)->first() === null)
            return $orderId;
        return static::generateOrderId();
    }

    protected function createPayment(): Payment
    {
        $payment = Payment::where([
            'user_id'   => $this->_user->id,
            'course_id' => $this->_course->id,
            'status'    => static::STATUS_PAYMENT_PENDING
        ])->first();
        if ($payment === null) {
            $payment = Payment::create([
                'user_id'   => $this->_user->id,
                'course_id' => $this->_course->id,
                'order_id'  => $this->_orderId,
                'amount'    => $this->_course->price,
                'status'    => static::STATUS_PAYMENT_PENDING
            ]);
        }
        return $payment;
    }

    protected function findOrCreateUser(Request $request) : User
    {
        if (Auth::check()) {
            return User::find(Auth::user()->id);
        }

        $user = User::where('email', $request->email)->first();

        if ($user === null) {
            $user = User::create([
                'fullname'    => $request->fullname,
                'phone'       => $request->phone,
                'email'       => $request->email,
                'email_token' => $this->_emailToken,
                'login'       => Common::getLoginFromEmail($request->email),
                'password'    => bcrypt($this->_password),
            ]);
        }

        return $user;
    }

    protected function generateUserPassword(): string 
    {
        return Common::generateRandomString(10, false);
    }

    protected function generateEmailToken(): string
    {
        // @todo Controller cодержит константу EMAIL_TOKEN_LENGTH. Но здесь она не видна. Необходимо решить эту проблему
        return Str::random(60);
    }

    protected function sendVerifyMailToUser(): void
    {
        Mail::to($this->_user->email)->send(new VerifyMail($this->_user));
    }

    protected function sendNewMailToUser(): void
    {
        Mail::to($this->_user->email)->send(new NewMail($this->_user, $this->_password));
    }

    public function getPaymentMethodsPage(): View
    {
        return $this->_paymentMethodsView;
    }

}