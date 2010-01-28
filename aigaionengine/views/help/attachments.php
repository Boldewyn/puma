<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="help-attachments" class="help-content">
  <h2><?php _e('Attachments')?></h2>
  <p><?php _e('It is possible to attach files to your publications in two ways: by uploading them to the server, and as a &lsquo;remote&rsquo; link to a document somewhere on the web. Upon import, the import data is scanned for links to attachments, which are then automatically stored as remote attachments for the imported entries. &lsquo;Remote&rsquo; attachments may at a later time be downloaded to the server and turned into &lsquo;server stored attachments&rsquo; with a single command.')?></p>

  <h3><?php _e('Why attachments?')?></h3>
  <p><?php _e('When building up a bibliography, it is not uncommon that one spends a lot of time tracking down not only the bibliographic information for new entries, but also tracking down the content of the papers themselves. And then, when you have finally obtained the file and printed it out, you forget the print-out somewhere on the plane... and have to do it all again. Or you could of course have attached the electronic version of the paper to your database - then at least you can print it out a second time without a long search.')?></p>

  <h3><?php _e('Attachments and public access rights')?></h3>
  <p><?php _e('When you have downloaded a publication, e.g. from the publishers site or from your library, you do not necessarily have the right to make that file public to all the world. It is a good idea not to set the access level of a publication to &lsquo;public&rsquo; unless you are very certain of your right to do so. When in doubt, keep the attachment intern or private, and use the DOI to give anonymous guests access to the publication contents.')?></p>

  <h3><?php _e('Remote attachments vs DOI')?></h3>
  <p><?php printf(__('A DOI is a Digital Object Identifier. <q>&ldquo;They are used to provide current information, including where they (or information about them) can be found on the Internet. Information about a digital object may change over time, including where to find it, but its DOI name will not change.&rdquo;</q> (source: %s).'), '<a href="http://www.doi.org">www.doi.org</a>')?></p>
  <p><?php _e('When you know the DOI of a paper, you can always find the paper by appending the DOI to the url <code>http://dx.doi.org/</code> This usually leads you to the paper on the site of the publisher. A major advantage of this is that, as opposed to on-server attachments, you do not have to worry about your rights to make the information public: the publisher will have the appropriate mechanisms in place on his site to restrict access to the actual content of the paper to those who have a right to it.')?></p>
  <p><?php _e('The difference with remote attachments (URL links) is that a DOI always stays the same, whereas an URL will probably be outdated as soon as the publisher changes his web site.')?></p>

  <h3><?php _e('Common problems')?></h3>
  <p><?php _e('The most common problems and errors with uploading attachments are:')?></p>
  <ul>
    <li><?php _e('The server is read-only, or the attachment directory is not writable, so storing uploaded attachment on the server fails')?></li>
    <li><?php _e('The PHP settings for the webserver are sometimes inadequate for uploading attachments: <code>upload_max_filesize</code>, <code>post_max_size</code> and <code>max_execution_time</code> should all be large enough for uploading attachments of a normal size')?></li>
    <li><?php _e('The setting &lsquo;allowed attachment extensions&rsquo; does not contain the right extensions')?></li>
  </ul>
</div>
