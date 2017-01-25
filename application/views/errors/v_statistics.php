<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo base_url();?>" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inventory - Research</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/styles.css" />
        <script src="js/jquery-1.11.3.min.js"></script>
    </head>
    <body>
        <header>
            <div class="header-title">
                <?php echo $title;?>
            </div>
        </header>
        <main>
            <table class="table table-bordered">
                <tr>
                    <th>Country</th><th>Impression</th>
                </tr>
<?php foreach($stats as $stat):?>
                <tr>
                    <td><?php echo $stat['country'];?></td>
                    <td><?php echo $stat['impression'];?></td>
                </tr>
<?php endforeach;?>
            </table>
        </main>
        <footer>
            
        </footer>
        <script src="js/functions.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>