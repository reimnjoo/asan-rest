# ASAN Integrated: A WAREHOUSE MANAGEMENT SYSTEM WITH COMMUNICATION
SUBSYSTEM FOR SCRAP YARDS IN THE PHILIPPINES 

Welcome to the Scrapyard Warehouse Management App. This application is designed to streamline your warehouse operations, enhancing efficiency and accuracy through automation. By integrating our WMS into your business, you can achieve better inventory management, improved accuracy, and streamlined processes through our communication subsystem. Our system reduces manual processes, minimizes errors, and maximizes your business potential, providing you with a competitive edge in the logistics and supply chain industry.

* **Paypal** and **GetStream.io** support.
* **Mapbox** support integrated inside user-verification section.

## Who is ASAN Integrated designed for?

* **Scrapyard Owners/Sellers** - designed for scrap yard owners to boost operational efficiency and cut financial losses. By automating warehouse and communication systems, it reduces manual effort and time spent managing stock, allowing owners to focus on business strategy. Real-time visibility and accuracy minimize errors, misplaced items, and inaccurate counts, leading to increased profitability.
* **Affiliated Businesses** - Affiliated businesses fosters the bond and strength between scrap yard owners and other affiliated buyers alike. With affiliations, it enables ASAN to perfectly streamline the communication using the subsystems made for both end-users of our application.


## Main Features

1. Input Daily Scrap (Day to day accepted scrap cycle for scrapyard owners/sellers).
2. View Weekly Scrap (View the Weekly scrap statistics of your scrapyard warehouse input of the week).
3. View Overall Scrapyard Stock (You have access to the total count of your stock count, leveraging ease of access in monitor scrapyard warehouse capacity).
4. Integrated Chat Subcommunication System (Leveraged for both Scrapyard Owners/Sellers and Affiliated Buyers)
5. User Dashboard Interface for Chat Moderation using GetStreamIo, PayPal, and a Web Administration for our ASAN Integrated App (that handles user verifications, account reactivations, and audit logs).


## Usage Manual

### ASAN Integrated and Web Administration

#### CREDENTIALS USED IN THE APPLICATION:

```bash

# Paypal Sandbox Account (To test subscription):
// Email: aladiahfulminar@seoltech.com
// Password: sonicglider1X

# Web Administration Account (To test web administration):
// Username: fslagman
// Password: password01230

```

#### Introduction:

Welcome to the WMS (Warehouse Management System) User Manual. This guide will
help you navigate and utilize the key features of the WMS efficiently. There are three types
of accounts within the WMS: Owner, Buyer, and Administrator. Each account type has
specific functionalities designed to cater to different roles within the system.

1. **Owner Account** - As an Owner, you have access to the following features:

    **Input Daily Scrap**

        a. Login to your Owner account.
        b. Navigate to the Daily Scrap Input section from the main dashboard.
        c. Enter the Date, Type of Scrap, Quantity, and any additional notes.
        d. Click Submit to save the entry.

    **View Weekly Scrap**

        a. Steps to View Weekly Scrap:
        b. Login to your Owner account.
        c. Navigate to the Weekly Scrap section from the main dashboard.
        d. Select the desired Week from the dropdown menu.
        e. Review the summarized data for the selected week.

    **View Overall Scrapyard Stock**

        a. Steps to View Overall Scrapyard Stock:
        b. Login to your Owner account.
        156
        c. Navigate to the Overall Scrapyard Stock section from the main
        dashboard.
        d. View the total stock levels across all categories and scrapyards.


    **View Weekly Scrap Statistics**

        a. Login to your Owner account.
        b. Navigate to the Weekly Scrap Statistics section from the main
        dashboard.
        c. Review charts and graphs to understand trends and patterns.

