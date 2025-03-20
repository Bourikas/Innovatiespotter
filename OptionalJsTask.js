async function addAPIsToArray(array) {
    let arrayToReturn = []
    for (let apiUrl of array) {
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }

            const data = await response.json();
            arrayToReturn.push(data);
        } catch (error) {
            console.error('There was a problem with the fetch operation:', error);
        }
    }
    console.log(arrayToReturn)
}



//test
let APIsList = ["https://newsapi.org/v2/top-headlines",
    "https://dogapi.dog/api/v2/facts",
    "https://geocode.xyz/40.748817,-73.985428?json=1"]
console.log(APIsList)

addAPIsToArray(APIsList)