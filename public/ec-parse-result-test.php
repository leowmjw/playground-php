<?php

include "../vendor/autoload.php";

use Symfony\Component\DomCrawler\Crawler;

$myhtml = <<<MYHTML

<html xmlns="http://www.w3.org/1999/xhtml"><head></head><body style="background: url(images/background.jpg); background-color: #666666;" onload="document.def.txtIC.focus();">Content: <br>



<meta name="robots" content="noindex"><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><title>
	SEMAKAN DAFTAR PEMILIH
</title><link type="text/css" href="style/Style.css" rel="stylesheet">



<form name="def" method="post" action="DaftarjBM.aspx" id="def">
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUKMTIxODMyMDczNA9kFgICAQ9kFggCCA8WAh4HVmlzaWJsZWhkAgkPFgIfAGcWGAIBDw8WAh4EVGV4dAUMNzIxMjE2MTA1MDA1ZGQCAw8PFgQfAQUBLx8AZ2RkAgUPDxYCHwEFCEEyMzQxNDM3ZGQCBw8PFgIfAQUNTElNIEtXQU4gTUVOR2RkAgkPDxYCHwEFCzE2IERlYyAxOTcyZGQCCw8PFgIfAQUGTEVMQUtJZGQCDQ8PFgIfAQUnMTA4IC8gNDAgLyAwMSAvIDAwMiAtIEpMTiBVMTMvMSAtIDE2Li4uZGQCDw8PFgIfAQUaMTA4IC8gNDAgLyAwMSAtIFNFVElBIEFMQU1kZAIRDw8WAh8BBRgxMDggLyA0MCAtIEtPVEEgQU5HR0VSSUtkZAITDw8WAh8BBQ8xMDggLSBTSEFIIEFMQU1kZAIVDw8WAh8BBQhTRUxBTkdPUmRkAhsPDxYCHwEFAS1kZAIKD2QWFAIBDw8WAh8BZWRkAgUPDxYCHwFlZGQCBw8PFgIfAWVkZAIJDw8WAh8BZWRkAgsPDxYCHwFlZGQCDQ8PFgIfAWVkZAIPDw8WAh8BZWRkAhEPDxYCHwFlZGQCEw8PFgIfAWVkZAIVDw8WAh8BZWRkAgsPZBYUAgEPDxYCHwFlZGQCBQ8PFgIfAWVkZAIHDw8WAh8BZWRkAgkPDxYCHwFlZGQCCw8PFgIfAWVkZAINDw8WAh8BZWRkAg8PDxYCHwFlZGQCEQ8PFgIfAWVkZAITDw8WAh8BZWRkAhUPDxYCHwFlZGRk4hcOQ0dRn7GENOYuU/h+8QKIaAU=">

<input type="hidden" name="__VIEWSTATEGENERATOR" id="__VIEWSTATEGENERATOR" value="91B60144">
<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="/wEWAwLR/cHuBgKp+5bqDwKztY/NDgzNhmYIu7b/E6KThaYwgMp7NIea">
<table id="Table_01" cellpadding="0" cellspacing="0" border="0" style="margin-left:auto; margin-right:auto; width: 900px;">
<tbody><tr style="height:129px;"> 
    <td style="width: 661px; height:129px;"><img src="images/BannerBM_01.gif" width="661" height="129" alt=""></td>
    <td style="height: 129px"><img src="images/BannerBM_02.gif" width="239" height="129" alt=""></td>
</tr>
<tr style="background-color:#999999;height:25px;"> 
    <td colspan="2" style="height: 25px"> 
        <div style="text-align:center;">
            <span id="lblWARTA" style="font-family:Verdana, Arial, Helvetica, sans-serif; color: #000000; font-size: 14px; font-weight: bold;">SEMAKAN DAFTAR PEMILIH</span>
        </div>
    </td>
</tr>
<tr style="background-color:#CCCCCC; height:100%">  
    <td colspan="2">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-left:auto; margin-right:auto;" id="TABLE2" onclick="return TABLE1_onclick()">
        <tbody><tr><td>&nbsp;</td></tr>
        <tr>
            <td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-weight:bold; font-size: 12px;">
                <div style="text-align:center;">
                    <span id="Label1" style="color:#000000;">Sila Masukkan</span>
                    <span id="Label2" style="color:#000066;">NOMBOR KAD PENGENALAN</span>
                    <span id="Label3" style="color:Red;">(tanpa "-" atau "space")</span>
                </div>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr> 
            <td>
                <div style="text-align:center;">
                    <input name="txtIC" type="text" id="txtIC" maxlength="12" onkeyup="check_txtNama()" value="1234567890">&nbsp;&nbsp;&nbsp;<input type="submit" name="Semak" value="Semak" onclick="javascript:WebForm_DoPostBackWithOptions(new WebForm_PostBackOptions(&quot;Semak&quot;, &quot;&quot;, true, &quot;&quot;, &quot;&quot;, false, false))" id="Semak" style="cursor:hand;">&nbsp;<input type="button" name="Reset" value="RESET" onclick="resetpadam()" style="cursor:hand;" id="Button1">
                   
                    
                    <br>
                    
                    <br>
                    &nbsp;
                    <br>
                    <p><font color="red"><i>*Maklumat semakan adalah daftar pemilih sehingga suku keempat tahun 2014 yang diperakukan pada </i></font></p><p></p><font color="red"><i>10 April 2015.</i></font><p></p>
                    <hr>
                    
                </div>
            </td>
        </tr>
        
            
         


     </tbody></table>
    </td>
