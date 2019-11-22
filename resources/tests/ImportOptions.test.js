import '@testing-library/jest-dom/extend-expect';
import React from 'react';
import { BrowserRouter as Router } from 'react-router-dom';
import { render } from '@testing-library/react';

import ImportOptions from '@components/ImportOptions/ImportOptions';

describe('<ImportOptions/>', () => {
  describe('rendering', () => {
    const importProps = {
      handleImportCSV: jest.fn(),
      stopRenderImport: jest.fn()
    };
    test('should render', () => {
      const app = render(
        <Router>
          <ImportOptions {...importProps} />
        </Router>
      );
      expect(app).toBeTruthy();
    });
  });
});
