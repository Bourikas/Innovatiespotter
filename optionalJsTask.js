/**
 * Asynchronously fetches data from an array of API URLs and stores the results.
 *
 * @param {string[]} array - An array of API endpoint URLs.
 * @returns {Promise<void>} - The function does not return anything explicitly but logs the fetched data.
 */
async function addAPIsToArray(array) {
    let arrayToReturn = []; // Stores the fetched data

    for (let apiUrl of array) { // Iterates through each API URL
        try {
            // Fetch data from the API
            const response = await fetch(apiUrl);

            // Check if the response status is OK (200-299)
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }

            // Parse the JSON response
            const data = await response.json();

            // Store the retrieved data in the array
            arrayToReturn.push(data);
        } catch (error) {
            // Log any errors encountered during the fetch operation
            console.error('There was a problem with the fetch operation:', error);
        }
    }

    // Log the final array containing the fetched API data
    console.log(arrayToReturn);
}

//test
let APIsList = ["https://newsapi.org/v2/top-headlines",
    "https://dogapi.dog/api/v2/facts",
    "https://geocode.xyz/40.748817,-73.985428?json=1"]
console.log(APIsList)

addAPIsToArray(APIsList)