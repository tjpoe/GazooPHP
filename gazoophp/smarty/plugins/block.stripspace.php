<?php
function smarty_block_stripspace($params, $content, &$smarty)
{
    return preg_replace("/\t/", '', $content);
}

/* vim: set expandtab: */

?>
