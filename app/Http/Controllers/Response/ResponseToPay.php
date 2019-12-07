<?php
namespace App\Http\Controllers\Response;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Objects\AuthKeys;
use App\Http\Models\Responses;
use Dnetix\Redirection\PlacetoPay;
use Session;
use Cache;

class ResponseToPay extends Controller
{
    public function receiveRequest()
    {
        $auth = new AuthKeys();
        $placetopay = new PlacetoPay([
            'login' => $auth::LOGIN_ID,
            'tranKey' => $auth::SECRET_KEY,
            'url' => $auth::URL_PLACETOPAY,
        ]);
        $response = $placetopay->query(Session::get('requestId'));
        if ($response->isSuccessful()) {
            if ($response->status()->isApproved()) {
                $this->storeInDB($response->status());
            } else {
                $this->storeInDB($response->status()); 
            }
        }
        $this->storeInCache($response->status());
        $this->setResponseMessageToView($response->status());
        return redirect('/');

    }

    public function storeInDB($object)
    {
        Responses::create([
            'status' => $object->status(),
            'reason' => $object->reason(),
            'message' => $object->message(),
            'date' => date("Y-m-d H:i:s", strtotime($object->date())),
            'requestId' => Session::get('requestId'),
        ]);
    }

    public function storeInCache($object)
    {
        Cache::put('status', $object->status(), now()->addMinutes(60));
        Cache::put('reason', $object->reason(), now()->addMinutes(60));
        Cache::put('message', $object->message(), now()->addMinutes(60));
        Cache::put('date', date("Y-m-d H:i:s", strtotime($object->date())), now()->addMinutes(60));
        Cache::put('requestId', Session::get('requestId'), now()->addMinutes(60));
    }

    public function setResponseMessageToView($object)
    {
        Session::flash('status', ($object->status() == 'APPROVED')?'OK':'FALLO');
        Session::flash('message', $object->message());
        Session::flash('date', date("Y-m-d H:i:s", strtotime($object->date())));
    }
}

