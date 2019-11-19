import React from 'react';

import PropTypes from 'prop-types';

const TableHead = props => {
  const { tHead, handleSort } = props;
  return (
    <thead className="main-table__thead">
      {tHead.map((tr, i) => (
        <tr key={tr[i].text}>
          {tr.map(th => (
            <Th
              key={`${th.sortable ? 'sortable' : 'not-sortable'}__${th.dataSort || th.text}`}
              handleSort={handleSort}
              {...th}
            />
          ))}
        </tr>
      ))}
    </thead>
  );
};

export default TableHead;

TableHead.propTypes = {
  handleSort: PropTypes.func.isRequired,
  tHead: PropTypes.array.isRequired
};

const Th = props => {
  const { sortable, type, dataSort, text, colSpan, rowSpan, handleSort } = props;
  return (
    <th
      className={sortable ? 'main-table__th--sortable' : 'main-table__th'}
      type={type || ''}
      data-sort={dataSort || ''}
      rowSpan={rowSpan || 1}
      colSpan={colSpan || 1}
      onClick={sortable ? e => handleSort(e, type, dataSort) : e => handleSort(e, false, false)}
    >
      <span className={sortable ? 'asc' : ''}>{text}</span>
    </th>
  );
};

const doesntImplemented = () => 'NO FUNCTION';

Th.defaultProps = {
  handleSort: doesntImplemented,
  type: '',
  dataSort: '',
  text: '',
  colSpan: '',
  rowSpan: ''
};

Th.propTypes = {
  sortable: PropTypes.bool.isRequired,
  handleSort: PropTypes.func,
  type: PropTypes.string,
  dataSort: PropTypes.string,
  text: PropTypes.string,
  colSpan: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  rowSpan: PropTypes.oneOfType([PropTypes.string, PropTypes.number])
};
