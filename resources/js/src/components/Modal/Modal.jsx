import React from 'react';
import PropTypes from 'prop-types';

import './Modal.scss';

const Modal = props => {
  const { relative, children } = props;
  const style = {
    position: relative ? 'relative' : 'absolute',
    padding: relative ? '50px' : '0px'
  };

  return (
    <div className="modal" style={style}>
      <div className="modal__outer">
        <div className="modal__inner">{children}</div>
      </div>
    </div>
  );
};

export default Modal;

Modal.defaultProps = {
  children: <></>
};

Modal.propTypes = {
  relative: PropTypes.bool,
  children: PropTypes.element
};

Modal.defaultProps = {
  relative: false
};
