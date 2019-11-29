import React from 'react';

import PropTypes from 'prop-types';

const TableHead = props => {
  const { tHead, handleSort, sortValue } = props;
  const stringToBool = str => {
    if (str === 'false') return false;
    return true;
  };
  const asc = stringToBool(localStorage.getItem('asc') || sessionStorage.getItem('asc'));
  return (
    <thead className="main-table__thead">
      {tHead.map((tr, i) => (
        <tr key={tr[i].text}>
          {tr.map(th => (
            <Th
              key={`${th.sortable ? 'sortable' : 'not-sortable'}__${th.dataSort || th.text}`}
              handleSort={handleSort}
              sortValue={sortValue}
              asc={asc}
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
  tHead: PropTypes.array.isRequired,
  sortValue: PropTypes.oneOfType([PropTypes.bool, PropTypes.string]).isRequired
};

const Th = props => {
  const { sortable, type, dataSort, text, colSpan, rowSpan, handleSort, sortValue, asc } = props;
  return (
    <th
      className={sortable ? 'main-table__th--sortable' : 'main-table__th'}
      type={type || ''}
      data-sort={dataSort || ''}
      rowSpan={rowSpan || 1}
      colSpan={colSpan || 1}
      onClick={
        sortable
          ? e => {
              handleSort(e, type, dataSort);
            }
          : e => handleSort(e, false, false)
      }
    >
      {sortValue !== dataSort && <span className="asc">{text}</span>}
      {sortValue === dataSort && <span className={asc ? `active asc` : 'active desc'}>{text}</span>}
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
  rowSpan: '',
  asc: true
};

Th.propTypes = {
  sortable: PropTypes.bool.isRequired,
  sortValue: PropTypes.oneOfType([PropTypes.bool, PropTypes.string]).isRequired,
  handleSort: PropTypes.func,
  asc: PropTypes.bool,
  type: PropTypes.string,
  dataSort: PropTypes.string,
  text: PropTypes.string,
  colSpan: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  rowSpan: PropTypes.oneOfType([PropTypes.string, PropTypes.number])
};
