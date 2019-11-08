import React from "react";
import App from "@src/App.js";
import { render, fireEvent, cleanup } from "@testing-library/react";

afterEach(() => {
    cleanup();
});

const linkSelector = href => `a[href="${href}"]`;

test("should navigate to /import", () => {
    const { container } = render(<App />);
    fireEvent.click(document.querySelector(linkSelector("/import")));
    expect(container.innerHTML).toMatch("Choose file");
});

test("should navigate from /import to /", () => {
    const { container } = render(<App />);
    fireEvent.click(document.querySelector(linkSelector("/import")));
    fireEvent.click(document.querySelector(linkSelector("/")));
    expect(container.innerHTML).toMatch("filtering__select");
});
