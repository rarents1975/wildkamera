<html>
<head>
<title></title>

<?php 

// Konfigurationsdatei fuer Ort des Mandanten auslesen und Variablen setzen
$json=file_get_contents("../devadmin/config.txt");
$obj = json_decode($json);
$modus=$obj->{'modus'};
$ort=$obj->{'ort'};
$server=$obj->{'server'};
$titel=$obj->{'titel'};
$streamname=$obj->{'streamname'};
$streamkey=$obj->{'streamkey'};
$passwdcheck=$obj->{'passwdcheck'};
$passwd=$obj->{'password'};

if($passwdcheck=='true')
{
 echo "<meta http-equiv=\"refresh\" content=\"0; URL=index2-pw.php\">";
}
elseif ($passwdcheck=='false')
{
 echo "<meta http-equiv=\"refresh\" content=\"0; URL=index2-nopw.php\">";
}

?>

<!-- Start Open Web Analytics Tracker -->
<script type="text/javascript">
//<![CDATA[
var owa_baseUrl = '<?php echo $modus."://".$server."/owa/" ?>';
var owa_cmds = owa_cmds || [];
owa_cmds.push(['setSiteId', '4d1e8fbaae713a5d0d2d2fd3f12b2eca']);
owa_cmds.push(['trackPageView']);
owa_cmds.push(['trackClicks']);

(function() {
        var _owa = document.createElement('script'); _owa.type = 'text/javascript'; _owa.async = true;
        owa_baseUrl = ('https:' == document.location.protocol ? window.owa_baseSecUrl || owa_baseUrl.replace(/http:/, 'https:') : owa_baseUrl );
        _owa.src = owa_baseUrl + 'modules/base/js/owa.tracker-combined-min.js';
        var _owa_s = document.getElementsByTagName('script')[0]; _owa_s.parentNode.insertBefore(_owa, _owa_s);
}());
//]]>
</script>
<!-- End Open Web Analytics Code -->

</head>
<body>
</body>
</html>
