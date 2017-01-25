<ul>
<?php foreach($countries as $country):?>
      <li data-code="<?php echo $country['code'];?>" onclick="countries.select(this)"><?php echo $country['name'];?></li>
<?php endforeach; ?>
</ul>