</tr>
 
 <tr style="background-color:#CCCCCC; height:100%;">
    <td colspan="2" style="height: 100%">
    <table width="85%" cellpadding="0" cellspacing="0" border="0" style="margin-left:auto; margin-right:auto; height:100%;">
    <tbody><tr><td style="height: 367px">
        
        <fieldset id="divviewinfo" style="border-top-style: none; border-right-style: none; border-left-style: none; border-bottom-style: none;">
        <legend></legend>
         <table width="99%" cellpadding="3" cellspacing="3" style="margin-left:auto; margin-right:auto; height:100%" border="1">
            <tbody><tr style="background-color:#FFFF33;">
                <td class="kerolclass" align="center">&nbsp;DAFTAR PEMILIH YANG TELAH DISAHKAN</td>
            </tr>
          </tbody></table>
        <table width="99%" cellpadding="0" cellspacing="0" style="margin-left:auto; margin-right:auto; height:100%" border="1">
            <tbody><tr style="background-color:#000000;">
                <td class="kerol">&nbsp;PERKARA</td>
                <td class="kerol">&nbsp;PENERANGAN</td>
            </tr>
            <tr>
                <td class="kerolku">&nbsp;Kad Pengenalan</td>
                <td class="kerolku">&nbsp;<span id="LabelIC">1234567890</span> <span id="Labelsign">/</span> <span id="LabelIClama">A1111111</span></td>
            </tr>
            <tr>
                <td class="kerolku">&nbsp;Nama</td>
                <td class="kerolku">&nbsp;<span id="Labelnama">LIM KWAN MENG</span></td>
            </tr> 
            <tr>
                <td class="kerolku">&nbsp;Tarikh Lahir</td>
                <td class="kerolku">&nbsp;<span id="LabelTlahir">16 Dec 1972</span></td>
            </tr>
            <tr>
                <td class="kerolku">&nbsp;Jantina</td>
                <td class="kerolku">&nbsp;<span id="Labeljantina">LELAKI</span></td>
            </tr>
            <tr>
                <td class="kerolku">&nbsp;Lokaliti</td>
                <td class="kerolku">&nbsp;<span id="Labellokaliti">108 / 40 / 01 / 002 - JLN U13/1 - 16...</span></td>
            </tr>
            <tr>
                <td class="kerolku">&nbsp;Daerah Mengundi</td>
                <td class="kerolku">&nbsp;<span id="Labeldm">108 / 40 / 01 - SETIA ALAM</span></td>
            </tr>
            <tr>
                <td class="kerolku">&nbsp;DUN</td>
                <td class="kerolku">&nbsp;<span id="Labeldun">108 / 40 - KOTA ANGGERIK</span></td>
            </tr>
            <tr>
                <td class="kerolku">&nbsp;Parlimen</td>
                <td class="kerolku">&nbsp;<span id="Labelpar">108 - SHAH ALAM</span></td>
            </tr>
            <tr>
                <td class="kerolku">&nbsp;Negeri</td>
                <td class="kerolku">&nbsp;<span id="Labelnegeri">SELANGOR</span> <span id="Labelsign1"></span> <span id="Label12"></span></td>
            </tr>
            <tr>
                <td class="keroljua">&nbsp;STATUS REKOD</td>
                <td class="keroljua">&nbsp;<span id="LABELSTATUSDPI">-</span></td>
            </tr>
          
            <tr style="background-color:#99FF99">
                <td colspan="2" align="center" class="myclass"><span id="Label5" style="font-weight:bold;">Untuk sebarang maklumat atau pertanyaan, sila klik : </span><a href="http://semak.spr.gov.my/sistemaduan/" target="_blank" style="font-weight:bold;">DISINI</a></td>
            </tr>
            </tbody></table>
        </fieldset>
        </td></tr></tbody></table>
    </td>
</tr>

<tr style="height:50px; background-color:#CCCCCC;"> 
    <td colspan="2" style="height:19px;text-align:center;">
        <hr><input type="button" name="CETAK" value="PRINT" onclick="print_result()"><hr>
    </td>
