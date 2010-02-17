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

if (! isset($paginationNeighbours)) { $paginationNeighbours = 3; }

$page = 0;
$liststyle = $userlogin->getPreference('liststyle');
if ($liststyle > 0 && $paginationCounter > 0 && $paginationCounter > $liststyle) {
    ?><ul class="pagination">
        <li class="first"><?php if ($paginationCurrent > 0) {
            _a($paginationPrefix.'0', __('Start'));
        } else {
            echo '<strong>',__('Start'),'</strong>';
        } ?></li><?php
        $ellipsisNeeded = False;
        while ($page*$liststyle < $paginationCounter) {
            if ($page == 1 || ($page+1)*$liststyle > $paginationCounter ||
                abs($page - $paginationCurrent) < $paginationNeighbours
                ) {
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
                $ellipsisNeeded = True;
            } elseif ($ellipsisNeeded) {
                $ellipsisNeeded = False;
                ?><li>&hellip;</li><?php
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

