const codes = ["CHM01", "CHM011", "CHM012"];

const data = Array.from({ length: 20 }, (_, i) => ({
    id: i + 1,
    date: new Date(`2017-01-${Math.ceil(Math.random() * 30)}`),
    class: "Class",
    type: "Type",
    fact_attributes: {
        codes: Array.from({ length: Math.ceil(Math.random() * 3) }, (_, i) => codes[i]),
        price: Math.ceil(Math.random() * 10000),
        qty: Math.ceil(Math.random() * 10)
    },
    plan_attributes: {
        codes: Array.from({ length: Math.ceil(Math.random() * 3) }, (_, i) => codes[i]),
        price: Math.ceil(Math.random() * 10000),
        qty: Math.ceil(Math.random() * 10)
    }
}));

module.exports = JSON.stringify({ pages: data });
