<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?><?php
/** This class holds the data structure of an author.

Database access for Authors is done through the author_db library */
class Author {

  //system vars
  var $author_id    = 0;
  var $specialchars = 'FALSE';
  var $cleanname    = '';

  //user vars
  var $firstname    = '';
  var $von          = '';
  var $surname      = '';
  var $jr           = '';
  var $email        = '';
  var $url          = '';
  var $institute    = '';



  //class constructor
  function Author()
  {
    $CI =&get_instance();
  }

  //getName returns the author name, formatted according to the user's preference
  function getName($style = '')
  {
    //if no style is given, get style from user preference
    if ($style == '') {
        $userlogin = getUserLogin();
        $style = $userlogin->getPreference('authordisplaystyle');
    }
        
    switch($style) {
      case 'fvl':   //first von last
      $name = $this->firstname;
      if ($this->von != '')
      ($name != '') ? $name .= " ".$this->von : $name = $this->von;

      if ($this->surname != '')
      ($name != '') ? $name .= " ".$this->surname : $name = $this->surname;

      if ($this->jr != '')
      ($name != '') ? $name .= ", ".$this->jr : $name = $this->jr;

      return $name;
      break;

      case 'vlf':   //von last, first
      $name = $this->von;
      if ($this->surname != '')
      ($name != '') ? $name .= " ".$this->surname : $name = $this->surname;

      if ($this->jr != '') {
        ($name != '') ? $name .= ", ".$this->jr : $name = $this->jr;
        if ($this->firstname == '') $name .= ", {}"; //make sure that even without first name, the jr is preserved as jr and does not become a firstname on subsequent import
      }

      if ($this->firstname != '')
      ($name != '') ? $name .= ", ".$this->firstname : $name = $this->firstname;

      return $name;
      break;

      case 'vl':    //von last
      $name = $this->von;
      if ($this->surname != '')
      ($name != '') ? $name .= " ".$this->surname : $name = $this->surname;

      return $name;
      break;

      default:      //last, von, first
      $name = $this->surname;
      if ($this->von != '')
      ($name != '') ? $name .= ", ".$this->von : $name = $this->von;

      if ($this->firstname != '')
      ($name != '') ? $name .= ", ".$this->firstname : $name = $this->firstname;

      return $name;
      break;
    }
  }

function format($formatStyle, $data='')
  {
    //if no data are passed, use $this->data
    if ($data == '')
      $data = $this->data;
    else
    {
      //cleanup and assign new data. $data should be of the Author class type.
      $this->_clearData();
      $this->data = $data;
    }
    
    //only format if there are special characters in the data
    //TODO: that's no longer true; everything is in UTF8 so there are no specialchars in that sense.
    if ($data->specialchars == 'TRUE')
    {  
      //the only fields where special characters should be formatted:
      $fields = array(  'firstname',
                        'von',
                        'jr',
                        'surname',
                        'institute'
                      );

      //TODO: FORMATTING, FOR DIFFERENT FORMATTING STYLES                      
      foreach ($fields as $field)
      {
        $this->data->$field = $data->$field;
      }
    }
  }
  
  /** Add a new author with the given data. Returns TRUE or FALSE depending on whether the operation was
  successfull. After a successfull 'add', $this->author_id contains the new author_id. */
  function add() {
    $this->author_id = $CI->author_db->add($this);
    if ($this->author_id > 0) {
      return True;
    }
    return False;
  }

  /** Update the changes in the data of this author. Returns TRUE or FALSE depending on whether the operation was
  successfull. */
  function update() {
      $CI = &get_instance();
    return $CI->author_db->update($this);
  }
  /** Deletes this author. Returns TRUE or FALSE depending on whether the operation was
  successful. */
  function delete() {
      $CI = &get_instance();
      return $CI->author_db->delete($this);
  }
  
  /** returns a list of similar authors (possibly empty) */
  function getSimilarAuthors() {
      $CI = &get_instance();
      return $CI->author_db->getSimilarAuthors($this);
  }
  //this function steals the publications and kills the similar author
  function merge($simauthor_id) {
      $CI = &get_instance();
      return $CI->author_db->merge($this,$simauthor_id);
  }
  
  function getKeywords() {
      $CI = &get_instance();
      return $CI->author_db->getKeywordsForAuthor($this->author_id);
    }
}
?>