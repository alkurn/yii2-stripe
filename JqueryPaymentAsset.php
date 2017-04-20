<?php

/**
 * @copyright Copyright Ganesh Alkurn, 2015
 * @license https://github.com/alkurn/yii2-stripe/LICENSE
 * @link https://github.com/alkurn/yii2-stripe#readme
 */

namespace alkurn\stripe;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Jquery Payment Library js.
 *
 * @author Ganesh Alkurn <demin@trabeja.com>
 */
class JqueryPaymentAsset extends AssetBundle {

    public $sourcePath = '@bower/jquery.payment';
    public $js = [
        'lib/jquery.payment.js',
    ];

}
