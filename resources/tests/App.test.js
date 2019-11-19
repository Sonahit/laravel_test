import '@testing-library/jest-dom/extend-expect';
import React from 'react';
import { render, fireEvent, cleanup, wait } from '@testing-library/react';
import App from '@src/App';

import data from './__mocks__/fetchMock';
import csv from './__mocks__/csvMock';
import { Simulate } from 'react-dom/test-utils';

beforeAll(() => {
  fetch.mockResponse(data);
});

afterEach(() => {
  cleanup();
});

test('should render App', () => {
  const app = render(<App />);
  expect(app).toBeTruthy();
});

describe('when has data', () => {
  beforeEach(() => {
    cleanup();
  });
  test('should filter data', async () => {
    const app = render(<App />);
    await wait(() => app.rerender(<App />));
    expect(fetch.mock.calls.length).toBeGreaterThan(0);
    expect(app).toBeTruthy();
    const table = document.querySelector('.main-table');
    expect(table).toBeTruthy();
    const selectOption = text => (app.getByText(text).selected = true);
    selectOption('Номеру полёта');
    fireEvent.change(app.getByRole('filters'));
    app.rerender(<App />);
    expect(app.getByPlaceholderText('От')).toBeTruthy();
    fireEvent.input(app.getByPlaceholderText('До'), { target: { value: 0 } });
    app.rerender(<App />);
    const tbody = app.getByRole('table-body');
    expect(tbody.rows.length).toBeLessThanOrEqual(1);
  });

  test('should sort data', async () => {
    const app = render(<App />);
    await wait(() => app.rerender(<App />));
    expect(fetch.mock.calls.length).toBeGreaterThan(0);
    expect(app).toBeTruthy();
    const table = document.querySelector('.main-table');
    expect(table).toBeTruthy();
    const tBody = () => Array.from(app.getByRole('table-body').children).map(tr => tr.outerHTML);
    const tbody = tBody();
    fireEvent.click(app.getByText('Номер полёта'));
    app.rerender(<App />);
    let span = app.getByText('Номер полёта');
    expect(span.className).toMatch('asc');
    fireEvent.click(app.getByText('Номер полёта'));
    app.rerender(<App />);
    span = app.getByText('Номер полёта');
    expect(span.className).toMatch('desc');
    const sortedTBody = tBody();
    expect(sortedTBody).toEqual(tbody.reverse());
  });
});

describe('import data', () => {
  test('should upload csv file', async () => {
    const app = render(<App />);
    fireEvent.click(app.getByText('Import'));
    await wait(() => app.rerender(<App />));
    expect(location.href).toMatch('import');
    const file = new File([csv], 'csv', {
      type: 'text/csv'
    });
    const input = app.getByLabelText('Choose File');
    Simulate.change(input, {
      target: { files: [file] }
    });
    await wait(() => app.rerender(<App />), { timeout: 5000 });
    const impBtn = app.getByRole('import');
    const label = app.getByRole('import_data');
    expect(label.innerText).not.toMatch('Choose file');
    fireEvent.click(impBtn);
    app.rerender(<App />);
    expect(location.href).not.toMatch('import');
    expect(app.getByRole('table-body').children.length).toBeGreaterThan(1);
  });
});
