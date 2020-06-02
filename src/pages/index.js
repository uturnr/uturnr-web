import React from "react"

import Headshot from "../components/headshot"
import Layout from "../components/layout"
import SEO from "../components/seo"

const IndexPage = () => (
  <Layout>
    <SEO />
    <h1>Who?</h1>
    <div style={{
      display: "flex"
    }}>
      <Headshot />
      <div style={{
        display: "flex",
        flexDirection: "column",
        justifyContent: "center",
      }}>
        <h2>
          <strong>Cody Robertson</strong>
        </h2>
        <p>
          <a
            href="mailto:cody@uturnr.com"
            style={{ display: `block`, marginBottom: `1rem` }}
          >
            cody@uturnr.com
          </a>
          <a href="https://www.linkedin.com/in/cody-robertson">linkedin.com/in/cody-robertson</a>
        </p>
      </div>
    </div>
    <h1>What?</h1>
    <ul>
      <li>Websites, Web Apps and Mobile Apps</li>
      <li>Other</li>
    </ul>
    <h3>For example:</h3>
    <ul>
      <li>
        <a
          href="https://mscout.mavenwe.com"
        >
          mScout
        </a>
      </li>
      <li>
        <a
          href="https://specials.lol"
        >
          Specials.lol
        </a>
      </li>
    </ul>
    <h1>When?</h1>
    <p>Now.</p>
    <h1>Where?</h1>
    <p>Saskatoon.</p>
    <h1>Why?</h1>
    <p>Why not?</p>
    <h1>How?</h1>
    <ul>
      <li>React</li>
      <li>Javascript</li>
      <li>Typescript</li>
      <li>Google Firebase</li>
        <ul>
          <li>Cloud Firestore Database</li>
          <li>Cloud Functions</li>
        </ul>
      <li>Swift</li>
      <li>Wordpress</li>
      <li>Other</li>
    </ul>
  </Layout>
)

export default IndexPage
