<!DOCTYPE html>
<html lang="en">
    <head>
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
                Inventory Research
            </div>
            <div class="user-widget dropdown">
                <button class="btn btn-default dropdown-toggle" id="user_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <span class="glyphicon glyphicon-user"></span>
                    User&nbsp;
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="user_dropdown">
                    <li><a href="#">Option 1</a></li>
                    <li><a href="#">Option 2</a></li>
                </ul>
            </div>
        </header>
        <main>
            <aside>
                <div class="panel panel-default">
                    <div class="panel-heading">Search</div>
                    <div class="panel-body">
                    <!-- Form Fields -->
                        <form id="search_form">
                            <div class="field-entry">
                                <input type="text" class="form-control" name="keyword" placeholder="Name or ID" />
                            </div>
                            <div class="field-entry">
                                <div class="filter-title">Country</div>
                                <div class="filter-content">
                                    <ul id="filter_country">
                                        <li>
                                            <label>
                                                <input type="radio" name="country" value="all" checked="true" onclick="countries.all()" />
                                                &nbsp;Worldwide (All Countries)
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="radio" name="country" value="" onclick="return country_select()" />
                                                &nbsp;<span id="filter_country_choice" title="Click to change.">Select Country</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="field-entry">
                                <div class="filter-title">Device Type</div>
                                <div class="filter-content">
                                    <ul>
                                        <li>
                                            <label><input type="radio" name="device_type" value="all" checked="true" /> All</label>
                                        </li>
                                        <li>
                                            <label><input type="radio" name="device_type" value="0" /> Desktop</label>
                                        </li>
                                        <li>
                                            <label><input type="radio" name="device_type" value="1" /> Phone</label>
                                        </li>
                                        <li>
                                            <label><input type="radio" name="device_type" value="2" /> Tablet</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="field-entry">
                                <div class="filter-title">Supply Type</div>
                                <div class="filter-content">
                                    <ul>
                                        <li>
                                            <label><input type="radio" name="supply_type" value="all" checked="true" /> All</label>
                                        </li>
                                        <li>
                                            <label><input type="radio" name="supply_type" value="0" /> Web</label>
                                        </li>
                                        <li>
                                            <label><input type="radio" name="supply_type" value="1" /> Mobile Web</label>
                                        </li>
                                        <li>
                                            <label><input type="radio" name="supply_type" value="2" /> Mobile App</label>
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
                    <div id="pagination_container" class="pagination">
                        Sort by &nbsp;
                        <select id="sorting" onchange="page_sort()">
                            <option value="seller_name">Seller</option>
                            <!--<option value="seller_id">ID</option>-->
                            <option value="filtered_impressions">Filtered Impressions</option>
                            <!--
                            <option value="total_impressions">Total Impressions</option>
                            <option value="filtered_uniques">Filtered Uniques</option>
                            <option value="total_uniques">Average Uniques</option>
                            <option value="partner_type">Partner Type</option>
                            <option value="inventory_trust">Inventory Trust</option>
                            -->
                        </select>
                        <select id="ordering" onchange="page_sort()">
                            <option value="asc">Ascending</option>
                            <option value="des">Descending</option>
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
        <script src="js/functions.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>