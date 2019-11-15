import "@testing-library/jest-dom/extend-expect";
import React from "react";
import { render, fireEvent, cleanup, wait } from "@testing-library/react";
import App from "@src/App.js";

import data from "./__mocks__/fetchMock";
import csv from "./__mocks__/csvMock";

beforeAll(() => {
    fetch.mockResponse(data);
});

afterEach(() => {
    cleanup();
});

test("should render App", () => {
    const app = render(<App />);
    expect(app).toBeTruthy();
});

describe("when has data", () => {
    beforeEach(() => {
        cleanup();
    });
    test("should filter data", () => {
        const app = render(<App />);
        return wait(() => app.rerender(<App />)).then(() => {
            expect(fetch.mock.calls.length).toBeGreaterThan(0);
            expect(app).toBeTruthy();
            const table = document.querySelector(".main-table");
            expect(table).toBeTruthy();
            const selectOption = text => (app.getByText(text).selected = true);
            selectOption("Номеру полёта");
            fireEvent.change(app.getByRole("filters"));
            app.rerender(<App />);
            expect(app.getByPlaceholderText("От")).toBeTruthy();
            fireEvent.input(app.getByPlaceholderText("До"), { target: { value: 0 } });
            app.rerender(<App />);
            const tbody = app.getByRole("table-body");
            expect(tbody.rows.length).toBeLessThanOrEqual(1);
        });
    });

    test("should sort data", () => {
        const app = render(<App />);
        return wait(() => app.rerender(<App />)).then(() => {
            expect(fetch.mock.calls.length).toBeGreaterThan(0);
            expect(app).toBeTruthy();
            const table = document.querySelector(".main-table");
            expect(table).toBeTruthy();
            const tBody = () => Array.from(app.getByRole("table-body").children).map(tr => tr.outerHTML);
            const tbody = tBody();
            fireEvent.click(app.getByText("Номер полёта"));
            app.rerender(<App />);
            let span = app.getByText("Номер полёта");
            expect(span.className).toMatch("asc");
            fireEvent.click(app.getByText("Номер полёта"));
            app.rerender(<App />);
            span = app.getByText("Номер полёта");
            expect(span.className).toMatch("desc");
            const sortedTBody = tBody();
            expect(sortedTBody).toEqual(tbody.reverse());
        });
    });
});

describe("import data", () => {
    test("should upload csv file", () => {
        const app = render(<App />);
        fireEvent.click(app.getByText("Import"));
        return wait(() => app.rerender(<App />)).then(() => {
            expect(location.href).toMatch("import");
            const file = new File([csv], "csv", {
                type: "text/csv"
            });
            const input = app.getByLabelText("Choose File");
            fireEvent.click(input, "files", {
                value: [file]
            });
            expect(input.value).toBeDefined();
            const impBtn = app.getByRole("import");
            fireEvent.click(impBtn);
            expect(location.href).not.toMatch("import");
            app.rerender(<App />);
            expect(app.getByRole("table-body").children.length).toBeGreaterThan(1);
        });
    });
});
