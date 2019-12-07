<?php
namespace App\Http\Controllers\Request;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Objects\AuthKeys;
use App\Objects\Payment;
use Dnetix\Redirection\PlacetoPay;
use Session;
use Cache;

class RequestToPay extends Controller
{
    public const IP_ADDRESS = '127.0.0.1';
    public const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36';
    
    public function index()
    {
        $cache = $this->setCacheResponseToView();
        return view('welcome', compact('cache'));
    }

    public function sendRequest(Request $req)
    {
        $auth = new AuthKeys();
        $payment = new Payment($req->reference, $req->description, $req->currency, $req->total);

        $placetopay = new PlacetoPay([
            'login' => $auth::LOGIN_ID,
            'tranKey' => $auth::SECRET_KEY,
            'url' => $auth::URL_PLACETOPAY,
        ]);
        $request = $this->setRequest($payment);
        $response = $placetopay->request($request);
        if ($response->isSuccessful()) {
            Session::put('requestId', $response->requestId);
            Session::put('processUrl', $response->processUrl);
            return redirect()->to($response->processUrl);
        } else {
            $this->setResponseMessage($response->status()->status(), $response->status()->message());
            return redirect('/');
        }
        
    }

    public function setRequest($payment)
    {
        $reference = $payment->getReference();
        $request = [
            'payment' => [
                'reference' => $reference,
                'description' => $payment->getDescription(),
                'amount' => [
                    'currency' => $payment->getCurrency(),
                    'total' => $payment->getTotal(),
                ],
            ],
            'expiration' => date('c', strtotime('+2 days')),
            'returnUrl' => route('receive').'?response='.$reference,
            'ipAddress' => $this::IP_ADDRESS,
            'userAgent' => $this::USER_AGENT,
        ];
        return $request;
    }

    public function setCacheResponseToView()
    {
        if(Cache::has('status')){
            $response = array(
                'status' => (Cache::get('status') == 'APPROVED')?'OK':'FALLO',
                'message' => Cache::get('message'),
                'date' => Cache::get('date')
            );
        } else {
            $response = null;
        }
        return $response;
    }

    function setResponseMessage($status, $message)
    {
        if ($status == 'FAILED' || $status == 'REJECTED') {
            Session::flash('status', 'FALLO');
        } elseif ($status == 'APPROVED') {
            Session::flash('status', 'OK');
        }
        Session::flash('message', $message);
    }

}
