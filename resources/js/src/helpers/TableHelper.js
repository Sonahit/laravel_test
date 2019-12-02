import * as sortKeys from './SortKeys';

export default class TableHelper {
  csvToJson(csv) {
    return csv.map(el => ({
      id: parseInt(el[0], 10),
      date: el[1],
      type: el[2],
      class: el[3],
      fact_codes: el[5].replace('s', '').split(','),
      fact_price: parseInt(el[9], 10),
      fact_qty: parseInt(el[7], 10),
      plan_codes: el[4].replace('s', '').split(','),
      plan_price: parseInt(el[8], 10),
      plan_qty: parseInt(el[6], 10)
    }));
  }

  getTable() {
    return document.getElementsByClassName('main-table')[0];
  }

  getTBody(table) {
    return Array.from(table.rows).filter(v => v.rowIndex > 1);
  }

  flatTHead(table) {
    return Array.from(table.tHead.rows)
      .map(thead => Array.from(thead.children).map(tr => tr))
      .flat();
  }

  csvAsTable(rawCSV) {
    const csv = rawCSV.split('\n');
    const head = csv.filter((_, i) => i <= 1).map(e => e.split(';'));
    const body = csv.filter((_, i) => i > 1).map(e => e.split(';'));
    return [head, body];
  }

  values(raw) {
    const row = [];
    raw.forEach((v, i) => {
      row[i] = [];
      if (v.cells) {
        // For body
        Array.from(v.cells).forEach(cell => row[i].push(cell.innerText));
      } else {
        // For headers
        Array.from(v).forEach(h => row[i].push(h.innerText || h));
      }
    });
    return row;
  }

  valuesWithAttr(raw) {
    const row = [];
    raw.forEach((v, i) => {
      row[i] = [];
      if (v.cells) {
        // For body
        Array.from(v.cells).forEach(cell =>
          row[i].push({
            v: cell.innerText,
            colSpan: cell.colSpan || 1,
            rowSpan: cell.rowSpan || 1
          })
        );
      } else {
        // For headers
        Array.from(v).forEach(h =>
          row[i].push({
            v: h.innerText || h,
            colSpan: h.colSpan || 1,
            rowSpan: h.rowSpan || 1
          })
        );
      }
    });
    return row;
  }

  getTHead(table) {
    return Array.from(table.tHead.rows);
  }

  tableToJson(table) {
    const [head, body] = [this.getTHead(table), this.getTBody(table)];
    const [vhead, vbody] = [this.valuesWithAttr(head), this.values(body)];
    return JSON.parse(
      JSON.stringify({
        head: vhead,
        body: vbody
      })
    );
  }

  th(key) {
    return this.flatTHead(this.getTable()).find(th => th.getAttribute('data-sort') === key);
  }

  getSortIndex(index) {
    // prettier-ignore
    const { 
            FLIGHTDATE_H, 
            FLIGHTID_H,
            DELTA_H,
            PRICEFACT_H,
            PRICEPLAN_H,
            CODEFACT_H,
            CODEPLAN_H,
            QTYPLAN_H,
            QTYFACT_H,
            NOTSORTABLE
        } = sortKeys.headerKeys;
    // prettier-ignore
    const {
            FLIGHTDATE_R,
            FLIGHTID_R, 
            QTYPLAN_R, 
            QTYFACT_R, 
            CODEFACT_R, 
            CODEPLAN_R, 
            PRICEFACT_R, 
            PRICEPLAN_R, 
            DELTA_R
        } = sortKeys.rowKeys;
    if (index === FLIGHTID_H) return FLIGHTID_R;
    if (index === FLIGHTDATE_H) return FLIGHTDATE_R;
    if (index === DELTA_H) return DELTA_R;
    if (index === CODEPLAN_H) return CODEPLAN_R;
    if (index === CODEFACT_H) return CODEFACT_R;
    if (index === QTYPLAN_H) return QTYPLAN_R;
    if (index === QTYFACT_H) return QTYFACT_R;
    if (index === PRICEPLAN_H) return PRICEPLAN_R;
    if (index === PRICEFACT_H) return PRICEFACT_R;
    return NOTSORTABLE;
  }
}
