export default [
    [
        {
            sortable: true,
            type: "number",
            dataSort: "flight_id",
            rowSpan: 2,
            text: "Номер полёта"
        },
        {
            sortable: true,
            type: "date",
            dataSort: "flight_date",
            rowSpan: 2,
            text: "Дата полёта"
        },
        {
            sortable: false,
            rowSpan: 2,
            text: "Класс"
        },
        {
            sortable: false,
            rowSpan: 2,
            text: "Тип номенклатуры"
        },
        {
            sortable: false,
            colSpan: 2,
            text: "Код"
        },
        {
            sortable: false,
            colSpan: 2,
            text: "Количество"
        },
        {
            sortable: false,
            colSpan: 2,
            text: "Цена"
        },
        {
            sortable: true,
            type: "number",
            dataSort: "delta",
            rowSpan: 2,
            text: "Дельта"
        }
    ],
    [
        {
            sortable: true,
            type: "string",
            dataSort: "plan_code",
            text: "План"
        },
        {
            sortable: true,
            type: "string",
            dataSort: "fact_code",
            text: "Факт"
        },
        {
            sortable: true,
            type: "number",
            dataSort: "plan_qty",
            text: "План"
        },
        {
            sortable: true,
            type: "number",
            dataSort: "fact_qty",
            text: "Факт"
        },
        {
            sortable: true,
            type: "number",
            dataSort: "plan_price",
            text: "План"
        },
        {
            sortable: true,
            type: "number",
            dataSort: "fact_price",
            text: "Факт"
        }
    ]
];
