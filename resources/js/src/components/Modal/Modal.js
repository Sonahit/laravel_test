import React from "react";
import PropTypes from "prop-types";

import "./Modal.scss";

const Modal = props => {
    return (
        <div className="modal">
            <div className="modal__outer">
                <div className="modal__inner">{props.children}</div>
            </div>
        </div>
    );
};

export default Modal;

Modal.propTypes = {
    children: PropTypes.any
};