2. **Buyer account** - As a Buyer, you have access to the following features:

    **Perform Warehouse Selection**

        a. Login to your Buyer account.
        b. Navigate to the Warehouse Selection section from the main dashboard.
        c. Browse the list of available warehouses.
        d. Select a warehouse to view its details and make your choice.

    **View Overall Stock**

        a. Login to your Buyer account.
        b. Navigate to the Overall Stock section from the main dashboard.
        c. View the total available stock across all warehouses.

    **View Categories of Each Scrapyard**

        a. Login to your Buyer account.
        b. Navigate to the Scrapyard Categories section from the main
        dashboard.
        157
        c. Select a scrapyard to view its categories and available stock within
        each category.

3. **Administrator Account** - As an Administrator, you have access to the following
features:

    **User/Account Management**

        a. Login to your Administrator account.
        b. Navigate to the User Management section from the main dashboard.
        c. View the list of current users.
        d. Add new users by clicking Add User and filling in the necessary
        details.
        e. Edit existing user information by selecting a user and clicking Edit.
        f. Deactivate or delete users as needed.

## Testing our Application in Android Studio

>**Note**: Make sure you have cloned our application in your desired folder in your system. Afterwards, perform the next steps:

```bash
# Select the project folder.
cd asan

# Install the necessary packages.
npm install

# Run the Metro Bundler
npm run start
```
>**Note**: Make sure you have Android Studio and have it properly configured to proceed.

# REACT NATIVE GETTING STARTED SECTION (For those who use newer versions of React Native):

## Step 1: Start the Metro Server

First, you will need to start **Metro**, the JavaScript _bundler_ that ships _with_ React Native.

To start Metro, run the following command from the _root_ of your React Native project:

```bash
# using npm
npm start

# OR using Yarn
yarn start
```

## Step 2: Start your Application

Let Metro Bundler run in its _own_ terminal. Open a _new_ terminal from the _root_ of your React Native project. Run the following command to start your _Android_ or _iOS_ app:

### For Android

```bash
# using npm
npm run android

# OR using Yarn
yarn android
```

### For iOS

```bash
# using npm
npm run ios

# OR using Yarn
yarn ios
```

If everything is set up _correctly_, you should see your new app running in your _Android Emulator_ or _iOS Simulator_ shortly provided you have set up your emulator/simulator correctly.

This is one way to run your app — you can also run it directly from within Android Studio and Xcode respectively.

## Step 3: Modifying your App

Now that you have successfully run the app, let's modify it.

1. Open `App.tsx` in your text editor of choice and edit some lines.
2. For **Android**: Press the <kbd>R</kbd> key twice or select **"Reload"** from the **Developer Menu** (<kbd>Ctrl</kbd> + <kbd>M</kbd> (on Window and Linux) or <kbd>Cmd ⌘</kbd> + <kbd>M</kbd> (on macOS)) to see your changes!

   For **iOS**: Hit <kbd>Cmd ⌘</kbd> + <kbd>R</kbd> in your iOS Simulator to reload the app and see your changes!

## Congratulations! :tada:

You've successfully run and modified your React Native App. :partying_face:

### Now what?

- If you want to add this new React Native code to an existing application, check out the [Integration guide](https://reactnative.dev/docs/integration-with-existing-apps).
- If you're curious to learn more about React Native, check out the [Introduction to React Native](https://reactnative.dev/docs/getting-started).

# Troubleshooting

If you can't get this to work, see the [Troubleshooting](https://reactnative.dev/docs/troubleshooting) page.

# Learn More

To learn more about React Native, take a look at the following resources:

- [React Native Website](https://reactnative.dev) - learn more about React Native.
- [Getting Started](https://reactnative.dev/docs/environment-setup) - an **overview** of React Native and how setup your environment.
- [Learn the Basics](https://reactnative.dev/docs/getting-started) - a **guided tour** of the React Native **basics**.
- [Blog](https://reactnative.dev/blog) - read the latest official React Native **Blog** posts.
- [`@facebook/react-native`](https://github.com/facebook/react-native) - the Open Source; GitHub **repository** for React Native.
