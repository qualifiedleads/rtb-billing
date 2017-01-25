<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="/" />
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
            <?php echo $table; ?>
        </main>
        <footer></footer>
        <div id="loader" class="screen-overlay">
            <div class="box">
                <img src="images/spinner.gif" />&nbsp;LOADING
            </div>
        </div>
        <script>
            window.addEventListener("message", receiveMessage, false);
            function receiveMessage(e){
                console.log("Message received.");
            }
            function sort_column2(elem){
                var sort = $(elem).attr('data-sort');
                var order = $(elem).attr('data-order');
                var data = location.search;
                if(order == "asc"){
                    order = "desc";
                }
                else{
                    order = "asc";
                }
                $(elem).attr('data-order',order);
                data = data+"&sort="+sort+"&order="+order+"&mode=json";
                loader.open();
                $.ajax({
                    method : "GET",
                    url : "index.php/stat/index"+data,
                    success : function(response){
                        var result = JSON.parse(response);
                        setTimeout(function(){
                            if(result.status == "success"){
                                loader.close();
                                $("main").html(result.content);
                            }
                        },delay);
                    }
                });
            }
        </script>
        <script src="js/functions.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>