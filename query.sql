SELECT
    sub.flight_id                                           AS 'Номер рейса',
    sub.flight_date                                         AS 'Дата рейса',
    sub.`type`                                              AS 'Тип номенклатуры',
    sub.class                                               AS 'Класс',
    IFNULL(sub.codes_planned, "NOT FOUND")                  AS 'Код.План',
    IFNULL(sub.codes_fact, "NOT FOUND")                     AS 'Код.Факт',
    IFNULL(SUM(sub.meal_planned), 0)                        AS 'Количество.План',
    IFNULL(sub.meal_fact, 0)                                AS 'Количество.Факт',
    Round(SUM(sub.price_planned), 2)                        AS 'Стоимость без НДС.План',
    Round(sub.meal_fact * sub.price_fact, 2)                AS 'Стоимость без НДС.Факт',
    Round(
        SUM(sub.price_planned) -
        sub.meal_fact * sub.price_fact,
     2)   AS 'Дельта'                                            
FROM (SELECT
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
    ) AS sub
    GROUP BY sub.flight_id, sub.flight_date
    ORDER BY sub.flight_id, sub.flight_date;


/*
    Normalization queries (tables)
*/

/* table meal_info*/
SELECT 
meal_id, nomenclature, meal_type
FROM new_matrix
GROUP BY meal_id;

/* table billed_meals by nomenclature, name, type, class? */
SELECT nomenclature, `type`, class, `name` FROM billed_meals
GROUP BY `name`, nomenclature, class, `type`
ORDER BY id;

/* table billed_meals_price*/
SELECT `name`, qty, price_per_one, total, total_novat_discounted FROM billed_meals
GROUP BY nomenclature, price_per_one;

/* dunno */
SELECT id AS billed_meals_id, delivery_number, bortnumber FROM billed_meals AS bm
GROUP BY delivery_number, bortnumber;