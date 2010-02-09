<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Pagination
 *
 * Parameters:
 *   $paginationCounter
 *   $paginationPrefix
 *   $paginationCurrent
 */
$userlogin = getUserLogin();

$page = 0;
$liststyle = $userlogin->getPreference('liststyle');
if ($liststyle > 0 && $paginationCounter > 0 && $paginationCounter > $liststyle) {
    ?><ul class="pagination">
        <li class="first"><?php if ($paginationCurrent > 0) {
            _a($paginationPrefix.'0', __('Start'));
        } else {
            echo '<strong>',__('Start'),'</strong>';
        } ?></li><?php
        while ($page*$liststyle < $paginationCounter) {
            $linktext = ($page*$liststyle+1).'-';
            if (($page+1)*$liststyle > $paginationCounter) {
                $linktext .= $paginationCounter;
            } else {
                $linktext .= (($page+1)*$liststyle);
            }
            if ($page != $paginationCurrent) {
                ?><li><?php _a($paginationPrefix.$page, $linktext) ?></li><?php
            } else {
                ?><li class="active"><strong><?php echo $linktext ?></strong></li><?php
            }
            $page++;
        }
        ?><li class="last"><?php if ($paginationCurrent < $page-1) {
            _a($paginationPrefix.($page-1), __('End'));
        } else {
            echo '<strong>',__('End'),'</strong>';
        } ?></li>
    </ul><?php
}

