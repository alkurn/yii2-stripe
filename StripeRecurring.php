<?php

/**
 * @copyright Copyright Ganesh Alkurn, 2017
 * @license https://github.com/alkurn/yii2-stripe/LICENSE
 * @link https://github.com/alkurn/yii2-stripe#readme
 */

namespace alkurn\stripe;

use Yii;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Subscription;

/**
 * Yii stripe component.
 *
 * @author Ganesh Alkurn <ganesh.alkurn@gmail.com>
 */
class StripeRecurring extends Stripe
{


    public function createCharge($request)
    {
        Stripe::setApiKey(Yii::$app->stripe->privateKey);

        $customer = Customer::create(['source' => $request['token'], 'email' => $request['email']]);
        $orgRequest = [];

        if($customer){

            $orgRequest['customer'] = $customer->id;
            if(isset($request['trial_from_plan'])){
                $orgRequest['trial_from_plan'] = true;
            }
            if(isset($request['items'])){
                $orgRequest['items'] = $request['items'];
            }
            if(isset($request['setup_fee'])){
                $this->setupFee($customer->id, $request['setup_fee']);
            }
        }

        return Subscription::create($orgRequest);
    }

    public function setupFee($customer_id, $fee){

        return \Stripe\InvoiceItem::create([
            "customer" => $customer_id,
            "amount" => (int) $fee,
            "currency" => "usd",
            "description" => "One-time setup fee"
        ]);
    }

    public function subscriptionStatus($subscription_id)
    {
        Stripe::setApiKey(Yii::$app->stripe->privateKey);
        $sub = Subscription::retrieve($subscription_id);
        return $sub;
    }
    public function cancelSubscription($subscription_id)
    {
        Stripe::setApiKey(Yii::$app->stripe->privateKey);
        $sub = Subscription::retrieve($subscription_id);
        return ($sub) ? $sub->cancel() : false;
    }

    public function switchSubscription($subscription_id, $plan)
    {
        if(!$plan) return false;
        Stripe::setApiKey(Yii::$app->stripe->privateKey);
        $sub = Subscription::retrieve($subscription_id);
        $res = Subscription::update($subscription_id, [
            'cancel_at_period_end' => false,
            'items' => [
                [
                    'id' => $sub->items->data[0]->id,
                    'plan' => $plan,
                ],
            ],
        ]);
        return ($res) ? true : false;
    }
}

