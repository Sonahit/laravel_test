import React from 'react';
import App from '@src/App';
import { render, fireEvent, cleanup } from '@testing-library/react';

jest.mock('./__mocks__/fetchMock');

beforeAll(() => {
  const data = jest.genMockFromModule('./__mocks__/fetchMock');
  fetch.mockResponse(data);
});

afterEach(() => {
  cleanup();
});

test('should navigate to /import', () => {
  const app = render(<App />);
  fireEvent.click(app.getByText(/^Import$/i));
  expect(location.href).toMatch('import');
});

test('should navigate from /import to /', () => {
  const app = render(<App />);
  fireEvent.click(app.getByText(/^Import$/i));
  fireEvent.click(app.getByText(/^Home$/i));
  expect(location.href).not.toMatch('/import');
});
