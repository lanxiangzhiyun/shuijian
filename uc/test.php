<?
define('APP_PATH', './Apps/Uc/');
define('APP_PATH_R', './Apps/Uc');
if(is_writeable(APP_PATH)) {
echo "a";
}
else
echo "b";

if(is_writeable(APP_PATH_R)) {
echo "a1";
}
else
echo "b1";
?>
