<?php

    include_once __DIR__ . "/../vendor/autoload.php";

    $outsideParam = [
        'first_name'    => 'Tame',
        'last_name'     => 'Developers',
        'age'           => 0,
    ];

    // This can be on just one file accesible to all forms request
    config_form([
        'error_type'    => true,
        'csrf_token'    => false,
        'class'         => [
            'error'     => 'custom_error_class',
            'success'   => 'custom_success_class'
        ]
    ]);

    //auto find request method if no param set
    $form = new \UltimateValidator\UltimateValidator($outsideParam);
    $form->submit([
        "string:current_password"   => 'Enter your old password',
        "string:new_password"       => 'Enter a new password',
        "string:retype_password"    => 'Retype new password',
        "string:retype_password:!==:{$form->old('new_password')}" => 'Password mis-match... Try again.' 
    ])->beforeSubmit(function(){

        print_r( 'beforeSubmit example usage'  );
    })->afterSubmit(function(){

        print_r("**afterSubmit usage example** <br>");
    })->error(function($response){ 

        // $response->echoJson(0, $response->message);
    })->success(function($response) use ($outsideParam){
        // access the form data
        $param = $response->param;
        
        // access parent scope data\ $data
        $attribute = $response->attribute;

        // the use keyword scope parameter
        $use = $outsideParam;

        $data['user_id']            = rand(10000, 99999);
        $data['retype_password']    = md5($param['retype_password']);

        // merge example
        $merge = $response->merge($response->param->toArray(), $data);

        // message
        $response->message = "Form submitted Successfully";

        print_r($param->retype_password);
        print_r($merge);
        // print_r($response->getForm());
        // print_r($attributeParam );
    });


?>


<!DOCTYPE html>
<head>
    <title>Form validation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<html>
    <body>

        <form method="post" action="<?= $_SERVER["PHP_SELF"];?>" class="form">
            <h2>Form sample</h2>
            <div class="errorMSg mb-5 <?= $form->getClass() ?>">
                <?= $form->getMessage() ?>
            </div>

            <!-- 
                Since we have turned the token verification off 
                Then we don't need to include the <?php csrf(); ?> with form anymore
            -->


            <div class="row">
                <div class="">
                    <label for="html">Current password</label>
                    <input type="text" name="current_password" value="<?= $form->old('current_password'); ?>">
                </div>
                
                <div class="">
                    <label for="html">New password</label>
                    <input type="text" name="new_password" value="<?= $form->old('new_password'); ?>">
                </div>
                
                <div class="">
                    <label for="html">Retype password</label>
                    <input type="text" name="retype_password" value="<?= $form->old('retype_password'); ?>">
                </div>

                <button type="submit" class="btn mt-2">Submit</button>
            </div>
        </form> 

    </body>
</html>