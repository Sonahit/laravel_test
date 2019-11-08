import React from "react";
import { render, fireEvent, cleanup } from "@testing-library/react";
import App from "@src/App.js";

afterEach(() => {
    cleanup();
});

test("should render App", () => {
    const app = render(<App />);
    expect(app).toBeTruthy();
});
