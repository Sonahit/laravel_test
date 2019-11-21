import '@testing-library/jest-dom/extend-expect';
import React from 'react';
import {render, fireEvent} from "@testing-library/react"

import TableBody from "@components/Table/TableBody"


describe("<TableBody/>", () => {
    describe("rendering", () => {
        const tableBodyProps = {
            table: [],
            filters: [],
            sort: [],
        }
        test("should render", () => {
            const app = render(<TableBody {...tableBodyProps} />)
            expect(app).toBeTruthy();
        })
    })
})