import React, { Component } from 'react';
import PropTypes from 'prop-types';
import ApiHelper from '@helpers/ApiHelper';

export default class ErrorHandler extends Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false, initTable: false };
  }

  componentDidMount() {
    const apiHelper = new ApiHelper();
    const url = new URL(location.href);
    const page = localStorage.getItem('page') || url.searchParams.get('page') || 1;
    const paginate = localStorage.getItem('paginate') || url.searchParams.get('paginate') || 40;
    apiHelper
      .get(`/billed_meals`, [
        {
          key: 'paginate',
          value: paginate
        },
        {
          key: 'page',
          value: page
        }
      ])
      .then(response => {
        const { pages } = response;
        let table = '';
        if (!Array.isArray(pages)) {
          table = pages.data;
        } else {
          table = pages;
        }
        this.setState({ initTable: table });
      })
      .catch(e => {
        this.setState({ hasError: e.message });
      });
  }

  componentDidCatch(e) {
    this.setState({ hasError: e });
  }

  render() {
    const { children } = this.props;
    const { hasError, initTable } = this.state;
    if (hasError && initTable) {
      const childrenRefresh = React.cloneElement(children, { initTable });
      return childrenRefresh;
    }
    return children;
  }
}

ErrorHandler.propTypes = {
  children: PropTypes.element.isRequired
};
