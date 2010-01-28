<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Helper for special character conversion
| -------------------------------------------------------------------
|
|   Provides several functions for special character conversion
|
|	Usage:
|       //load this helper:
|       $this->load->helper('theme'); 
|       //get available themes by name:
	findSpecialCharsInArray(&$array)
		returns true when special chars are found.

	findSpecialCharsInString(&$string)
		returns true when special chars are found.

	addSlashesToArray(&$array)
		addslashes to each element in the array.

	addHtmlEntitiesToArray(&$array)
		adds html entities to each element in the array.

	prettyPrintBibCharsFromArray(&$array)
		strips the special chars in an array and replaces by html special char

	prettyPrintBibCharsFromString(&$string)
		strips the special chars in a string and replaces by html special char

	stripBibCharsFromArray(&$array)
		strips the bibtex special chars from an array

	stripBibCharsFromString(&$string)
		strips the bibtex special chars from a string

	latinToBibCharsFromArray(&$array)
		converts latin chars to bibtex special chars form an array

	latinToBibCharsFromString(&$array)
		converts latin chars to bibtex special chars form a string

	quotesToHTMLFromArray(&$array)
		converts single and double quotes to their html equivalents

	quotesToHTMLFromString(&$string)
		converts single and double quotes to their html equivalents

	stripSlashesFromArray(&$array)
		stripslashes on each element in the array.

	stripHtmlEntitiesFromArray(&$array)
		strips html entities from each element in the array.

	function stripQuotesFromString($string)
		strips the " and ' character from a string and returns it

	getSpecialCharsArray()
		gets an array with regexps for finding special chars.

	getSpecialCharsReplaceArray()
		gets an array with regexps for replacing special chars.

	getHTMLSpecialCharsArray()
		gets an array with regexps for finding html special chars (quotes)

	getHTMLSpecialCharsReplaceArray()
		gets an array with the html codes for quotes.

	getLatinCharsArray()
		gets an array with latin chars that can be replaced by bibtex

	getLatinCharsReplaceArray()
		gets an array with bibtex replace chars for latin chars.

*/

appendErrorMessage('You just loaded the specialchar helper. This is not good; it should be replaced by a combination of the bibtexutf8 (for converting bibtex codes to utf) helper and the utf8_to_ascii library (for transliteration, generating clean ascii for cleanname and cleantitle rows) available in include/...)');
    
    
function findSpecialCharsInArray(&$array)
{
	$bFound = false;
	$keys = array_keys($array);
	foreach ($keys as $key)
	{
		$bFound = findSpecialCharsInString($array[$key]);
		if ($bFound)
		{
			return true;
		}
	}
	return false;
}

function findSpecialCharsInString(&$string)
{
	$specialChars = getSpecialCharsArray();
	foreach ($specialChars as $char)
	{
		if (preg_match($char, $string))
		{
			return true;
		}
	}
	return false;
}

function addSlashesToArray($array)
{
	$keys = array_keys($array);
	foreach ($keys as $key)
	{
		$array[$key] = trim(addslashes($array[$key]));
	}
	return $array;
}

function addHtmlEntitiesToArray($array)
{
	$keys = array_keys($array);
	foreach ($keys as $key)
	{
		$array[$key] = htmlentities($array[$key], ENT_QUOTES, 'utf-8');
	}
	return $array;
}

function prettyPrintBibCharsFromArray($array)
{
	$keys = array_keys($array);
	foreach ($keys as $key)
	{
		$array[$key] = prettyPrintBibCharsFromString($array[$key]);
	}
	return $array;
}

function prettyPrintBibCharsFromString($string)
{
	$specialBibChars = getSpecialCharsArray();
	$replaceChars		= getSpecialCharsReplaceArray();
	//$replaceChars = "$1";

	$string = preg_replace($specialBibChars, $replaceChars, $string);
	return $string;
}

function stripBibCharsFromArray($array)
{
	$keys = array_keys($array);
	foreach ($keys as $key)
	{
		$array[$key] = stripBibCharsFromString($array[$key]);
	}
	return $array;
}

function stripBibCharsFromString($string)
{
	$specialBibChars = getSpecialCharsArray();
	$replaceChars = "$1";

	$string = preg_replace($specialBibChars, $replaceChars, $string);
	return $string;
}

function latinToBibCharsFromArray($array)
{
	$keys = array_keys($array);
	foreach ($keys as $key)
	{
		$array[$key] = latinToBibCharsFromString($array[$key]);
	}
	return $array;
}

function latinToBibCharsFromString($string)
{
	$specialLatinChars = getLatinCharsArray();
	$replaceChars		= getLatinCharsReplaceArray();

	$string = preg_replace($specialLatinChars, $replaceChars, $string);
	return $string;
}

function quotesToHTMLFromArray($array)
{
	$keys = array_keys($array);
	foreach ($keys as $key)
	{
		$array[$key] = quotesToHTMLFromString($array[$key]);
	}
	return $array;
}

