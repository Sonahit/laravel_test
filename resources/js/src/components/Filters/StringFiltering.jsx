import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';

const StringFiltering = props => {
  const { filteringKey, method, startValue, reset, setReset } = props;
  const [string, setString] = useState('');
  const [filter, setFilter] = useState(filteringKey);
  if (filter !== filteringKey) {
    setFilter(filteringKey);
    setString(startValue);
  }
  if (reset && string !== '') {
    setString(new Date(''));
  }
  useEffect(() => {
    if (reset) {
      setReset(false);
    }
    if (!string) {
      props.handleFilterReset(filteringKey);
    } else {
      props.handleFilterValue(filteringKey, method, string, '', '', '');
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [filteringKey, string]);
  return (
    <div className="input_container">
      <div className="input_wrapper">
        <div className="input_wrapper-container">
          <input
            style={{ width: '100%', margin: '0 3px' }}
            placeholder="Введите текст"
            onChange={({ target }) => setString(target.value)}
            value={string !== '' ? string : ''}
          />
        </div>
      </div>
    </div>
  );
};

export default StringFiltering;

StringFiltering.defaultProps = {
  startValue: '',
  reset: false
};

StringFiltering.propTypes = {
  method: PropTypes.string.isRequired,
  startValue: PropTypes.any,
  filteringKey: PropTypes.string.isRequired,
  handleFilterValue: PropTypes.func.isRequired,
  handleFilterReset: PropTypes.func.isRequired,
  setReset: PropTypes.func.isRequired,
  reset: PropTypes.bool
};
