<?php require "./inc/session_start.php"; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php include "./inc/head.php"; ?>
    </head>
    <body>
        <?php

            if(!isset($_GET['vista']) || $_GET['vista']==""){
                $_GET['vista']="login";
            }


            if(is_file("./vistas/".$_GET['vista'].".php") && $_GET['vista']!="login" && $_GET['vista']!="404" && $_GET['vista']!="registro" && $_GET['vista']!="home_empleado" && $_GET['vista']!="home" && $_GET['vista']!="home_cliente"){

                /*== Cerrar sesion ==*/
                if((!isset($_SESSION['id']) || $_SESSION['id']=="") || (!isset($_SESSION['usuario']) || $_SESSION['usuario']=="")){
                    include "./vistas/logout.php";
                    exit();
                }

                //include "./inc/navbar.php";

                include "./vistas/".$_GET['vista'].".php";

                include "./inc/script.php";

            }else{
                if($_GET['vista']=="login"){
                    include "./vistas/login.php";
                    
                }else{
                    if($_GET['vista']=="registro"){
                        include "./vistas/registro.php";
                    }else{
                        if($_GET['vista']=="home_empleado"){
                            include "./inc/navbarE.php";
                            include "./inc/script.php";
                            include "./vistas/home_empleado.php";
                        }else{
                            if($_GET['vista']=="home"){
                                include "./inc/navbar.php";
                                include "./inc/script.php";
                                include "./vistas/home.php";
                            }else{
                                if($_GET['vista']=="home_cliente"){
                                    include "./inc/navbarC.php";
                                    include "./inc/script.php";
                                    
                                    include "./vistas/home_cliente.php";
                                }else{
                                    include "./vistas/404.php";
                                }
                            }
                        }
                    }
                 }
            }

        ?>
    </body>
</html>
