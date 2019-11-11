import { getIndex } from "./filterTable.js";

export default function sortTable(table, sortOptions) {
    const { dataSort, asc } = sortOptions;
    if (!dataSort) return table;
    const comparer = asc => (a, b) =>
        ((v1, v2) => (v1 !== "" && v2 !== "" && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)))(asc ? a : b, asc ? b : a);
    const [index, subIndex] = getIndex(dataSort);
    return table.sort((trA, trB) => {
        if (subIndex) {
            const vA = trA[index][subIndex];
            const vB = trB[index][subIndex];
            return comparer(!asc)(vA, vB);
        }
        const vA = index === "delta" ? trA.plan_attributes.price - trA.fact_attributes.price : trA[index];
        const vB = index === "delta" ? trB.plan_attributes.price - trB.fact_attributes.price : trB[index];
        return comparer(asc)(vA, vB);
    });
}
