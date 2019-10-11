<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billed_Meals extends Model
{
    #TODO DATA METHODS
    protected $table = 'billed_meals';
    protected $primaryKey = 'id';

    public $from = '20170101';
    public $to = '20170131';
    public $where = [['type','=','Комплект'],['class','=','Бизнес']];
    public const NO_LIMIT = -1;
    
    //#TODO RELATIONSHIPS
    public function flight_load(){
        return $this->hasOne('App\Flight_load');
    }


    /**
     * @return \App\Billed_Meals  
     */
    public function getBilledMeals($rows = '*', $limit = 10, $where = ""){
        return Billed_Meals::select($rows)
        ->whereBetween('flight_date', [$this->from, $this->to])
        ->where($where)
        ->limit($limit)
        ->orderBy('flight_id', 'asc')
        ->orderBy('flight_date', 'asc')
        ->get();
    }

    public function getReport($limit, $from, $to){
        /*SELECT
        bms.flight_id AS flight_id,
        bms.flight_date AS flight_date,
        bms.`type` AS `type`,          
        bms.class AS class ,
        GROUP_CONCAT(DISTINCT nm.iata_code SEPARATOR ', ') AS codes_planned,
        GROUP_CONCAT(DISTINCT bm.iata_code SEPARATOR ', ') AS codes_fact,
        nm.meal_qty AS meal_planned,
        nm.meal_qty * bmp.price AS price_planned,
        SUM(DISTINCT bm.qty) AS meal_fact,
        bms.price_per_one * 1.14 * 1.04 AS price_fact
        FROM billed_meals AS bms
        INNER JOIN (flight_load AS fload) ON
            fload.id = bms.flight_load_id    
        INNER JOIN billed_meals AS bm ON
            bm.flight_id = bms.flight_id  
            AND bm.flight_date = bms.flight_date
            AND bm.class = bms.class
            AND bm.`type` = bms.`type`
            AND bm.iata_code <> 'ALC'
        INNER JOIN meal_rules AS ml ON
            ml.iata_code = bms.iata_code
            AND WEEK(bms.flight_date) % 2 = ml.weeknumber
        INNER JOIN (new_matrix AS nm, business_meal_prices AS bmp) ON
            nm.iata_code = ml.iata_code
            AND nm.passenger_amount = fload.business
            AND bmp.nomenclature = nm.nomenclature
        WHERE
            bms.class = 'Бизнес'
            AND DATE(bms.flight_date) >= '20170101'
            AND DATE(bms.flight_date) <= '20170131'
            AND TIME(bms.flight_date) >=  '00:00:00'
            AND TIME(bms.flight_date) <=  '23:59:59'
            AND  bms.`type` = 'Комплект'
        GROUP BY bms.flight_id, bms.flight_date, nm.nomenclature
        */
        return ;
    }
}
