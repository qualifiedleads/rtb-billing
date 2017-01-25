<?php
    $this->load->helper("url");
    function format_number($input){
        if(is_numeric($input)){
            return number_format($input);
        }
        else{
            return $input;
        }
    }
?><table class="table table-bordered">
    <tr>
        <th data-sort="seller_name" onclick="column_sort(this)">
            Seller
            <div class="sort-direction" <?php if($sort!="seller_name"){echo 'style="visibility:hidden"';} ?>>
                <?php if($order=='ASC'){?><span class="glyphicon glyphicon-menu-up"></span><?php
                }else{?><span class="glyphicon glyphicon-menu-down"></span><?php }?>
            </div>
        </th>
        <th data-sort="filtered_impressions" onclick="column_sort(this)">
            Avg. Daily Impressions
            <div class="sort-direction" <?php if($sort!="filtered_impressions"){echo 'style="visibility:hidden"';} ?>>
                <?php if($order=='ASC'){?><span class="glyphicon glyphicon-menu-up"></span><?php
                }else{?><span class="glyphicon glyphicon-menu-down"></span><?php }?>
            </div>
        </th>
    </tr>
<?php foreach($rows as $row){?>
    <tr>
<?php if($row->stat!=""){?>
        <td><a href="<?php echo 'http://rtb.cat/stats.html?seller='.$row->stat.'&name='.$row->seller_member_name;?>" target="_blank"><?php echo $row->seller_member_name;?></a></td>
<?php }else{?>
        <td><?php echo $row->seller_member_name;?></td>
<?php }?>
        <td><?php echo format_number($row->filtered_imps);?></td>
    </tr>
<?php }?>
</table>