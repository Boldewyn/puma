<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
views/export/formatted

displays osbib formatted data for given publications

input parameters:
nonxref: map of [id=>publication] for non-crossreffed-publications
xref: map of [id=>publication] for crossreffed-publications
header: not used here.
format: html|rtf|plain
style: APA | etc (available OSBib styles)
sort: year|author|title|type

*/
if (!isset($header)||($header==null))$header='';


$ext="txt";
$mime="text/plain";
$pre = "";
$post= "";
switch ($format) {
    case "html":
        $ext = "html";
        $mime="text/html";
        $newline = "<br/>\n";
        $pre = "<html>\n<body>\n";
        $post= "\n</body>\n</html>";
        break;
    case "sxw":
        $ext = "sxwNOTFINISHEDYET";
        //$mime="text/html";
        //$newline = "<br/>\n";
        //$pre = "<html>\n<body>\n";
        //$post= "\n</body>\n</html>";
        break;
    case "rtf":
        $this->load->helper('rtf');
        $rtf = new MINIMALRTF();
        $ext="rtf";
        $mime="application/rtf";
        $pre = $rtf->openRtf();//"{\\rtf1\\ansi\\uc1\\lang1033";
        $post= $rtf->closeRtf();//"}";
        break;
    default: 
        break;
}
$newline = "\n";
switch ($format) {
	case "html":
		$newline = "<br/>\n";
	break;
	case "rtf":
		$newline = " \\par\n";
	break;
	default:
		$newline = "\n";
	break;
}

$result = $pre;

$this->load->helper('export');
$this->load->helper('osbib');

$bibformat = new BIBFORMAT(APPPATH."include/OSBib/format/", TRUE);
foreach ($nonxrefs as $pub_id=>$publication) {
    if (!ini_get('safe_mode'))set_time_limit(5); // give an additional 2 seconds for every entry to be displayed
    $result .= getOSBibFormattingForPublication($publication,$bibformat,$style,$format);
    $result .= $newline;
}
foreach ($xrefs as $pub_id=>$publication) {
    if (!ini_get('safe_mode'))set_time_limit(5); // give an additional 2 seconds for every entry to be displayed
    $result .= getOSBibFormattingForPublication($publication,$bibformat,$style,$format);
    $result .= $newline;
}

$result .= $post;

// Load the download helper and send the file to your desktop
$this->load->helper('download');
//how to tell browser that encoding is utf8? it SEEMS the browser understands all by itself. If not, we should 
//introduce a 3rd param for force_download, which takes care of the utf 8 charset somehow

if ($format!='html') {
    ob_clean(); //DR: I DO NOT UNDERSTAND THIS!!! THE BUFFER CONTAINS A NEWLINE AND I CANNOT FIND WHERE IT COMES FROM!!!!
    //I AM VERY UNHAPPY TO SOLVE IT THIS WAY!!!!! BUT RTF CANNOT HANDLE AN INITIAL NEWLINE!!!!
    force_download(AIGAION_DB_NAME."_export_".date("Y_m_d").'.'.$ext, $result);
} else {
    header("Content-Type: text/html; charset=UTF-8"); 
    echo $result;
}

?>