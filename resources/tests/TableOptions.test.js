import '@testing-library/jest-dom/extend-expect';
import React from 'react';
import {render, fireEvent} from "@testing-library/react"

import TableOptions from "@components/TableOptions/TableOptions"


describe("<TableOptions/>", () => {
    
    describe("rendering", () => {
        const tableOptionsProps = {
            method: "",
            filters: {},
            handleFilterSelect: jest.fn(),
            handleFilterValue: jest.fn(),
            handleFilterReset: jest.fn(),
            stopRenderImport: jest.fn(),
            resetAllFilters: jest.fn(),
            fetchAllData: jest.fn(),
            rememberTable: jest.fn(),
            forgetTable: jest.fn(),
            refreshTable: jest.fn(),
        }
        test("should render", () => {
            const app = render(<TableOptions {...tableOptionsProps} />)
            expect(app).toBeTruthy();
        })
    })
})