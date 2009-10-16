<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?><?php
/** This class regulates the database access for Keywords. */
class Keyword_db {


  function Keyword_db()
  {
  }

  function getByID($keyword_id)
  {
        $CI = &get_instance();
    //retrieve one keyword row
    $Q = $CI->db->get_where('keywords',array('keyword_id'=>$keyword_id));

    if ($Q->num_rows() > 0)
    {
      //load the keyword
      $R = $Q->row();
      return $this->getFromRow($R);
    }
    else
      return null;
  }
  
  function getFromRow($R)
  {
    foreach ($R as $key => $value)
    {
      $kw->$key = $value;
    }
    return $kw;
  }  
  
  function getByKeyword($keyword)
  {
    $CI = &get_instance();
    $Q = $CI->db->get_where('keywords',array('keyword'=>$keyword));

    if ($Q->num_rows() > 0)
    {
      //load the publication
      $R = $Q->row();
    
      return $this->getFromRow($R);
    }
    else
      return null;
  }
  
  function getKeywordsLike($keyword)
  {
    $CI = &get_instance();
    //select all keywords from the database that start with the characters as in $keyword
    $CI->db->orderby('keyword');
    $CI->db->like('keyword',$keyword);
    $Q = $CI->db->get('keywords');
    
    //retrieve results or fail
    $result = array();
    foreach ($Q->result() as $R)
    {
      $result[] = $this->getFromRow($R);
    }
    return $result;
  }
  
  function getAllKeywords()
  {
    $CI = &get_instance();
    $result = array();
    
    //get all keywords from the database, order by cleankeyword
    $CI->db->orderby('cleankeyword');
    $Q = $CI->db->get('keywords');
    
    //retrieve results or fail
    foreach ($Q->result() as $row)
    {
      $next = $this->getFromRow($row);
      if ($next != null)
      {
        $result[] = $next;
      }
    }
    return $result;
  }
  
  function getKeywordsForPublication($pub_id)
  {
    $CI = &get_instance();
    $Q = $CI->db->query("SELECT ".AIGAION_DB_PREFIX."keywords.* FROM ".AIGAION_DB_PREFIX."keywords, ".AIGAION_DB_PREFIX."publicationkeywordlink
                               WHERE ".AIGAION_DB_PREFIX."publicationkeywordlink.pub_id = ".$CI->db->escape($pub_id)." 
                                 AND ".AIGAION_DB_PREFIX."publicationkeywordlink.keyword_id = ".AIGAION_DB_PREFIX."keywords.keyword_id
                               ORDER BY ".AIGAION_DB_PREFIX."keywords.keyword");
    $result = array();

    if ($Q->num_rows() > 0)
    {
      foreach ($Q->result() as $R)
      {
        $result[] = $this->getFromRow($R);
      }
    }

    return $result;
  }
  
  function add($keyword)
  {
    $CI = &get_instance();
    $CI->load->helper('utf8_to_ascii');
    $cleankeyword = utf8_to_ascii($keyword->keyword);
    $data = array('keyword' => $keyword->keyword, 'cleankeyword'=>$cleankeyword);
    
    $CI->db->insert('keywords', $data);
    
    $keyword_id = $CI->db->insert_id();
    
    if ($keyword_id)
    {
      //load the keyword
      $kw->keyword_id = $keyword_id;
      $kw->keyword = $keyword;
      $kw->cleankeyword = $cleankeyword;
      return $kw;
    }
    else
      return null;
  }
  
  //ensureKewordsInDatabase($keywords) checks if all keywords in the array 
  //are already in the database. If not, it will add them.
  //returns a map of (keyword_id=>keyword)
  function ensureKeywordsInDatabase($keywords)
  {
    $CI = &get_instance();
    if (!is_array($keywords))
      return null;
    
    $result = array();
    foreach($keywords as $kw)
    {
      $current      = $this->getByKeyword($kw->keyword);
      if ($current == null)
        $current    = $this->add($kw);
      
      $result[] = $current;
    }
    
    return $result;
  }
  
  function review($keywords)
  {
    $CI = &get_instance();
    if (!is_array($keywords))
      return null;
    
    $result_message   = "";
    
    //get database keyword array
    $db_keywords = array();
    $CI->db->orderby('keyword');
    $Q = $CI->db->get('keywords');
    if ($Q->num_rows() > 0)
    {
      foreach ($Q->result() as $R)
      {
        $db_keywords[$R->keyword_id] = strtolower($R->keyword);
      }
    }
    
    //check availability of the keywords in the database
    foreach ($keywords as $keyword)
    {
      $keyword_low  = strtolower($keyword->keyword);
      $keyword_id   = array_search($keyword_low, $db_keywords);
      
      //is the keyword already in the db?
      if (!is_numeric($keyword_id))
      {
        //not found in the database, so check for similar keywords
        $db_distances = array();
        foreach ($db_keywords as $keyword_id => $db_keyword)
        {
          $distance = levenshtein($db_keyword, $keyword_low);
          if ($distance < 3)
            $db_distances[$keyword_id] = $distance;
        }
        
        //sort while keeping key relationship
        asort($db_distances, SORT_NUMERIC);
        
        //are there similar keywords?
        if (count($db_distances) > 0)
        {
          $result_message .= __("Found similar keywords for")." <b>&quot;".$keyword->keyword."&quot;</b>:<br/>\n";
          $result_message .= "<ul>\n";
          foreach($db_distances as $key => $value)
          {
            $result_message .= "<li>".$db_keywords[$key]."</li>\n";
          }
          $result_message .= "</ul>\n";
        }
        //when no similar keywords are found, we add the unknown keyword to the database
        else
        {
          $this->add($keyword);
        }
      }
    }
    if ($result_message != "")
    {
      $result_message .= __("Please review the entered keywords.")."<br/>\n";
      return $result_message;
    }
    else
      return null;
  }
}
?>