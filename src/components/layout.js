/**
 * Layout component that queries for data
 * with Gatsby's useStaticQuery component
 *
 * See: https://www.gatsbyjs.org/docs/use-static-query/
 */

import React from "react"
import PropTypes from "prop-types"

import Header from "./header"
import "./layout.scss"
import "./uturnr.scss"

const Layout = ({ children }) => {

  const phrases = [
    "Hey, it's better than it used to be.",
    "Thanks for coming to my TED talk.",
    "The End.",
    "Bless your heart.",
    "Thanks!",
    "Nice shoes.",
    "Take care.",
    "💩",
    "Take a deep breath.",
    "If you’re not supposed to eat at night, then why is there a light bulb in the fridge?",
    "Sir, this is an Arby’s.",
    "Let’s take this offline.",
    "Yes, and...",
    "It's all Greek to me.",
    "Hear, hear.",
    "Leverage agile frameworks to provide a robust synopsis for high level overviews.",
    "Lorem ipsum dolor sit amet.",
    "I’m not weird, I’m gifted.",
    "I like turtles.",
    "It seemed like the thing to do at the time.",
    "Wanna dance?",
    "Miley Cyrus's Top 5 Scandalous HTML Secrets",
    "Dream big.",
    "You're a smart cookie.",
    "I appreciate you.",
    "On a scale of 1 to 10, you're an 11.",
    "Colours seem brighter when you're around.",
  ];

  const getRandomPhrase = () => {
    return phrases[Math.round(Math.random() * (phrases.length - 1))];
  }

  return (
    <>
      <Header />
      <div
        style={{
          margin: `0 auto`,
          maxWidth: 500,
          padding: `0 1rem 1.5rem`,
        }}
      >
        <main>{children}</main>
      </div>
      <footer
        style={{
          margin: `10rem auto 4rem`,
          maxWidth: 500,
          padding: `0 1rem`,
        }}
      >
        © {new Date().getFullYear()}. {getRandomPhrase()}
      </footer>
    </>
  )
}

Layout.propTypes = {
  children: PropTypes.node.isRequired,
}

export default Layout
