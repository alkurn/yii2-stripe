<?php

/**
 * @copyright Copyright Ganesh Alkurn, 2017
 * @license https://github.com/alkurn/yii2-stripe/LICENSE
 * @link https://github.com/alkurn/yii2-stripe#readme
 */

namespace alkurn\stripe;

use yii\base\Exception;

/**
 * Yii stripe component.
 *
 * @author Ganesh Alkurn <ganesh.alkurn@gmail.com>
 */
class Stripe extends \yii\base\Component {

    /**
     * @see Stripe
     * @var string Stripe's public key
     */
    public $publicKey;

    /**
     * @see Stripe
     * @var string Stripe's private key
     */
    public $privateKey;

    /**
     * @see Stripe
     * @var string Stripe's private key
     */
    public $clientId;

    /**
     * @see Stripe
     * @var string Stripe's redirect Url
     */
    public $redirectUri;

    /**
     * @see Init extension default
     */
    public function init() {
        if (!$this->publicKey) {
            throw new Exception("Stripe's public key is not set.");
        } elseif (!$this->privateKey) {
            throw new Exception("Stripe's private key is not set.");
        }
        elseif (!$this->clientId) {
            throw new Exception("Stripe's client id is not set.");
        }
        parent::init();
    }

}

