import PropTypes from "prop-types"
import React from "react"

import Logo from "../images/uturnr.svg"

const Header = ({ siteTitle }) => (
  <header
    style={{
      marginBottom: `1.45rem`,
    }}
  >
    <div
      style={{
        margin: `0 auto`,
        maxWidth: 500,
        padding: `2rem 0.3rem 5rem`,
      }}
    >
      <Logo
        style={{
          width: `18rem`,
        }}
      />
    </div>
  </header>
)

Header.propTypes = {
  siteTitle: PropTypes.string,
}

Header.defaultProps = {
  siteTitle: ``,
}

export default Header
