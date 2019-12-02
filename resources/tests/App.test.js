import '@testing-library/jest-dom/extend-expect';
import React from 'react';
import { render, fireEvent, cleanup, wait } from '@testing-library/react';
import App from '@src/App';
import { Simulate } from 'react-dom/test-utils';

describe('<App/>', () => {
  beforeAll(() => {
    const data = jest.genMockFromModule('./__mocks__/fetchMock');
    fetch.mockResponse(data);
  });

  afterEach(() => {
    cleanup();
  });

  test('should render App', () => {
    const app = render(<App />);
    expect(app).toBeTruthy();
  });

  describe('import data', () => {
    test('should upload csv file', async () => {
      const app = render(<App />);
      fireEvent.click(app.getByText('Import'));
      await wait(() => app.rerender(<App />));
      expect(location.href).toMatch('import');
      const csv = jest.genMockFromModule('./__mocks__/csvMock.js');
      const file = new File([csv], 'csv.csv', {
        type: 'text/csv'
      });
      const input = app.getByLabelText('Choose File');
      Simulate.change(input, {
        target: { files: [file] }
      });
      await wait(() => app.rerender(<App />));
      await new Promise(resolve => setTimeout(resolve(), 5 * 1000));
      const impBtn = app.getByRole('import');
      const label = app.getByRole('import_data');
      expect(label.innerText).not.toMatch('Choose file');
      fireEvent.click(impBtn);
      app.rerender(<App />);
      expect(location.href).not.toMatch('import');
      expect(app.getByRole('table-body').children.length).toBeGreaterThan(1);
    });
  });
});
