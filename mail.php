<?php

//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

include($_SERVER['DOCUMENT_ROOT'] . '/classes/v2/PrideIntegrate.php');

date_default_timezone_set('Europe/Moscow');

if (isset($_POST)) {
	
		$email = $_POST['email'];
		$email = preg_replace('/\s/', '', $email);
		$email = mb_strtolower($email); // преобразовываем в нижний регистр
		
		file_put_contents(__DIR__ . '/mails.txt', $_POST['name'].';'.$_POST['email'].';'.$_POST['phone'].';'.$_POST['utm_medium'].';'.$_POST['utm_source'].';'.$_POST['utm_content'].';'.$_POST['utm_group'].';'.$_POST['utm_term'].';'.$_POST['utm_campaign'].';'.$_POST['gcpc'].';'.$_POST['gcao'].';'.$_POST['referer'].';'.date('Y-m-d H:i:s')."\n", FILE_APPEND);

		// ивент первый, второй пакет
        $pride = new PrideIntegrate('f7a20c6a-e411-41e3-af0d-9ce2db75ed94', '7714b450-9263-44b6-9d40-445ac2c5bf2c');
		//$pride->executeRequest();
	
        //echo $_POST['name'];
        //echo $_POST['email'];
        //echo $_POST['phone'];
    

//	MAIL
    $name = $_POST['name'];
    
    $phone = $_POST['phone'];
    $ip = $_POST['ip'];
    $utm_campaign = $_POST['utm_campaign'];
    $utm_medium = $_POST['utm_medium'];
    $utm_source = $_POST['utm_source'];
    $utm_content = $_POST['utm_content'];
    $utm_group = $_POST['utm_group'];
    $utm_term = $_POST['utm_term'];
    $gcpc = $_POST['gcpc'];
    $gcao = $_POST['gcao'];
    $referer = $_POST['referer'];
    $product = 2666531;
    $suma = 500;
    $productname = "Миллион на Своем Деле";

    $data = date('Y-m-d H:i:s', time() + (60*60*3));

    // проверяем есть ли у этого пользователя уже созданный заказ в этот день
    define("DB_HOST", "sql596.your-server.de");
    define("DB_LOGIN", "artem_oplata");
    define("DB_PASSWORD", "8NX8y7Y2J7q323Df");
    define("DB_NAME", "artem_oplata");

    $con = mysqli_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die(mysqli_error());

    mysqli_select_db($con, DB_NAME) or die(mysqli_error());

    mysqli_query($con, "SET NAMES 'utf8'");
    mysqli_query($con, "SET CHARACTER SET 'utf8'");
    mysqli_query($con, "SET SESSION collation_connection = 'utf8_general_ci'");

    // делаем выборку с базы
    $zakaz = mysqli_query($con,
        " 
					SELECT * from zakaz where email = '" . $email . "' and product = '" . $product . "' ORDER BY `data` DESC
					") or die(mysqli_error());

    // переменная чтобы не писать по нескольку раз код. Если i принимает значение 1, то создаем заказ и пишем в базу
    $i = 0;

    if ($row = mysqli_fetch_assoc($zakaz)) {

        // пишем для сравнения дату, чтобы проверить есть ли заказ в этот день
        $data2 = date('Y-m-d H:i:s', strtotime("-27 hours"));

        if ($row['data'] <= $data2)
            $i = 1;

           // echo "заказ есть";

           // echo $row['data'];

    } else
        $i = 1;

    if ($i == 1) {

        //echo $email;

//	GETCOURSE

	

       $accountName = 'nesterenko';
        $secretKey = 'Se626LXuMfDP38Mgs6VRaMff7u877J2DjE30V9d3aet7FhaDNkbmu7Mt1BuJH295CSQlrkpdbZnD8xHoBgDTNPmlk2zBLYzQKY4enyHpJefqgsF3pdq32f4ro4RThHAf';

        $deal = array();
        $deal['user']['email'] = $email;
        $deal['user']['first_name'] = $name;
        $deal['user']['phone'] = $phone;
        //$user['user']['group_name'] = Array ("apibiggamemlm3friends");
        $deal['session']['utm_campaign'] = $utm_campaign;
        $deal['session']['utm_medium'] = $utm_medium;
        $deal['session']['utm_source'] = $utm_source;
        $deal['session']['utm_content'] = $utm_content;
        $deal['session']['utm_group'] = $utm_group;
        $deal['session']['utm_term'] = $utm_term;
        $deal['session']['gcao'] = $gcao;
        $deal['session']['gcpc'] = $gcpc;
        $deal['session']['referer'] = $referer;
        $deal['system']['refresh_if_exists'] = 1;
        $deal['deal']['deal_status'] = "new";
        $deal['deal']['offer_code'] = $product;
        $deal['deal']['deal_cost'] = $suma;

        $json = json_encode($deal);
        $base64 = base64_encode($json);

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, 'https://' . $accountName . '.getcourse.ru/pl/api/deals');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, 'action=add&key=' . $secretKey . '&params=' . $base64);
            $out = curl_exec($curl);
            // echo $out;
            curl_close($curl);
        } else {
            echo 'Failed initialization';
        }

        // заносим заказ в базу, чтобы в след раз не создавался если человек повторно нажмет кнопку

        $zakaz = mysqli_query($con,
            " 
					INSERT INTO zakaz (name, email, phone, product, suma, productname,data) VALUES ('$name','$email','$phone','$product','$suma','$productname','$data')
				  
					") or die(mysqli_error());
		
		$order = new PrideCrmOrder();
        $order->account = PrideCrmApi::ACCOUNT;
        $order->currency = PrideCrmApi::CURRENCY;
        $order->amount = (float)$suma;
        $order->product_id = $product;
        $order->product_name = $productname;
        $order->name = $name;
        $order->phone = $phone;
        $order->email = $email;
        $order->order_id = 'rd'.mysqli_insert_id($con);
        $order->status = 'new';  // status: new OR paid

        $pride->createCrmOrderByOrder($order);
		
		
    }

$utm = '';

    if(isset($_POST['utm_campaign']) and !$_POST['utm_campaign'] == '')
    $utm = '&utm_campaign='.$_POST['utm_campaign'].'';

    if(isset($_POST['utm_medium']) and !$_POST['utm_medium'] == '')
    $utm = '&utm_medium='.$_POST['utm_medium'].''.$utm.'';  

    if(isset($_POST['utm_source']) and !$_POST['utm_source'] == '')
    $utm = '&utm_source='.$_POST['utm_source'].''.$utm.''; 
    
    if(isset($_POST['utm_content']) and !$_POST['utm_content'] == '')
    $utm = '&utm_content='.$_POST['utm_content'].''.$utm.'';   

    if(isset($_POST['utm_group']) and !$_POST['utm_group'] == '')
    $utm = '&utm_group='.$_POST['utm_group'].''.$utm.'';  

     if(isset($_POST['utm_term']) and !$_POST['utm_term'] == '')
    $utm = '&utm_term='.$_POST['utm_term'].''.$utm.'';    
    
    if(isset($_POST['gcao']) and !$_POST['gcao'] == '')
    $utm = '&gcao='.$_POST['gcao'].''.$utm.''; 

    if(isset($_POST['gcpc']) and !$_POST['gcpc'] == '')
    $utm = '&gcpc='.$_POST['gcpc'].''.$utm.'';  

    $utm = 'price='.$suma.'&productname='.$productname.'&name='.$name.'&email='.$email.'&phone='.$phone.''.$utm.'';

    header('Location: zayavka.php?'.$utm.'');
}