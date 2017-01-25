<?php if($page > 1):?>
    <button class="btn btn-default" title="Previous" onclick="page_go(<?php echo $page-1;?>)">
        <i class="glyphicon glyphicon-chevron-left"></i>
    </button>
<?php else:?>
    <button class="btn btn-default" disabled="true">
        <i class="glyphicon glyphicon-chevron-left"></i>
    </button>
<?php endif;?>
    &nbsp;
<?php if($total == 1 || $total == 0){ ?>
    <select id="pagination" name="page" disabled="true" onchange="page_change()">
<?php }else{ ?>
    <select id="pagination" name="page" onchange="page_change()">
<?php } 
    foreach($list as $number):?>
        <option value="<?php echo $number;?>" <?php if($page==$number){echo 'selected';}?>> <?php echo $number;?></option>
<?php endforeach; ?>
    </select>
    &nbsp;
    of <?php echo $total;?>
    &nbsp;
<?php if($page < $total):?>
    <button class="btn btn-default" title="Next" onclick="page_go(<?php echo $page+1;?>)">
        <i class="glyphicon glyphicon-chevron-right"></i>
    </button>
<?php else:?>
    <button class="btn btn-default" disabled="true">
        <i class="glyphicon glyphicon-chevron-right"></i>
    </button>
<?php endif;?>