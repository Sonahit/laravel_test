import '@testing-library/jest-dom/extend-expect';
import React from 'react';

import ErrorHandler from '@handlers/ErrorHandler';
import { render } from '@testing-library/react';
import { mockComponent } from 'react-dom/test-utils';

const ErrorMockComponent = () => {
  throw new Error();
};
describe('<ErrorHandler/>', () => {
  describe('rendering', () => {
    test('should render with error', () => {
      const app = render(
        <ErrorHandler>
          <ErrorMockComponent />
        </ErrorHandler>
      );
      expect(app).toBeTruthy();
    });

    test('should render', () => {
      const app = render(<ErrorHandler>{mockComponent}</ErrorHandler>);
      expect(app).toBeTruthy();
    });
  });
});
