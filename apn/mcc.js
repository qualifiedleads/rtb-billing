/*
* This script is used for sheet integration via Adwords Script feature.
* The update function is only limited to a single worksheet (tab). If 
* you have multiple accounts to update, make new instances of this
* script for each one.
*
* Mike
*/

/*
* Set the settings here. Change below variables to appropriate values.
*/

// URL of the google spreadsheet. Make sure you have write permission.
var SPREADSHEET_URL = 'https://docs.google.com/spreadsheets/d/1lq9tfH1Y3LOkNfUMHAB12NN-Lur3WtHLAyVPn5Zg9R8/edit#gid=0';

// Name of the worksheet to write to.
var SHEET_NAME = 'FrostByte';

// URI of the appnexus API webapp.
var APNAPP_URI = 'http://mau.said.ps/apn/run.php';

// Apnexus advertiser id.
var APNEXUS_ID = '574205';

// Google account id.
var ADWORDS_ID = '378-703-6763';

/*
* Do not change anything below this line.
*--------------------------------------------------------------------------------------------------------------------------
*/

// Initialize Sheet
var ss = SpreadsheetApp.openByUrl(SPREADSHEET_URL);
var sheet = ss.getSheetByName(SHEET_NAME);

// Date reference.
var date_utc = new Date();
date_utc.setHours(date_utc.getHours() - 8);
var date_now = setProperTimeZone(date_utc);

// Table settings
var REF_TABLE_COUNT = "Y1"; // Where to store current table count.
var REF_MONTH = "Z1"; // Where to store current month number.
var ROW_SIZE = 13; // Number of rows with text.
var ROW_ADJUST = 2; // Number of rows space to add including headers.
var VOFFSET_DATE = 0;
var VOFFSET_HEADERS = 1;
var VOFFSET_PREV_BALANCE = 2;
var VOFFSET_DIRECT_MEDIA_COST = 4;
var VOFFSET_INDIRECT_MEDIA_COST = 5;
var VOFFSET_DIRECT_AUC_FEE = 6;
var VOFFSET_INDIRECT_AUC_FEE = 7;
var VOFFSET_SEM_COST = 8;
var VOFFSET_TOTAL = 12;
var row_values = [
  ["Items","Description","Amount","Date","Reference"],
  ["Previous Balance","Automatic from previous month.","","","(Manual input.)"],
  ["Advance Payment","(Manual input.)","","","(Manual input.)"],
  ["Media Cost (Google AdX)","Cost from Google AdX","","(Hourly)","(Manual input.)"],
  ["Media Cost (Others)","Cost from Appnexus.","","(Hourly)","(Manual input.)"],
  ["Auction Service Fee","13% of Google AdX cost.","","(Hourly)","(Manual input.)"],
  ["Auction Service Fee","13% of others (Appnexus).","","(Hourly)","(Manual input.)"],
  ["Google Adwords Cost","Adwords account.","","(Hourly)","(Manual input.)"],
  ["Access Fee","(Manual input.)","","","(Manual input.)"],
  ["(Free slot deductions)","(Manual input.)","","","(Manual input.)"],
  ["(Free slot deductions)","(Manual input.)","","","(Manual input.)"],
  ["Balance","","0","",""]
];

// Script reference variables.

function main() {
  var reference_month_cell = sheet.getRange(REF_MONTH);
  var reference_table_cell = sheet.getRange(REF_TABLE_COUNT);
  var prev_month = reference_month_cell.getValue();
  var table_count = reference_table_cell.getValue();
  var month_now = date_now.getUTCMonth();
  if(table_count > 0){
    if(prev_month == month_now){
      updateTable();
      reference_month_cell.setValue(month_now);
    }
    else{
      newTable();
      updateTable();
      reference_month_cell.setValue(month_now);
    }
  }
  else{
    newTable();
    updateTable();
    reference_month_cell.setValue(month_now);
  }
}

