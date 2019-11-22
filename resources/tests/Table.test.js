import '@testing-library/jest-dom/extend-expect';
import React from 'react';
import { render } from '@testing-library/react';

import Table from '@components/Table/Table';

describe('<Table/>', () => {
  describe('rendering', () => {
    const tableProps = {
      table: [],
      handleRefresh: jest.fn(),
      handleImportCSV: jest.fn(),
      stopRenderImport: jest.fn(),
      fetchAllData: jest.fn(),
      setFetch: jest.fn(),
      rememberTable: jest.fn(),
      forgetTable: jest.fn()
    };
    test('should render', () => {
      const app = render(<Table {...tableProps} />);
      expect(app).toBeTruthy();
    });
  });
});
