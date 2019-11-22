/* eslint-disable no-console */
import '@testing-library/jest-dom/extend-expect';
import './setupSpyError';
import React from 'react';
import Filters from '@components/Filters/Filters';
import { render } from '@testing-library/react';

describe('<Filters/>', () => {
  describe('rendering', () => {
    test("should'nt render without props", () => {
      expect(() => {
        render(<Filters />);
      }).toThrow();
      expect(console.error).toHaveBeenCalled();
    });
    test('should render with props', () => {
      const filtersProps = {
        method: 'date',
        filters: {},
        handleFilterSelect: jest.fn(),
        handleFilterValue: jest.fn(),
        handleFilterReset: jest.fn(),
        resetAllFilters: jest.fn()
      };
      const app = render(<Filters {...filtersProps} />);
      expect(app).toBeTruthy();
    });
  });
});
