## Divi Custom Modules

Note: This project was bootstrapped with [Create Divi Extension](https://github.com/elegantthemes/create-divi-extension).

Author: N Palmer

This is a plugin for the [WordPress Divi Builder](https://www.elegantthemes.com/gallery/divi/), created with PHP, JavaScript, and React. It adds the following features to the Divi Builder:

- **Facebook Feed:** In combination with the Smash Balloon Facebook Feed plugin, this creates a module for your websites that displays a Facebook feed of your choice.
- **Header:** Displays a page header. Also adds a login link and search bar. Modifiable in real-time using the page builder via React.
- **Home Value:** Displays a Home Value module. Entering a home information will connect to Redfin via cURL and display the home value information on the page, as well as emailing it to the user via PHP.
- **Listings:** This feature allows the website owner to input their city, state and zip into the WordPress control panel and returns the following:
  - List of schools in the area
  - List of churches in the area
  - List of charities in the area
  - Top 200 restaurants in the area
  - Top 200 businesses in the area
  - The latest news articles from the area (via Patch)
  - Facebook events from that area
  - Groupon deals from within 5 miles of the location
  - Also connects to the owner's Real Estate website if provided and returns their current listings.
- **Reviews:** Displays the first 3 reviews from any business (via Yelp API).
- **Team:** Adds an "Our Team" module. User can input individual members which display responsively on the page. Updatable in real-time via React.

### Available scripts:

In the project directory, you can run:

##### `yarn start`

Builds the extension in the development mode. Open your WordPress site to view it in the browser. The page will reload if you make edits to JavaScript files. You will also see any lint errors in the console.

##### `yarn build`

Builds the extension for production to the `build` folder. It correctly optimizes the build for the best performance.

##### `yarn zip`

Runs `build` and then creates a production release zip file.