import React, { useEffect, useState } from 'react';
import PropTypes from 'prop-types';

function QuickFiltering(props) {
  const { handleQuickFiltering } = props;
  const [string, setString] = useState(
    localStorage.getItem('searchParam') || sessionStorage.getItem('searchParam') || false
  );
  useEffect(() => {
    if (string) {
      handleQuickFiltering(string.toUpperCase());
    } else {
      handleQuickFiltering(false);
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [string]);
  return (
    <div className="quick_filter">
      <div className="input_container">
        <div className="input_wrapper">
          <div className="input_wrapper-container">
            <input
              style={{ width: '100%', margin: '0 3px' }}
              placeholder="Search"
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

QuickFiltering.propTypes = {
  handleQuickFiltering: PropTypes.func.isRequired
};
