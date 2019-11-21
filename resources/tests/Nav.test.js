import '@testing-library/jest-dom/extend-expect';
import React from 'react';
import {render, fireEvent} from "@testing-library/react"

import Nav from "@components/Nav/Nav"


describe("<Nav/>", () => {
    
    describe("rendering", () => {
        const navProps = {
            links: []
        }
        test("should render", () => {
            const app = render(<Nav {...navProps} />)
            expect(app).toBeTruthy();
        })
    })
})