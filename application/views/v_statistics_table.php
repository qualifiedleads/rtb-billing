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
                    <th data-sort="country" data-order="<?php echo $order;?>" onclick="sort_column2(this)">
                        Country
                        <div class="sort-direction" <?php if($sort!="country"){echo 'style="display:none"';}?>>
                            <?php if($order=='desc'){?><span class="glyphicon glyphicon-menu-down"></span><?php
                            }else{?><span class="glyphicon glyphicon-menu-up"></span><?php }?>
                        </div>
                    </th>
                    <th data-sort="impression" data-order="<?php echo $order;?>" onclick="sort_column2(this)">
                        Impression
                        <div class="sort-direction" <?php if($sort!="impression"){echo 'style="display:none"';}?>>
                            <?php if($order=='desc'){?><span class="glyphicon glyphicon-menu-down"></span><?php
                            }else{?><span class="glyphicon glyphicon-menu-up"></span><?php }?>
                        </div>
                    </th>
                </tr>
<?php foreach($stats as $stat):?>
                <tr>
                    <td><?php echo $stat['country'];?></td>
                    <td><?php echo format_number($stat['impression']);?></td>
                </tr>
<?php endforeach;?>
            </table>
