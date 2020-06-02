module.exports = {
  siteMetadata: {
    title: `Uturnr Software`,
    description: `Kick off your next, great Gatsby project with this default starter. This barebones starter ships with the main Gatsby configuration files you might need.`,
    author: `@gatsbyjs`,
  },
  plugins: [
    `gatsby-plugin-react-helmet`,
    {
      resolve: `gatsby-source-filesystem`,
      options: {
        name: `images`,
        path: `${__dirname}/src/images`,
      },
    },
    `gatsby-transformer-sharp`,
    {
      resolve: `gatsby-plugin-sharp`,
      options: {
        stripMetadata: true,
        defaultQuality: 100,
      },
    },
    {
      resolve: `gatsby-plugin-manifest`,
      options: {
        name: `uturnr`,
        short_name: `uturnr`,
        start_url: `/`,
        background_color: `#4FB848`,
        theme_color: `#fff`,
        display: `minimal-ui`,
        icon: `src/images/u.png`,
      },
    },
    `gatsby-plugin-sass`,
    `gatsby-plugin-react-svg`,
    {
      resolve: `gatsby-plugin-google-analytics`,
      options: {
        trackingId: "UA-115499012-3",
      },
    },
  ],
}
