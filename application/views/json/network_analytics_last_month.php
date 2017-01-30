{
    "report": {
        "report_type":"network_analytics",
        "columns": [
            "seller_member_name",
            "seller_member_id",
            "cost",
            "imps"
        ],
        "filters": [
            {
                "advertiser_id": <?php echo $advertiser_ids;?>
            }
        ],
        "report_interval": "last_month",
        "format":"csv"
    }
}