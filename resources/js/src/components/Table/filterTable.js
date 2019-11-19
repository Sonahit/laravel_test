export function getIndex(key) {
  switch (key) {
    case 'flight_date':
      return ['date', false];
    case 'flight_id':
      return ['id', false];
    case 'plan_code':
      return ['plan_attributes', 'codes'];
    case 'plan_qty':
      return ['plan_attributes', 'qty'];
    case 'plan_price':
      return ['plan_attributes', 'price'];
    case 'fact_code':
      return ['fact_attributes', 'codes'];
    case 'fact_qty':
      return ['fact_attributes', 'qty'];
    case 'fact_price':
      return ['fact_attributes', 'price'];
    case 'delta':
      return ['delta', false];
    default:
      return [false, false];
  }
}

function filterByNumber(table, startValue, endValue, index, subIndex) {
  const start = parseInt(startValue);
  const end = parseInt(endValue);
  if (end < start || isNaN(start) || isNaN(end)) return table;
  return table.filter(tr => {
    if (subIndex) {
      const trNumber = parseInt(tr[index][subIndex]);
      if (!trNumber) return false;
      return trNumber >= start && trNumber <= end;
    }
    const trNumber =
      index === 'delta' ? tr.plan_attributes.price - tr.fact_attributes.price : parseInt(tr[index]);
    return trNumber >= start && trNumber <= end;
  });
}

function filterByString(table, string, index, subIndex) {
  if (!string.toLocaleLowerCase) return table;
  const s = string.toLocaleLowerCase();
  return table.filter(tr => {
    if (subIndex) {
      const trString = tr[index][subIndex];
      if (!trString) return false;
      return trString.some(code => code.toLocaleLowerCase().includes(s));
    }
    const trString = tr[index];
    return trString.some(code => code.toLocaleLowerCase().includes(s));
  });
}

function filterByDate(table, startDate, endDate, index, subIndex) {
  const start = Date.parse(startDate);
  const end = Date.parse(endDate);
  return table.filter(tr => {
    if (subIndex) {
      const trDate = Date.parse(tr[index][subIndex]);
      if (!trDate) return false;
      return trDate >= start && trDate <= end;
    }
    const trDate = Date.parse(tr[index]);
    return trDate >= start && trDate <= end;
  });
}

export default function filterTable(table, filters) {
  // const { startValue, endValue, key, method } = filter;
  // if (!key || !method) return table;
  // if (!startValue && startValue !== 0) return table;
  // const [index, subIndex] = getIndex(key);
  // if (!index) return table;
  // if (method === "date") {
  //     return filterByDate(table, startValue, endValue || startValue, index, subIndex);
  // } else if (method === "number") {
  //     return filterByNumber(table, startValue, endValue, index, subIndex);
  // } else if (method === "string") {
  //     return filterByString(table, startValue, index, subIndex);
  // }
  return Object.keys(filters).reduce((filteredTable, key) => {
    const filter = filters[key];
    if (filter) {
      const { method, endValue, startValue } = filter;
      const [index, subIndex] = getIndex(key);
      if (!index) return filteredTable;
      if (method === 'date') {
        return filterByDate(filteredTable, startValue, endValue || startValue, index, subIndex);
      }
      if (method === 'number') {
        return filterByNumber(filteredTable, startValue, endValue, index, subIndex);
      }
      if (method === 'string') {
        return filterByString(filteredTable, startValue, index, subIndex);
      }
    }
    return filteredTable;
  }, table);
}
