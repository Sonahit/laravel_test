import '@testing-library/jest-dom/extend-expect';
import React from 'react';
import {render, fireEvent} from "@testing-library/react"

import TableHead from "@components/Table/TableHead"


describe("<TableHead/>", () => {
    describe("rendering", () => {
        const tableHeadProps = {
            handleSort: jest.fn(),
            tHead: []
        }
        test("should render", () => {
            const app = render(<TableHead {...tableHeadProps} />)
            expect(app).toBeTruthy();
        })
    })
})