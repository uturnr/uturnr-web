import React from "react"
import { useStaticQuery, graphql } from "gatsby"
import Img from "gatsby-image"

/*
 * This component is built using `gatsby-image` to automatically serve optimized
 * images with lazy loading and reduced file sizes. The image is loaded using a
 * `useStaticQuery`, which allows us to load the image from directly within this
 * component, rather than having to pass the image data down from pages.
 *
 * For more information, see the docs:
 * - `gatsby-image`: https://gatsby.dev/gatsby-image
 * - `useStaticQuery`: https://www.gatsbyjs.org/docs/use-static-query/
 */

const Headshot = () => {
  const data = useStaticQuery(graphql`
    query {
      headshotImage: file(relativePath: { eq: "cody.jpg" }) {
        childImageSharp {
          fixed(width: 150, height: 150, quality: 100) {
            ...GatsbyImageSharpFixed
          }
        }
      }
    }
  `)

  return <Img
    fixed={data.headshotImage.childImageSharp.fixed}
    style={{
      borderRadius: '12%',
      boxShadow: '0 2px 15px 3px rgba(79,184,72,0.1)',
      marginRight: `2rem`,
    }}
  />
}

export default Headshot
