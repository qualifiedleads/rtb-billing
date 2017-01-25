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
            <div class="sort-direction">
                <?php if($order=='ASC'){?><span class="glyphicon glyphicon-menu-up"></span><?php
                }else{?><span class="glyphicon glyphicon-menu-down"></span><?php }?>
            </div>
        </th>
        <!--<th>Links</th>-->
        <th data-sort="filtered_impressions" onclick="column_sort(this)">
            Filtered Impressions
            <div class="sort-direction">
                <?php if($order=='ASC'){?><span class="glyphicon glyphicon-menu-up"></span><?php
                }else{?><span class="glyphicon glyphicon-menu-down"></span><?php }?>
            </div>
        </th>
        
        <!--
        <th>Total Impressions</th>
        <th>Filtered Uniques</th>
        <th>Average Uniques</th>
        <th>Partner Type</th>
        <th>Inventory Trust</th>
        -->
    </tr>
<?php foreach($rows as $row){?>
    <tr>
<?php if($row->statistics!=""){?>
        <td><a href="<?php echo 'index.php/stat/index?seller='.$row->statistics.'&name='.$row->seller_member_name;?>" target="_blank"><?php echo $row->seller_member_name;?></a></td>
<?php }else{?>
        <td><?php echo $row->seller_member_name;?></td>
<?php }?>
        <!--<td><?php echo $row->seller_member_id;?></td>-->
        <td><?php echo format_number($row->filtered_imps);?></td>

        <!--
        <td><?php echo format_number($row->total_imps);?></td>
        <td><?php echo format_number($row->filtered_uniques);?></td>
        <td><?php echo format_number($row->total_uniques);?></td>
        <td><?php echo $row->seller_type;?></td>
        <td><?php echo $row->inventory_trust;?></td>
        -->
    </tr>
<?php }?>
</table>