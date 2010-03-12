-- 
-- Management for external search engines
-- 

CREATE TABLE `puma_external_search` (
  `name` VARCHAR( 255 ) NOT NULL,
  `url` VARCHAR( 1023 ) NOT NULL,
  `q` VARCHAR( 255 ) NOT NULL,
  `active` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT 1,
  `image` VARCHAR( 255 ) NOT NULL DEFAULT "",
  `charset` VARCHAR( 12 ) NOT NULL DEFAULT "utf-8",
  `method` VARCHAR( 6 ) NOT NULL DEFAULT "get",
  `p` MEDIUMTEXT NOT NULL DEFAULT "",
  PRIMARY KEY( `name` )
);

INSERT INTO `puma_external_search` 
( `name`, `url`, `q`, `image`, `method`, `p` )
VALUES
('Google Scholar', 'http://scholar.google.com/scholar', 'q', 'static/images/external_search/google.png', 'get', ''),
('ePub Server (Uni)', 'http://epub.uni-regensburg.de/cgi/search', 'q', 'static/images/external_search/epub.png', 'get', '[["_action_search","Search"],["_order","bytitle"],["basic_srchtype","ALL"],["_satisfyall","ALL"]]'),
('arXiv.org', 'http://arxiv.org/search', 'query', 'static/images/external_search/arxiv.png', 'get', ''),
('WorldWideScience', 'http://worldwidescience.org/wws/search.html', 'expression', 'static/images/external_search/wws.png', 'get', ''),
('Inspec', 'http://ovidsp.ovid.com/ovidweb.cgi', 'textBox', 'static/images/external_search/inspec.png', 'get', '[["T","JS"],["MODE","ovid"],["PAGE","main"],["NEWS","n"],["DBC","y"],["D","insz"]]'),
('Spires', 'http://www.slac.stanford.edu/spires/find/hep/www', 'rawcmd', 'static/images/external_search/spires.png', 'get', '[["FORMAT","WWW"],["SEQUENCE",""]]'),
('Amazon', 'http://www.amazon.de/s/ref=nb_ss_w', 'field-keywords', 'static/images/external_search/amazon.png', 'get', '[["__mk_de_DE","ÅMÅZÕÑ"],["url","search-alias=aps"]]'),
('OPAC', 'https://ubbx6.bib-bvb.de/InfoGuideClient.ubrsis/start.do', 'searchString[0]', 'static/images/external_search/opac.png', 'get', '[["Login","igubr"]]'),
('PubMed', 'http://www.ncbi.nlm.nih.gov/sites/entrez', 'EntrezSystem2.PEntrez.Pubmed.SearchBar.Term', 'static/images/external_search/pubmed.png', 'post',
      '[
          ["EntrezSystem2.PEntrez.DbConnector.Cmd","Go"],
          ["EntrezSystem2.PEntrez.DbConnector.Db","pubmed"],
          ["EntrezSystem2.PEntrez.DbConnector.IdsFromResult",""],
          ["EntrezSystem2.PEntrez.DbConnector.LastDb","pubmed"],
          ["EntrezSystem2.PEntrez.DbConnector.LastIdsFromResult",""],
          ["EntrezSystem2.PEntrez.DbConnector.LastQueryKey",""],
          ["EntrezSystem2.PEntrez.DbConnector.LastTabCmd","home"],
          ["EntrezSystem2.PEntrez.DbConnector.LinkName",""],
          ["EntrezSystem2.PEntrez.DbConnector.LinkReadableName",""],
          ["EntrezSystem2.PEntrez.DbConnector.LinkSrcDb",""],
          ["EntrezSystem2.PEntrez.DbConnector.QueryKey",""],
          ["EntrezSystem2.PEntrez.DbConnector.TabCmd",""],
          ["EntrezSystem2.PEntrez.DbConnector.Term","{query}"],
          ["EntrezSystem2.PEntrez.Pubmed.Entrez_PageController.PreviousPageName","home"],
          ["EntrezSystem2.PEntrez.Pubmed.Pubmed_SearchBar.CurrDb","pubmed"],
          ["EntrezSystem2.PEntrez.Pubmed.Pubmed_SearchBar.FeedLimit","15"],
          ["EntrezSystem2.PEntrez.Pubmed.Pubmed_SearchBar.FeedName",""],
          ["EntrezSystem2.PEntrez.Pubmed.Pubmed_SearchBar.SearchResourceList","pubmed"],
          ["EntrezSystem2.PEntrez.Pubmed.Pubmed_SearchBar.Term","{query}"],
          ["p$a","EntrezSystem2.PEntrez.Pubmed.Pubmed_SearchBar.Search"],
          ["p$el",""],
          ["p$l","EntrezSystem2"],
          ["p$st","entrez"]
      ]'),
('Bibsonomy', 'http://www.bibsonomy.org/search', 'search', 'static/images/external_search/bibsonomy.png' , 'get', '')
; 


