<?php
    require_once("c://xampp/htdocs/login/controller/homeController.php");
    $obj = new homeController();
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $confirmarContraseña = $_POST['confirmarContraseña'];
    $rut = $_POST['rut'];
    $error = "";
    function validarRUT($rut) {
        $rut = strtoupper(preg_replace('/[^0-9K]/', '', $rut));
        if (!preg_match('/^[0-9]{7,8}[0-9K]$/', $rut)) {
            return false;
        }
    
        $numero = substr($rut, 0, -1);
        $dv = substr($rut, -1);
        $suma = 0;
        $multiplo = 2;
    
        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += $numero[$i] * $multiplo;
            $multiplo = $multiplo < 7 ? $multiplo + 1 : 2;
        }
    
        $resto = $suma % 11;
        $dvCalculado = 11 - $resto;
    
        if ($dvCalculado == 11) {
            $dvCalculado = '0';
        } elseif ($dvCalculado == 10) {
            $dvCalculado = 'K';
        } else {
            $dvCalculado = (string) $dvCalculado;
        }
    
        return $dvCalculado === $dv;
    }
    
    if (empty($correo) || empty($contraseña) || empty($confirmarContraseña) || empty($rut)) {
        $error .= "<li>Todos los campos son obligatorios</li>";
        header("Location:signup.php?error=" . urlencode($error) . "&&correo=$correo&&rut=$rut");
        exit();
    }
    
    if (!validarRUT($rut)) {
        $error .= "<li>El RUT ingresado no es válido</li>";
        header("Location:signup.php?error=" . urlencode($error) . "&&correo=$correo&&rut=$rut");
        exit();
    }
    
    if ($contraseña !== $confirmarContraseña) {
        $error .= "<li>Las contraseñas no coinciden</li>";
        header("Location:signup.php?error=" . urlencode($error) . "&&correo=$correo&&rut=$rut");
        exit();
    }
    
    $obj = new homeController();
    if ($obj->guardarUsuario($correo, $contraseña, $rut) === false) {
        $error .= "<li>Error al guardar el usuario. El correo ya existe.</li>";
        header("Location:signup.php?error=" . urlencode($error) . "&&correo=$correo&&rut=$rut");
        exit();
    } else {
        header("Location:login.php");
        exit();
    }
    ?>