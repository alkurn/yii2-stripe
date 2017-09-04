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

    /**
 * Yii stripe component.
 *
 * @author Ganesh Alkurn <ganesh.alkurn@gmail.com>
 */
class StripeRecurring extends Stripe {


    public function createCharge($request){

        Stripe::setApiKey(Yii::$app->stripe->privateKey);

        $customer = Customer::create(array(
                'source'  => $request['token'],
                'email' => $request['email'],
                'plan'  => $request['plan']
            )
        );

        $request['customer']    =  $customer->id;

        return Charge::create($request);
    }

    

}

