<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Subscription;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function registerSubscription(Request $request, $clientId) {
        // Client passes the data inside this validator.
        $validator = Validator::make($request->all(), [
            'subscription_status' => 'required|int',
            'subscription_mode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Generate subscription_id as UUID
        $subscriptionId = Str::uuid();

        // subscription_start_date as the current date
        $subscriptionStartDate = Carbon::now();

        // Determine subscription_end_date based on subscription_mode
        $subscriptionMode = $request->input('subscription_mode');
        $subscriptionEndDate = null;

        if ($subscriptionMode === 'Monthly') {
            $subscriptionEndDate = $subscriptionStartDate->copy()->addDays(30);
        } elseif ($subscriptionMode === 'Annual') {
            $subscriptionEndDate = $subscriptionStartDate->copy()->addYear();
        } else {
            return response()->json(['error' => 'Invalid subscription mode'], 400);
        }

        // Assuming you have a Subscription model and table
        $subscription = new Subscription();
        $subscription->subscription_id = $subscriptionId;
        $subscription->client_id = $clientId;
        $subscription->subscription_status = $request->input('subscription_status');
        $subscription->subscription_start_date = $subscriptionStartDate;
        $subscription->subscription_end_date = $subscriptionEndDate;
        $subscription->save();

        return response()->json(['message' => 'Your payment has been successfully processed!'], 201);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
