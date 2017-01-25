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