<?php
$code=$_GET['code'];
$nsukey=$_GET['nsukey'];
$ip='192.168.1.196:12138';
$ip='120.78.162.200:12138';
echo "<script>window.location.href='http://$ip/wShop/index.php/home/login/openid/code/$code/nsukey/$nsukey'</script>";