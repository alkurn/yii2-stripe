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

/**
 * Yii stripe component.
 *
 * @author Ganesh Alkurn <ganesh.alkurn@gmail.com>
 */
class StripeCharge extends Stripe {


    public function createCharge($request){

        Stripe::setApiKey(Yii::$app->stripe->privateKey);
        return Charge::create($request);
    }

    public function retriveCharge($id){
        Stripe::setApiKey(Yii::$app->stripe->privateKey);
        return Charge::retrieve($id);
    }
}