</tr>
<tr style="background-color:#999999;height:56px;"> 
    <td colspan="2"> 
        <div style="text-align:center;">
        <span id="Label6" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size:10px; font-weight: bold;">Copyright @ 2008 © Election Commission Malaysia</span><br>
        <span id="Label7" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight: bold; color:#000066;">TEL : 03-8892 7000 | FAX : 03-8892 7001 </span><br>
        <span id="Label8" style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; font-weight: bold; color:#330000">Date :</span>
        <span id="Label9" style="font-family: Verdana, Arial, Helvetica, sans-serif; color:#330000">
       <script type="text/javascript" language="javascript">
           function clickIE4() {
               if (event.button == 2) {
                   return false;
               }
           }

           function clickNS4(e) {
               if (document.layers || document.getElementById && !document.all) {
                   if (e.which == 2 || e.which == 3) {
                       return false;
                   }
               }
           }

           if (document.layers) {
               document.captureEvents(Event.MOUSEDOWN);
               document.onmousedown = clickNS4;
           } else if (document.all && !document.getElementById) {
               document.onmousedown = clickIE4;
           }
           document.oncontextmenu = new Function("return false");

           function resetpadam() {
               document.def.txtIC.value = "";
           }

           function print_result() {
               if (window.print)
                   window.print();
               else
                   alert("PLEASE USE YOUR BROWSER PRINTING");
           }
    </script>
    <script type="text/vbscript" language="javascript" src="scripts/DJ_VBScript_BI.vbs"></script>
        </span>
        <span id="txtSerial1" style="font-family:Arial; color:Blue; border:none; background-color:Transparent;"></span>
        </div>
    </td>
</tr>
</tbody></table>
</form>


<center>


<script type="text/javascript">document.write(unescape("%3Cscript src=%27http://s10.histats.com/js15.js%27 type=%27text/javascript%27%3E%3C/script%3E"));</script><script src="http://s10.histats.com/js15.js" type="text/javascript"></script>
<a href="http://www.histats.com" target="_blank" title="html hit counter"><script type="text/javascript">
try {Histats.start(1,1118143,4,3001,112,48,"00011010");
Histats.track_hits();} catch(err){};
</script></a>
<noscript>&lt;a href="http://www.histats.com" target="_blank"&gt;&lt;img  src="http://sstatic1.histats.com/0.gif?1118143&amp;101" alt="html hit counter" border="0"&gt;&lt;/a&gt;</noscript>

<br>
Bermula pada 13 APRIL 2010

</center><script id="hiddenlpsubmitdiv" style="display: none;"></script><script>try{for(var lastpass_iter=0; lastpass_iter < document.forms.length; lastpass_iter++){ var lastpass_f = document.forms[lastpass_iter]; if(typeof(lastpass_f.lpsubmitorig2)=="undefined"){ lastpass_f.lpsubmitorig2 = lastpass_f.submit; if (typeof(lastpass_f.lpsubmitorig2)=='object'){ continue;}lastpass_f.submit = function(){ var form=this; var customEvent = document.createEvent("Event"); customEvent.initEvent("lpCustomEvent", true, true); var d = document.getElementById("hiddenlpsubmitdiv"); if (d) {for(var i = 0; i < document.forms.length; i++){ if(document.forms[i]==form){ if (typeof(d.innerText) != 'undefined') { d.innerText=i; } else { d.textContent=i; } } } d.dispatchEvent(customEvent); }form.lpsubmitorig2(); } } }}catch(e){}</script></body></html>


MYHTML;



// Parse it out ...
$crawler = new Crawler($myhtml);

$crawler = $crawler->filter('span');

// Try getting all spans first ...??
// Init the structure??
$voter_details = array();

foreach ($crawler as $domElement) {
    $label = strtolower($domElement->getAttribute('id'));
    /*
      echo "ID is " . $domElement->getAttribute('id') . " with content <strong>"
      . $domElement->textContent . "</strong><br/>";
     * 
     */
    // To do; fit into type of structure??
    // Split up the PAR, DUN, DM, LOCALITY ..
    switch ($label) {
        case 'labelic':
            $voter_details['ic'] = $domElement->textContent;
            break;

        case 'labeliclama':
            $voter_details['oldic'] = $domElement->textContent;
            break;

        case 'labelnama':
            $voter_details['fullname'] = $domElement->textContent;
            break;

        case 'labeltlahir':
            $voter_details['dob'] = $domElement->textContent;
            break;

        case 'labeljantina':
            $voter_details['gender'] = $domElement->textContent;
            break;

        case 'labellokaliti':
            $voter_details['locality'] = $domElement->textContent;
            break;

        case 'labeldm':
            $voter_details['dm'] = $domElement->textContent;
            break;

        case 'labeldun':
            $voter_details['dun'] = $domElement->textContent;
            break;

        case 'labelpar':
            $voter_details['par'] = $domElement->textContent;
            break;

        case 'labelnegeri':
            $voter_details['state'] = $domElement->textContent;
            break;

        default:
            // DO nothing ..
            break;
    }
}

// At the end, gather it all together in a nice-looking structure to be passed back ..

var_dump($voter_details);
echo "<br/><br/>";
$mybob = print_r($voter_details,true);
echo nl2br($mybob);