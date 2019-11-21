import React, { Component } from 'react';
import PropTypes from 'prop-types';

export default class ErrorHandler extends Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false };
  }

  componentDidCatch(e) {
    this.setState({ hasError: e });
  }

  render() {
    const { children } = this.props;
    const { hasError } = this.state;
    if (hasError) {
      return <h1> Something went wrong </h1>;
    }
    return children;
  }
}

ErrorHandler.propTypes = {
  children: PropTypes.element.isRequired
};
