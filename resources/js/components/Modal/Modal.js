import React, { Component } from 'react'
import PropTypes from 'prop-types';

import './Modal.scss';

export default class Modal extends Component {
    render() {
        return (
            <div className="modal"> 
                <div className="modal__outer">
                    <div className="modal__inner">
                        {this.props.children}
                    </div>
                </div>
            </div>
        )
    }
}

Modal.propTypes = {
    children: PropTypes.any,
}



