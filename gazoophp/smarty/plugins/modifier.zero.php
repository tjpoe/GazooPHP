<?php
function smarty_modifier_zero($number = null)
{
    if ( empty( $number ) ) {
      return 0;
    } else {
      return $number;  
    }
}
?>
