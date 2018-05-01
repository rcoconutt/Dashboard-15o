<?php

$ini = parse_ini_file('../config/app.ini');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    
    $url = $ini['uri'] . "CAT_USUARIO?transform=1";
    $url = $url . "&filter[]=USERNAME,eq," . test_input($_POST["user"]) . "&filter[]=CONTRASENA,eq," . md5(test_input($_POST["pass"])) . "&filter[]=ACTIVO=1";
    $url = $url . "&columns=ID_USUARIO,NOMBRE,USERNAME";
    $res = CallAPI($method, $url);
    
    if(count($res["CAT_USUARIO"]) > 0){
        startSession($res);
        if(isset($_POST["remember"])){
            $_SESSION["remember"] = 1;
        }
        header("Location: ../views/dashboard.html"); /* Redirect browser */
        exit();
    }else{
        /*echo '<script type="text/javascript">';
        echo "    alert('Usuario no valido')";
        echo '</script>';*/
        exit(header("Location: ../views/login.html?status=1"));
        
    }
}

function startSession($res){
    $_SESSION["ID_USUARIO"] = $res["ID_USUARIO"];
    $_SESSION["NOMBRE"] = $res["NOMBRE"];
    $_SESSION["USERNAME"] = $res["USERNAME"];
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  
  return $data;
}

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();
    
    switch ($method)
    {
        case "POST":
            
            curl_setopt($curl, CURLOPT_POST, 1);
            
            if ($data){
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    $result = curl_exec($curl);
    
    curl_close($curl);

    return json_decode($result, true);
}