function updateTable(){
  // Set cell ranges to write to.
  var table_count_cell = sheet.getRange(REF_TABLE_COUNT);
  var table_count = table_count_cell.getValue();
  if(table_count > 0){
    var row_index = ((table_count-1)*(ROW_SIZE+1))+ROW_ADJUST;
  }
  else{
    var row_index = ((table_count)*(ROW_SIZE+1))+ROW_ADJUST;
  }
  
  // If Adwords account is enabled.
  if(ADWORDS_ID != ""){
    // Get Adwords values.
    var accountIterator = MccApp.accounts().withIds([ADWORDS_ID]).get();
    var account = accountIterator.next();
    var stats = account.getStatsFor("THIS_MONTH");
    var adcost = stats.getCost();
    // Write Adwords ads cost to sheet.
    var media_cost_sem_cell = 'C'+(row_index+VOFFSET_SEM_COST);
    writeCell(media_cost_sem_cell,'-'+adcost);
  }
  
  // If Appnexus account is enabled.
  if(APNEXUS_ID != ""){
    // Get Appnexus values.
    var apn = UrlFetchApp.fetch(APNAPP_URI+'?billing='+APNEXUS_ID);
    var media_cost = JSON.parse(apn.getContentText());
    // Write Appnexus media cost to sheet.
    var media_cost_direct_cell = 'C'+(row_index+VOFFSET_DIRECT_MEDIA_COST);
    var media_cost_indirect_cell = 'C'+(row_index+VOFFSET_INDIRECT_MEDIA_COST);
    writeCell(media_cost_direct_cell,'-'+media_cost.direct);
    writeCell(media_cost_indirect_cell,'-'+media_cost.indirect);
  }

  // Write UTC date & time to sheet.
  var balance_text_cell = 'A'+(row_index+VOFFSET_TOTAL);
  writeCell(balance_text_cell,'Balance as of '+date_now.toUTCString());
}

function newTable(){
  var table_count_cell = sheet.getRange(REF_TABLE_COUNT);
  var table_count = table_count_cell.getValue();
  var row_index = (table_count*(ROW_SIZE+1))+ROW_ADJUST;
  
  // Write the date.
  var date_cell = sheet.getRange("E"+row_index);
  var date_month = date_now.getUTCMonth();
  var date_year = date_now.getUTCFullYear();
  date_cell.setValue("Month, Year");
  date_cell.setFontWeight("bold");
  
  // Write table row defaults.
  for(var x in row_values){
    sheet.appendRow(row_values[x]);
  }
  
  // Format table.
  var headers_voffset = row_index+VOFFSET_HEADERS;
  var headers_cols = sheet.getRange("A"+headers_voffset+":E"+headers_voffset);
  headers_cols.setBackground("#fff2cc");
  headers_cols.setFontWeight("bold");
  headers_cols.setBorder(true, null, true, null, false, false);
  
  var body_amounts = sheet.getRange('C'+(row_index+VOFFSET_PREV_BALANCE)+':C'+(row_index+(VOFFSET_TOTAL-1)));
  body_amounts.setNumberFormat("$#,##0.00");
  
  var footer_voffset = row_index+VOFFSET_TOTAL;
  var footer_row = sheet.getRange("A"+footer_voffset+":E"+footer_voffset);
  var footer_amount_cell = sheet.getRange('C'+footer_voffset);
  footer_row.setBorder(true, null, null, null, false, false);
  footer_row.setFontWeight("bold");
  footer_amount_cell.setNumberFormat("$#,##0.00");
  
  var balance_cells = sheet.getRange("A"+footer_voffset+":B"+footer_voffset);
  balance_cells.merge();
  
  // Update row count.
  table_count_cell.setValue(table_count+1);
  
  // Apply forumulas.
  var media_cost_direct_ref = 'C'+(row_index+VOFFSET_DIRECT_MEDIA_COST);
  var media_cost_indirect_ref = 'C'+(row_index+VOFFSET_INDIRECT_MEDIA_COST);
  var auction_fee_direct_cell = sheet.getRange('C'+(row_index+VOFFSET_DIRECT_AUC_FEE));
  var auction_fee_indirect_cell = sheet.getRange('C'+(row_index+VOFFSET_INDIRECT_AUC_FEE));
  auction_fee_direct_cell.setValue('='+media_cost_direct_ref+'*0.13');
  auction_fee_indirect_cell.setValue('='+media_cost_indirect_ref+'*0.13');
  date_cell.setFormula('=TEXT(DATE('+date_year+','+eval(date_month+1)+',1),"MMMM, YYYY")');
  footer_amount_cell.setFormula('=SUM(C'+(row_index+VOFFSET_PREV_BALANCE)+':C'+(row_index+(VOFFSET_TOTAL-1))+')');
  
  // Apply previous balance.
  if(table_count > 0) {
    var prev_total_row = (row_index-ROW_ADJUST);
    var prev_total_cell = sheet.getRange('C'+prev_total_row);
    var prev_balance_cell = sheet.getRange('C'+(row_index+VOFFSET_PREV_BALANCE));
    prev_balance_cell.setValue(prev_total_cell.getValue());
  }
}

function writeCell(cell_range,value){
  var cell = sheet.getRange(cell_range);
  cell.setValue(value);
}

function writeRow(values){
  var ss = SpreadsheetApp.openByUrl(SPREADSHEET_URL);
  var sheet = ss.getSheetByName(SHEET_NAME); 
  sheet.appendRow(values);
}

function setProperTimeZone(date) {
  return new Date(Utilities.formatDate(date, 'Africa/Accra', "MMM dd,yyyy HH:mm:ss"));
}