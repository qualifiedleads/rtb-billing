<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="/rtb/" />
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BlueKai Data</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/styles.css" />
        <script src="js/jquery-1.11.3.min.js"></script>
    </head>
    <body>
        <!--
        <header>
            <div class="logo">
                <a href="#">rtb.cat</a>
            </div>
            <div class="nav">
                <nav>
                    <a href="#">About Us</a><a href="#">Inventory</a><a href="#">Login</a><a href="#">Contact</a>
                </nav>
            </div>
        </header>
        -->
        <main>
            <aside id="sidebar" class="docked">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Search
                        <button class="btn toggler" onclick="toggle_side(this)">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </button>
                    </div>
                    <div class="panel-body">
                    <!-- Form Fields -->
                        <form id="search_form">
                            <div class="field-entry">
                                <input type="text" class="form-control" name="keyword" placeholder="Name or ID" />
                            </div>
                            <div class="field-entry">
                                <div class="filter-title">Main Categories</div>
                                <div class="filter-content">
                                    <ul id="filter_country">
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_b2b" checked="true" />
                                                &nbsp;B2B
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_branded_data" />
                                                &nbsp;Branded Data
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_custom_categories" />
                                                &nbsp;Custom Categories
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_demographic" />
                                                &nbsp;Demographic
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_device_data" />
                                                &nbsp;Device Data
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_geographic" />
                                                &nbsp;Geographic
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_in_market" />
                                                &nbsp;In-Market
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_interest" />
                                                &nbsp;Interest
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_past_purchases" />
                                                &nbsp;Past Purchases
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_predictors" />
                                                &nbsp;Predictors
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="category" value="bk_television" />
                                                &nbsp;Television
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="sort_by" value="" />
                            <input type="hidden" name="order_by" value="acs" />
                            <input type="hidden" name="page" value="1" />
                            <div class="field-entry form-buttons">
                                <button type="submit" class="btn btn-info btn-sm">Apply Filter</button>
                                <button type="reset" class="btn btn-warning btn-sm" onclick="countries.all()">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>
            <section>
                <div class="section-header">
                    <div class="title">Search Results</div>
                    <div id="pagination_container" class="pagination">
                        <select id="pagination" name="page" disabled="true">
                            <option value="1">1</option>
                        </select>
                        &nbsp;
                        of 0
                    </div>
                </div>
                <div id="table_container" class="section-body">
                    <!--
                        Ajax table will fill this section.
                    -->
                </div>
                <div class="section-footer">
                    <div id="pagination_container_bottom" class="pagination">
                        <div class="sort_by">Sort by </div>
                        <select id="sorting" onchange="page_sort()">
                            <option value="bk_id">BlueKai ID</option>
                            <option value="apn_id">Appnexus ID</option>
                            <option value="path">Path</option>
                            <option value="size">Size</option>
                            <option value="cpm">CPM</option>
                        </select>
                        <select id="ordering" onchange="page_sort()">
                            <option value="asc">Ascending</option>
                            <option value="des" selected="true">Descending</option>
                        </select>
                    </div>
                </div>
            </section>
        </main>
        <footer>
            Footer&nbsp;|&nbsp;Text&nbsp;|&nbsp;Links
        </footer>
        <div id="loader" class="screen-overlay">
            <div class="box">
                <img src="images/spinner.gif" />&nbsp;LOADING
            </div>
        </div>
        <div id="message" class="screen-overlay">
            <div class="box">
                <div class="message">
                    Message goes here.
                </div>
                <div class="buttons">
                    <button type="button" class="btn btn-default btn-sm" onclick="message.close()">
                        Ok
                    </button>
                </div>
            </div>
        </div>
        <div id="country_select" class="screen-overlay">
            <div class="box">
                <div class="message">
                    <p>Select a country.</p>
                </div>
                <div class="list">
                    <ul>
<?php foreach($countries as $country):?>
                        <li data-code="<?php echo $country['code'];?>" onclick="countries.select(this)"><?php echo $country['name'];?></li>
<?php endforeach; ?>
                    </ul>
                </div>
                <div class="buttons">
                    <button type="button" class="btn btn-default btn-sm" onclick="countries.close()">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        <script src="js/bk.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            function toggle_side(elem){
                if($("#sidebar").hasClass("docked")){
                    $("#sidebar").removeClass("docked");
                    $(elem).children("span").removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-left");
                }
                else{
                    $("#sidebar").addClass("docked");
                    $(elem).children("span").removeClass("glyphicon-chevron-left").addClass("glyphicon-chevron-right");
                }
            }
        </script>
    </body>
</html>