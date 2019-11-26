import React from 'react';
import PropTypes from 'prop-types';

import filterTable from './filterTable';
import sortTable from './sortTable';

const TableBody = props => {
  const { table, filters, sort } = props;
  const tBody = filterTable(sortTable(table, sort), filters);
  return (
    <tbody role="table-body" className="main-table__tbody">
      {tBody.map(tr => (
        <TableElement key={`${tr.id}__${tr.date}`} {...tr} />
      ))}
    </tbody>
  );
};

export default TableBody;

TableBody.propTypes = {
  table: PropTypes.oneOfType([PropTypes.bool, PropTypes.array]).isRequired,
  filters: PropTypes.object.isRequired,
  sort: PropTypes.object.isRequired
};

const TableElement = props => {
  const {
    id,
    date,
    type,
    plan_codes,
    plan_qty,
    plan_price,
    fact_codes,
    fact_qty,
    fact_price,
    delta
  } = props;
  // eslint-disable-next-line react/destructuring-assignment
  const nom_class = props.class;
  return (
    <tr>
      <td className="main-table__td">{id}</td>
      <td className="main-table__td">{date}</td>
      <td className="main-table__td">{nom_class}</td>
      <td className="main-table__td">{type}</td>
      <td className="main-table__td">{plan_codes.join(', ') || 'NO DATA'}</td>
      <td className="main-table__td">{fact_codes.join(', ')}</td>
      <td className="main-table__td">{plan_qty}</td>
      <td className="main-table__td">{fact_qty}</td>
      <td className="main-table__td">{plan_price.toFixed(2)}</td>
      <td className="main-table__td">{fact_price.toFixed(2)}</td>
      <td className="main-table__td">{delta.toFixed(2)}</td>
    </tr>
  );
};

TableElement.propTypes = {
  id: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
  date: PropTypes.string.isRequired,
  class: PropTypes.string.isRequired,
  type: PropTypes.string.isRequired,
  plan_codes: PropTypes.array.isRequired,
  plan_price: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
  plan_qty: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
  fact_codes: PropTypes.array.isRequired,
  fact_price: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
  fact_qty: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired,
  delta: PropTypes.oneOfType([PropTypes.string, PropTypes.number]).isRequired
};