function quotesToHTMLFromString($string)
{
	$HTMLSpecialCharsArray = getHTMLSpecialCharsArray();
	$replaceChars = getHTMLSpecialCharsReplaceArray();

	$string = preg_replace($HTMLSpecialCharsArray, $replaceChars, $string);
	return $string;
}

function stripSlashesFromArray($array)
{
	$keys = array_keys($array);
	foreach ($keys as $key)
	{
		$array[$key] = stripslashes($array[$key]);
	}
	return $array;
}

function stripHtmlEntitiesFromArray($array)
{
	$keys = array_keys($array);
	foreach ($keys as $key)
	{
		$array[$key] = html_entity_decode($array[$key], ENT_QUOTES);
	}
	return $array;
}

function stripQuotesFromString($string)
{
	$stripchars = array("'", "\"", "`", "-");
	return str_replace($stripchars, "", $string);
}

function getSpecialCharsArray()
{
	return array(
			"/[{}]/",
			"/\\\`([aeiou])/i",
			"/\\\'([aeiou])/i",
			"/\\\\\^([aeiou])/i",
			"/\\\~([aon])/i",
			'/\\\"([aeiouy])/i',
			"/\\\(a)\s?(a)/i",
			"/\\\(c)\s?(c)/i",
			"/\\\(ae|oe)/i",
			'/\\\(s)\s?(s)/i',
			"/\\\(o)/",
			"/\\\.(I)/"
	);
}

function getSpecialCharsReplaceArray()
{
	return array(
			'',
			"&$1grave;",
			"&$1acute;",
			"&$1circ;",
			"&$1tilde;",
			"&$1uml;",
			"&$2ring;",
			"&$2cedil;",
			"&$1lig;",
			"&$2zlig;",
			"&$1slash;",
			"$1"
	);
}

function getHTMLSpecialCharsArray()
{
	return array(
			'/"/',
			"/'/"
	);
}

function getHTMLSpecialCharsReplaceArray()
{
	return array(
			"&quot;",
			"&#039;"
	);
}

function getLatinCharsArray()
{
	return array(
			"/À/",
			"/Á/",
			"/Â/",
			"/È/",
			"/É/",
			"/Ê/",
			"/Ì/",
			"/Í/",
			"/Î/",
			"/Ò/",
			"/Ó/",
			"/Ô/",
			"/Ù/",
			"/Ú/",
			"/Û/",
			"/à/",
			"/á/",
			"/â/",
			"/è/",
			"/é/",
			"/ê/",
			"/ì/",
			"/í/",
			"/î/",
			"/ò/",
			"/ó/",
			"/ô/",
			"/ù/",
			"/ú/",
			"/û/",
			"/ä/",
			"/Ä/",
			"/ë/",
			"/Ë/",
			"/ï/",
			"/ï/",
			"/ü/",
			"/Ü/",
			"/ö/",
			"/Ö/",
			"/ç/",
			"/Ç/",
			"/Œ/",
			"/ÿ/",
			"/Ÿ/",
			"/ß/",
			"/å/",
			"/Å/",
			"/ý/",
			"/Ý/",
			"/þ/",
			"/Þ/",
			"/ø/",
			"/Ø/",
			"/ñ/",
			"/Ñ/",
			"/ã/",
			"/Ã/",
			"/õ/",
			"/Õ/"
	);
}

function getLatinCharsReplaceArray()
{
	return array(
			"{\\`A}",
			"{\\'A}",
			"{\\^A}",
			"{\\`E}",
			"{\\'E}",
			"{\\^E}",
			"{\\`I}",
			"{\\'I}",
			"{\\^I}",
			"{\\`O}",
			"{\\'O}",
			"{\\^O}",
			"{\\`U}",
			"{\\'U}",
			"{\\^U}",
			"{\\`a}",
			"{\\'a}",
			"{\\^a}",
			"{\\`e}",
			"{\\'e}",
			"{\\^e}",
			"{\\`i}",
			"{\\'i}",
			"{\\^i}",
			"{\\`o}",
			"{\\'o}",
			"{\\^o}",
			"{\\`u}",
			"{\\'u}",
			"{\\^u}",
			"{\\\"a}",
			"{\\\"A}",
			"{\\\"e}",
			"{\\\"E}",
			"{\\\"i}",
			"{\\\"I}",
			"{\\\"u}",
			"{\\\"U}",
			"{\\\"o}",
			"{\\\"O}",
			"\\c{c}",
			"\\C{c}",
			"{\\OE}",
			"{\\\"y}",
			"{\\\"Y}",
			"{\\ss}",
			"{\\aa}",
			"{\\AA}",
			"{\\'y}",
			"{\\'Y}",
			"{\\l}",
			"{\\L}",
			"{\\o}",
			"{\\O}",
			"\\~{n}",
			"\\~{N}",
			"\\~{a}",
			"\\~{A}",
			"\\~{o}",
			"\\~{O}"
	);
}

