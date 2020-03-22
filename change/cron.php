<?php

/** 
*
* Vesta Web Interface
*
* Copyright (C) 2020 Carter Roeser <carter@cdgtech.one>
* https://cdgco.github.io/VestaWebInterface
*
* Vesta Web Interface is free software: you can redistribute it and/or modify
* it under the terms of version 3 of the GNU General Public License as published 
* by the Free Software Foundation.
*
* Vesta Web Interface is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with Vesta Web Interface.  If not, see
* <https://github.com/cdgco/VestaWebInterface/blob/master/LICENSE>.
*
*/

session_start();
$configlocation = "../includes/";
if (file_exists( '../includes/config.php' )) { require( '../includes/includes.php'); }  else { header( 'Location: ../install' ); exit();};
if(base64_decode($_SESSION['loggedin']) != 'true') { header('Location: ../login.php'); exit(); }

if(isset($cronenabled) && $cronenabled != 'true'){ header("Location: ../error-pages/403.html"); exit(); }


if (!isset($_POST['v_job'])) { header('Location: ../list/cron.php?error=1'); exit();}
elseif (!isset($_POST['v_min'])) { header('Location: ../edit/cron.php?error=1&job=' . $_POST['v_job']); exit();}
elseif (!isset($_POST['v_hour'])) { header('Location: ../edit/cron.php?error=1&job=' . $_POST['v_job']); exit();}
elseif (!isset($_POST['v_day'])) { header('Location: ../edit/cron.php?error=1&job=' . $_POST['v_job']); exit();}
elseif (!isset($_POST['v_month'])) { header('Location: ../edit/cron.php?error=1&job=' . $_POST['v_job']); exit();}
elseif (!isset($_POST['v_wday'])) { header('Location: ../edit/cron.php?error=1&job=' . $_POST['v_job']); exit();}
elseif (!isset($_POST['v_cmd'])) { header('Location: ../edit/cron.php?error=1&job=' . $_POST['v_job']); exit();}

$postvars = array(
    'hash' => $vst_apikey, 
    'user' => $vst_username,
    'password' => $vst_password,
    'returncode' => 'yes',
    'cmd' => 'v-change-cron-job',
    'arg1' => $username,
    'arg2' => $_POST['v_job'],
    'arg3' => $_POST['v_min'], 
    'arg4' => $_POST['v_hour'],
    'arg5' => $_POST['v_day'], 
    'arg6' => $_POST['v_month'],
    'arg7' => $_POST['v_wday'],
    'arg8' => $_POST['v_cmd']
);

print_r($postvars);
$curl0 = curl_init();

curl_setopt($curl0, CURLOPT_URL, $vst_url);
curl_setopt($curl0, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl0, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl0, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl0, CURLOPT_POST, true);
curl_setopt($curl0, CURLOPT_POSTFIELDS, http_build_query($postvars));
        
$r1 = curl_exec($curl0);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body class="fix-header">
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
            </svg>
        </div>


        <form id="form" action="../edit/cron.php?job=<?php echo $_POST['v_job']; ?>" method="post">
            <?php 
                echo '<input type="hidden" name="returncode" value="'.$r1.'">';
            ?>
        </form>
        <script type="text/javascript">
            document.getElementById('form').submit();
        </script>
    </body>
    <script src="../plugins/components/jquery/jquery.min.js"></script>
</html>
