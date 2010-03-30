<?php
/*
Helper functions for processing months...
*/

/* In: month field from database
out: month field in bibtex format, assuming that it will be exported with additional braces around it! (that means that the export may look like this: }#nov#{    ) */
function formatMonthBibtex($month) {
    $output = $month;
    //replace braced quotes by AIGSTR
    $output = preg_replace('/\\{\\"\\}/', AIGSTR, $output);
    //replace remaining quotes "..." by }#...#{ 
    $output = preg_replace('/\\"([^\\"]*)\\"/', '}#$1#{', $output);
    //replace AIGSTR by unbraced quotes
    $output = preg_replace('/'.AIGSTR.'/', '"', $output);
    return $output;
}

/* In: month field from database
out: month field in bibtex format, assuming that it will be shown in an edit form */
function formatMonthBibtexForEdit($month) {
    $output = formatMonthBibtex('{'.$month.'}');
    //remove intial }# if any
    $output = preg_replace('/^\\{\\}\\#/', '', $output);
    //remove sufgfix #{ if any
    $output = preg_replace('/\\#\\{\\}\z/', '', $output);
    if ($output == '{}') {
        $output = '';
    }
    return $output;
}

/* In: month field from database.
Out: month field formatted in text format, for display on screen or for export to RIS / RTF / etc */
function formatMonthText($month) {
    $output = $month;
    //replace braced quotes by AIGSTR
    $output = preg_replace('/\\{\\"\\}/',AIGSTR,$output);
    //replace month quotes "..." by month names
    foreach (getMonthsInternal() as $abbrv=>$full) {
        $output = preg_replace('/\\"'.$abbrv.'\\"/', $full, $output);
    }
    //replace REMAINOING (UNKNOWN MACROS) by the macro name if it is an unknown macro...
    $output = preg_replace('/\\"([^\\"]*)\\"/', '$1', $output);
    //replace AIGSTR by unbraced quotes
    $output = preg_replace('/'.AIGSTR.'/', '"', $output);
    return $output;
}

function getMonthsInternal() {
    return array(''=>'','jan'=>__('January'), 'feb'=>__('February'), 'mar'=>__('March'), 'apr'=>__('April'), 'may'=>__('May'), 'jun'=>__('June'), 'jul'=>__('July'), 'aug'=>__('August'), 'sep'=>__('September'), 'oct'=>__('October'), 'nov'=>__('November'), 'dec'=>__('December'));
}


//__END__