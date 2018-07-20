Yii2 Stripe Wrapper.
==========
Installation
--------------------------

The preferred way to install this extension is through http://getcomposer.org/download/.

Either run

```sh
php composer.phar require alkurn/yii2-stripe "dev-master"
```

or add

```json
"alkurn/yii2-stripe": "dev-master"
```

to the require section of your `composer.json` file.


Usage
--------------------------
Add a new component in main.php
```php
'components' => [
...
'stripe' => [
    'class' => 'alkurn\stripe\Stripe',
    'publicKey' => "pk_test_xxxxxxxxxxxxxxxxxxx",
    'privateKey' => "sk_test_xxxxxxxxxxxxxxxxxx",
    'ClientId' => "ca_xxxxxxxxxxxxxxxxxx",
],
...

```

To render simple checkout form just call the widget in the view, it will automatically register the scripts.
Check stripe documentation for more options.
```php
use alkurn\stripe\StripeCheckout;

<?= 
StripeCheckout::widget([
    'action' => '/',
    'name' => 'Yoga Trainer',
    'description' => 'Yoga Trainer ($40.00)',
    'amount' => 40,
    'image' => '/128x128.png',
]);
?>
```

Custom checkout form is an extended version of simple form, but you can customize the button (see buttonOptions) and handle token as you want (tokenFunction).
```php
use alkurn\stripe\StripeCheckoutCustom;

<?= 
StripeCheckoutCustom::widget([
    'action' => '/',
    'name' => 'Demo test',
    'description' => '2 widgets ($20.00)',
    'amount' => 2000,
    'image' => '/128x128.png',
    'buttonOptions' => [
        'class' => 'btn btn-lg btn-success',
    ],
    'tokenFunction' => new JsExpression('function(token) { 
                alert("Here you should control your token."); 
    }'),
    'openedFunction' => new JsExpression('function() { 
                alert("Model opened"); 
    }'),
    'closedFunction' => new JsExpression('function() { 
                alert("Model closed"); 
    }'),
]);
?>
```
Example of a custom form. StripeForm is an <b>extended ActiveForm</b> so you can perform validation of amount and other attributes you want. 
Use of <b>Jquery Payment library</b> is optional, you can disable format and validation and write your own implementation.
You can also change JsExpression for response and request handlers.

```php
use alkurn\stripe\StripeForm;

 <?php
 $form = StripeForm::begin([
             'tokenInputName' => 'stripeToken',
             'errorContainerId' => 'payment-errors',
             'brandContainerId' => 'cc-brand',
             'errorClass' => 'has-error',
             'applyJqueryPaymentFormat' => true,
             'applyJqueryPaymentValidation' => true,
             'options' => ['autocomplete' => 'on']
 ]);
 ?>

 <div class="form-group">
     <label for="number" class="control-label">Card number</label>
     <span id="cc-brand"></span>
     <?= $form->numberInput() ?>
 </div>

 <div class="form-group">
     <label for="cvc" class="control-label">CVC</label>
     <?= $form->cvcInput() ?>
 </div>

 <!-- Use month and year in the same input. -->
 <div class="form-group">
     <label for="exp-month-year" class="control-label">Card expiry</label>
     <?= $form->monthAndYearInput() ?>
 </div>

 <!-- OR in two separate inputs. -->
 <div class="form-group">
     <label for="exp-month" class="control-label">Month</label>
     <?= $form->monthInput() ?>
 </div>

 <div class="form-group">
     <label for="exp-year" class="control-label">Year</label>
     <?= $form->yearInput() ?>
 </div>

 <div id="payment-errors"></div>
 
 <?= Html::submitButton('Submit'); ?>
 
 <?php StripeForm::end(); ?>
 
 ```
 
 ```php
 
 <?php
 use alkurn\stripe\StripeForm;
 
 
 $provider = new StripeConnect([
     'clientId'          => '{stripe-client-id}',
     'clientSecret'      => '{stripe-client-secret}',
     'redirectUri'       => 'https://example.com/callback-url',
 ]);
 
 if (!isset($_GET['code'])) {
 
     // If we don't have an authorization code then get one
     $authUrl = $provider->getAuthorizationUrl();
     $_SESSION['oauth2state'] = $provider->getState();
     header('Location: '.$authUrl);
     exit;
 
 // Check given state against previously stored one to mitigate CSRF attack
 } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
 
     unset($_SESSION['oauth2state']);
     exit('Invalid state');
 
 } else {
 
     // Try to get an access token (using the authorization code grant)
     $token = $provider->getAccessToken('authorization_code', [
         'code' => $_GET['code']
     ]);
 
     // Optional: Now you have a token you can look up a users profile data
     try {
 
         // We got an access token, let's now get the user's details
         $account = $provider->getResourceOwner($token);
 
         // Use these details to create a new profile
         printf('Hello %s!', $account->getDisplayName());
 
     } catch (Exception $e) {
 
         // Failed to get user details
         exit('Oh dear...');
     }
 
     // Use this to interact with an API on the users behalf
     echo $token->getToken();
 }
 
```

