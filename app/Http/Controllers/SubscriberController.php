<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Subscriber;

class SubscriberController extends Controller
{
    public function subscribe(Request $request) {
    	
	    $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email'
        ])->validate();

		$subscriber = Subscriber::create($request->input());

		if($subscriber) {
			return response()->json(['message' => 'Thank you for your subscription!']);
		} else {
			return response()->json(['message' => 'We are unable to store your subscription.'],404);
		}
    }

    public function unsubscribe(Request $request) {
    	$subscriber = Subscriber::where('email', $request->input('email'))->first();

    	if($subscriber) {
    		$subscriber->delete();
    		return response()->json(['message' => 'You have been unsubscribe from our newsletter']);
    	} else {
    		return response()->json(['message' => 'Sorry, unable to find any subcriber based on email given.'],404);
    	}
    	
    	return redirect('/');
    }
}
