<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

appendErrorMessage('The conversion controller is not intended for actual use...');

class Conversion extends Controller {
	function Conversion()
	{
		parent::Controller();	
	}
	
	function index(){}
	

	/** show the sql commands needed to convert all tabels to utf8 where needed */
	function tableconvert()
	{
	    echo 'show info on converting your whole database tables to utf8<br/>';
	    echo '(to be included in the migration somehow)<br/>';
//convert all tables and columns to utf8 and corresponding collation
//alter database default charset
        $this->db->query ('ALTER DATABASE '.AIGAION_DB_NAME.' CHARACTER SET utf8;');
        echo '<br/>';
//alter all tables defaultcharset
	    $tablenames = $this->_getDatabaseTables();
	    foreach ($tablenames as $tablename) {
	        $p = strpos($tablename,AIGAION_DB_PREFIX);
            if (!($p===FALSE)) {
                if ($p==0) {
                    $this->db->query('ALTER TABLE '.$tablename.' CONVERT TO CHARACTER SET utf8;');
                    echo '<br/>';
                }
            }
	    }
	}
	/** show info on converting your whole database such that bibtex specialchar codes are converted into utf8 */
	function bibtexconvert() {
	    echo 'show info on converting your whole database such that bibtex specialchar codes are converted into utf8<br/>';
	    echo 'assumes the tables are already in utf8!';
	    //convert: 
	    $this->load->helper('bibtexutf8');
        //author->surname, von, firstname, institute
	    $Q = $this->db->get('author');
        foreach ($Q->result() as $row) {
            $data = array('surname'=>bibCharsToUtf8FromString($row->surname),
                          'von'=>bibCharsToUtf8FromString($row->von),
                          'firstname'=>bibCharsToUtf8FromString($row->firstname),
                          'institute'=>bibCharsToUtf8FromString($row->institute));
                            
            $this->db->update('author',$data,array('author_id'=>$row->author_id));
        }
        //keywords->keyword
	    $Q = $this->db->get('keywords');
        foreach ($Q->result() as $row) {
            $data = array('keyword'=>bibCharsToUtf8FromString($row->keyword));
                            
            $this->db->update('keywords',$data,array('keyword_id'=>$row->keyword_id));
        }
        //notes->text
	    $Q = $this->db->get('notes');
        foreach ($Q->result() as $row) {
            $data = array('text'=>bibCharsToUtf8FromString($row->text));
            $this->db->update('notes',$data,array('note_id'=>$row->note_id));
        }
        //publication->title, series, publisher, location, journal, booktitle, institution, address, organisation, school, note, abstract, 
	    $Q = $this->db->get('publication');
        foreach ($Q->result() as $row) {
            $data = array('title'=>bibCharsToUtf8FromString($row->title),
                          'series'=>bibCharsToUtf8FromString($row->series),
                          'publisher'=>bibCharsToUtf8FromString($row->publisher),
                          'location'=>bibCharsToUtf8FromString($row->location),
                          'journal'=>bibCharsToUtf8FromString($row->journal),
                          'booktitle'=>bibCharsToUtf8FromString($row->booktitle),
                          'institution'=>bibCharsToUtf8FromString($row->institution),
                          'address'=>bibCharsToUtf8FromString($row->address),
                          'organization'=>bibCharsToUtf8FromString($row->organization),
                          'school'=>bibCharsToUtf8FromString($row->school),
                          'note'=>bibCharsToUtf8FromString($row->note),
                          'abstract'=>bibCharsToUtf8FromString($row->abstract));
                            
            $this->db->update('publication',$data,array('pub_id'=>$row->pub_id));
        }
	    
	}
	
/** help functions for the conversion to utf8 */
function _getDatabaseTables()
{
	$tableNames = array();
	$Q = mysql_query("SHOW TABLES FROM ".AIGAION_DB_NAME);
	if (mysql_num_rows($Q) > 0) {
		while ($R = mysql_fetch_array($Q)) {
			$tableNames[] = $R['Tables_in_'.AIGAION_DB_NAME];
		}
	}
	return $tableNames;
}	
function _getColumns($tablename)
{
	$colNames = array();
	$Q = mysql_query("SHOW COLUMNS FROM ".$tablename);
	if (mysql_num_rows($Q) > 0) {
		while ($R = mysql_fetch_array($Q)) {
			$colNames[] = $R['Field'];
		}
	}
	return $colNames;
}	
}
?>