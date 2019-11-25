import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';

function QuickFiltering(props) {
  const { startValue, reset } = props;
  const [string, setString] = useState(startValue);
  useEffect(() => {
    if (string) {
      props.handleQuickFiltering(string);
    } else {
      props.handleQuickFiltering(false);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [string]);
  if (reset && string !== false) {
    setString(false);
  }
  return (
    <div className="quick_filter">
      <div className="input_container">
        <div className="input_wrapper">
          <div className="input_wrapper-container">
            <input
              style={{ width: '100%', margin: '0 3px' }}
              placeholder="Быстрый фильтр"
              onChange={({ target }) => setString(target.value)}
              value={string || ''}
            />
          </div>
        </div>
      </div>
    </div>
  );
}

export default QuickFiltering;

QuickFiltering.defaultProps = {
  reset: false
};

QuickFiltering.propTypes = {
  startValue: PropTypes.any.isRequired,
  handleQuickFiltering: PropTypes.func.isRequired,
  reset: PropTypes.bool
};