//jaro winkler distance for name matching
//see: http://en.wikipedia.org/wiki/Jaro-Winkler
function jaroSimilarity($str_a, $str_b)
{
  //we work on all lowercase strings
  $str_a      = strtolower($str_a);
  $str_b      = strtolower($str_b);
  
  //initialize working variables
  $m          = 0;                      //number of matching characters
  $t          = 0;                      //number of transpositions for matches
  $a          = strlen($str_a);         //size of string a
  $b          = strlen($str_b);         //size of string b
  $match_wnd  = ceil(max($a, $b) / 2) - 1;  //size of the matching window
  if ($match_wnd < 0)
    $match_wnd = 0;

/*
  //for each character in $str_a
  for ($i = 0; $i < $a; $i++)
  {
    $char = $str_a{$i};                 //character to match
    
    //set match window limits
    $neg_lim = $i - $match_wnd;
    if ($neg_lim < 0)
      $neg_lim = 0;
      
    $pos_lim = $i + $match_wnd;
    if ($pos_lim > $b)
      $pos_lim = $b;
      
    //search within the match window, first in postive direction
    $pos_found      = false;
    $pos_transport  = 0;
    
    $startpos = $i;
    if ($startpos >= $b)
    {
      $startpos = $b-1;
    }
    for ($j = $startpos; $j < $pos_lim; $j++)
    {
      if (!$pos_found)
      {
        if ($char == $str_b{$j})          //found a match?
        {
          $pos_transport += $j - $i;
          $pos_found      = true;
        }
      }
    }
    
    //then in negative direction
    $neg_found      = false;
    $neg_transport  = 0;
    
    for ($j = $startpos; $j >= $neg_lim; $j--)
    {
      if (!$neg_found)
      {
        if ($char == $str_b{$j})          //found a match?
        {
          $neg_transport += $i - $j;
          $neg_found      = true;
        }
      }
    }
    
    if ($pos_found && $neg_found)
    {
      $m++;
      if ($pos_transport <= $neg_transport)
      {
        $t += $pos_transport;
      }
      else
      {
        $t += $neg_transport;
      }
    }
    else if ($pos_found && !$neg_found)
    {
      $m++;
      $t += $pos_transport;
    }
    else if (!$pos_found && $neg_found)
    {
      $m++;
      $t += $neg_transport;
    }
  }
*/  
  $jaro = jaroWindowFind($str_a, $str_b, $a, $b, $match_wnd);
  $m_a  = $jaro['m'];
  $t   += $jaro['t'];
  
  $jaro = jaroWindowFind($str_b, $str_a, $b, $a, $match_wnd);
  $m_b  = $jaro['m'];
  $t   += $jaro['t'];
  
  $m = $m_a + $m_b;
  if ($m > 0)
  {
    $similarity = ($m_a / $a) + ($m_b / $b) + (($m - $t) / $m);
    return $similarity / 3;
  }
  else
    return 0;
}

function jaroWindowFind($str_a, $str_b, $a, $b, $match_wnd)
{
  $t = 0;
  $m = 0;
   //for each character in $str_a
  for ($i = 0; $i < $a; $i++)
  {
    $char = $str_a{$i};                 //character to match
    
    //set match window limits
    $neg_lim = $i - $match_wnd;
    if ($neg_lim < 0)
      $neg_lim = 0;
      
    $pos_lim = $i + $match_wnd + 1;
    if ($pos_lim > $b)
      $pos_lim = $b;
      
    //search within the match window, first in postive direction
    $pos_found      = false;
    $pos_transport  = 0;
    
    $startpos = $i;
    if ($startpos >= $b)
    {
      $startpos = $b-1;
    }
    for ($j = $startpos; $j < $pos_lim; $j++)
    {
      if (!$pos_found)
      {
        if ($char == $str_b{$j})          //found a match?
        {
          $pos_transport += abs($i - $j);
          $pos_found      = true;
        }
      }
    }
    
    //then in negative direction
    $neg_found      = false;
    $neg_transport  = 0;
    
    for ($j = $startpos; $j >= $neg_lim; $j--)
    {
      if (!$neg_found)
      {
        if ($char == $str_b{$j})          //found a match?
        {
          $neg_transport += abs($i - $j);
          $neg_found      = true;
        }
      }
    }
    
    if ($pos_found && $neg_found)
    {
      $m++;
      if ($pos_transport <= $neg_transport)
      {
        $t += $pos_transport;
      }
      else
      {
        $t += $neg_transport;
      }
    }
    else if ($pos_found && !$neg_found)
    {
      $m++;
      $t += $pos_transport;
    }
    else if (!$pos_found && $neg_found)
    {
      $m++;
      $t += $neg_transport;
    }
  }
  
  return array('m' => $m, 't' => $t);
}
?>