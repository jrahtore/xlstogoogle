<?php
  define("host","localhost");
  define("dbname","asddsad");
  define("username","petty");
  define("password","petty@2021");
  $top_500_keys_common = [
    'rank1',
    'ndc',
    'prod_name',
    'strength',
    'pkg_size',
    'form',
    'mfr',
    'stp',
    'low_sold',
    'avg_sold',
    'high_sold',
    'sold_variance',
    'best_price_today',
    'best_price_exp_date',
    'best_price_qty_available',
    'avg_trxade_price_today',
    'num_results'
  ];
  $configSheet = ['top_500'=>[
    'sheet_0'=>array_merge($top_500_keys_common,['tp'=>'By Units']),
    'sheet_1'=>array_merge($top_500_keys_common,['tp'=>'In Demand - By Frequency']),
    'sheet_2'=>array_merge($top_500_keys_common,['tp'=>'By Total Sales G']),
    'sheet_3'=>array_merge($top_500_keys_common,['tp'=>'By Total Sales B']),
    'sheet_4'=>array_merge($top_500_keys_common,['tp'=>'Products By Units']),
    'sheet_5'=>array_merge($top_500_keys_common,['tp'=>'Product By Sales']),
    'sheet_6'=>[
    'rank',
    'manufacturer_name',
    'percent_unit_sold',
    'percentof_total_unit_sold',
    'by_sales_or_unit',
    'tp'=>'Top Manufacturer'
    ],
  ]];
  function pr($a){
    echo "<pre>";
    print_r($a);
    echo "</pre>";
  }
 ?>
