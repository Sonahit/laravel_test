import React from 'react';
import App from '@src/App';
import { render, fireEvent, cleanup } from '@testing-library/react';
import data from './__mocks__/fetchMock';

beforeAll(() => {
  fetch.mockResponse(data);
});

afterEach(() => {
  cleanup();
});

const linkSelector = href => `a[href="${href}"]`;

test('should navigate to /import', () => {
  const { container } = render(<App />);
  fireEvent.click(document.querySelector(linkSelector('/import')));
  expect(location.href).toMatch('import');
});

test('should navigate from /import to /', () => {
  const { container } = render(<App />);
  fireEvent.click(document.querySelector(linkSelector('/import')));
  fireEvent.click(document.querySelector(linkSelector('/')));
  expect(location.href).not.toMatch('/import');
});
