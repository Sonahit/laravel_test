import React from "react";
import PropTypes from "prop-types";

import "./Modal.scss";

const Modal = props => {
    const style = { 
            position: props.relative ? "relative" : "absolute",
            padding: props.relative ? "50px" : "0px"
    }
    
    return (
        <div className="modal" style={style}>
            <div className="modal__outer">
                <div className="modal__inner">{props.children}</div>
            </div>
        </div>
    );
};

export default Modal;

Modal.propTypes = {
    relative: PropTypes.bool,
    children: PropTypes.any,
};

Modal.defaultProps ={
    relative: false
}
