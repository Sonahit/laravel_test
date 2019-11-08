import React from "react";
import PropTypes from "prop-types";

import "./Nav.scss";

import { Link } from "react-router-dom";

export default function Nav(props) {
    const { links } = props;
    return (
        <nav>
            <ul className="pagination">
                {links.map(({ link, text }) => (
                    <NavLink key={link} link={link} text={text} />
                ))}
            </ul>
        </nav>
    );
}

Nav.propTypes = {
    links: PropTypes.array.isRequired
};

const NavLink = ({ link, text }) => {
    return (
        <li className="page-item">
            <Link to={link} className="page-link">
                {text}
            </Link>
        </li>
    );
};

NavLink.propTypes = {
    link: PropTypes.string,
    text: PropTypes.string
};
