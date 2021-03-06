<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
views/export/bibtex

displays bibtex for given publications

input parameters:
nonxref: map of [id=>publication] for non-crossreffed-publications
xref: map of [id=>publication] for crossreffed-publications

*/
if (!isset($header)||($header==null))$header='';

$result = '
%'.sprintf(__('BibTeX export from %s'), getConfigurationSetting("WINDOW_TITLE")).'
%'.date('l d F Y h:i:s A').'
'.$header.'

';


$this->load->helper('export');
foreach ($nonxrefs as $pub_id=>$publication) {
    $result .= getBibtexForPublication($publication)."\n";
}
if (count($xrefs)>0) $result .= "\n\n".__('crossreferenced publications').": \n";
foreach ($xrefs as $pub_id=>$publication) {
    $result .= getBibtexForPublication($publication)."\n";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>        
  <body>
    <pre>
<?php
    echo $result;
?>
    </pre>
  </body>
</html>
<?php


//__END__
